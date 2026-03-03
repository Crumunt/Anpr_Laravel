<?php

namespace Database\Seeders;

use App\Models\ANPR\Gate;
use Illuminate\Database\Seeder;

class GateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gates = [
            ['gate_name' => 'Main Gate', 'gate_location' => 'Entry'],
            ['gate_name' => 'Main Gate', 'gate_location' => 'Exit'],
            ['gate_name' => 'Second Gate', 'gate_location' => 'Entry'],
            ['gate_name' => 'Second Gate', 'gate_location' => 'Exit'],
            ['gate_name' => 'Visionary Gate', 'gate_location' => 'Entry'],
            ['gate_name' => 'Visionary Gate', 'gate_location' => 'Exit'],
            ['gate_name' => 'BGC Gate', 'gate_location' => 'Entry'],
            ['gate_name' => 'BGC Gate', 'gate_location' => 'Exit'],
        ];

        foreach ($gates as $gateData) {
            Gate::firstOrCreate(
                [
                    'gate_name' => $gateData['gate_name'],
                    'gate_location' => $gateData['gate_location'],
                ],
                [
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('Gates seeded successfully!');
    }
}
