<?php

namespace Database\Seeders;

use App\Models\Vehicle\Vehicle;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $faker = Faker::create();
        $vehicleTypes = ['Sedan', 'SUV', 'Motorcycle', 'Van'];
        $vehicleMakes = ['Toyota', 'Honda', 'Ford', 'Mitsubishi', 'Chevrolet', 'Hyundai', 'Nissan'];
        $vehicle_status = [2,3,7,8];
        for ($i = 7; $i < 906; $i++) {
            

            Vehicle::create([
                'owner_id' => $i,
                
            ]);
        }
    }
}
