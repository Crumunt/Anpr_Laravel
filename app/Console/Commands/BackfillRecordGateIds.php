<?php

namespace App\Console\Commands;

use App\Models\ANPR\Gate;
use App\Models\ANPR\Record;
use Illuminate\Console\Command;

class BackfillRecordGateIds extends Command
{
    protected $signature = 'records:backfill-gate-ids';

    protected $description = 'Backfill gate_id for existing records based on gate_type and location';

    public function handle(): int
    {
        $records = Record::whereNull('gate_id')->get();

        if ($records->isEmpty()) {
            $this->info('No records found without gate_id.');
            return self::SUCCESS;
        }

        $this->info("Found {$records->count()} records to update.");

        $updated = 0;
        $skipped = 0;

        $bar = $this->output->createProgressBar($records->count());
        $bar->start();

        foreach ($records as $record) {
            // Try to find gate by gate_type (as gate_name) and location
            $gate = Gate::where('gate_name', $record->gate_type)
                ->where('gate_location', $record->location)
                ->first();

            // If not found, try by slug
            if (!$gate && $record->gate_type) {
                $gate = Gate::where('slug', $record->gate_type)->first();
            }

            if ($gate) {
                $record->update([
                    'gate_id' => $gate->id,
                    'gate_type' => $gate->slug,
                ]);
                $updated++;
            } else {
                $skipped++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        $this->info("Updated: {$updated} records");
        if ($skipped > 0) {
            $this->warn("Skipped: {$skipped} records (no matching gate found)");
        }

        return self::SUCCESS;
    }
}
