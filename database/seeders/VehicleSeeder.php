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
            $make = $faker->randomElement($vehicleMakes);

            Vehicle::create([
                'owner_id' => $i,
                'license_plate' => strtoupper(Str::random(3)) . ' ' . $faker->numberBetween(100, 9999),
                'vehicle_type' => $faker->randomElement($vehicleTypes),
                'vehicle_make' => $make,
                'vehicle_model' => $faker->word(),
                'vehicle_year' => $faker->numberBetween(2000, 2023),
                'assigned_gate_pass' => $faker->numberBetween(100, 9999),
                'status_id' => $faker->randomElement($vehicle_status),
                'created_at' => $faker->randomElement([
                    $faker->dateTimeBetween(Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()),
                    $faker->dateTimeBetween(Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()),
                ]),
            ]);
        }
    }
}
