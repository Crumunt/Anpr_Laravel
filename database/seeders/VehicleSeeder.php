<?php

namespace Database\Seeders;

use App\Models\Vehicle\Vehicle;
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

        for ($i = 1; $i <= 100; $i++) {
            $make = $faker->randomElement($vehicleMakes);

            Vehicle::create([
                'owner_id' => $i + 5,
                'license_plate' => strtoupper(Str::random(3)) . ' ' . $faker->numberBetween(100, 9999),
                'vehicle_type' => $faker->randomElement($vehicleTypes),
                'vehicle_make' => $make,
                'vehicle_model' => $faker->word(),
                'vehicle_year' => $faker->numberBetween(2000, 2023),
                'assigned_gate_pass' => $faker->numberBetween(100,9999), 
                'status_id' => $faker->numberBetween(1, 8),
            ]);
        }
    }
}
