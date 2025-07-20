<?php

namespace Database\Seeders;

use App\Models\Vehicle\orcr as VehicleOrcr;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ORCRSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        for($i = 1; $i <= 100; $i++) {
            VehicleOrcr::create([
                'vehicle_id' => $i + 5,
                'document_path' => Str::random(16)
            ]);
        }
    }
}
