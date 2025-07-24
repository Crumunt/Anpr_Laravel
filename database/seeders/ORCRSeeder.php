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
        for($i = 7; $i < 906; $i++) {
            VehicleOrcr::create([
                'vehicle_id' => $i,
                'document_path' => Str::random(16)
            ]);
        }
    }
}
