<?php

namespace Database\Seeders;

use App\Models\User as ModelsUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $faker = Faker::create();

        // SuperAdmin
        ModelsUser::create([
            'first_name' => $faker->firstName(),
            'middle_name' => $faker->lastName(),
            'last_name' => $faker->lastName(),
            'email' => $faker->email(),
            'password' => $faker->password(),
            'phone_number' => $faker->phoneNumber(),
            'role_id' => 1,
            'status_id' => 1,
        ]);

        for ($i = 0; $i < 5; $i++) {
            ModelsUser::create([
                'first_name' => $faker->firstName(),
                'middle_name' => $faker->lastName(),
                'last_name' => $faker->lastName(),
                'email' => $faker->email(),
                'password' => $faker->password(),
                'phone_number' => $faker->phoneNumber(),
                'role_id' => $faker->numberBetween(2, 4),
                'status_id' => $faker->numberBetween(1, 8),
            ]);
        }

        for ($i = 1; $i < 100; $i++) {
            ModelsUser::create([
                'user_id' => $faker->optional(0.3)->numberBetween(1000, 9999),
                'first_name' => $faker->firstName(),
                'middle_name' => $faker->optional(0.3)->lastName(),
                'last_name' => $faker->lastName(),
                'email' => $faker->email(),
                'password' => $faker->password(),
                'phone_number' => $faker->phoneNumber(),
                'license' => $faker->randomNumber(5),
                'role_id' => $faker->numberBetween(5, 7),
                'status_id' => $faker->numberBetween(1, 8),
            ]);

        }
    }
}
