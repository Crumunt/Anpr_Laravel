<?php

namespace Database\Seeders;

use App\Models\User as ModelsUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

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
        $super_admin = ModelsUser::create([
            'first_name' => $faker->firstName(),
            'middle_name' => $faker->lastName(),
            'last_name' => $faker->lastName(),
            'email' => $faker->email(),
            'password' => Hash::make('password123'),
            'phone_number' => $faker->phoneNumber(),
            'status_id' => 1,
        ]);
        $super_admin->refresh();
        if (Role::where('name', 'super admin')->exists()) {
            $super_admin->assignRole('super admin');
        } else {
            Log::warning('Role "super admin" not found!');
        }

        $admin_roles = [
            'admin',
            'security staff',
            'encoder'
        ];
        for ($i = 0; $i < 5; $i++) {
            $admin = ModelsUser::create([
                'first_name' => $faker->firstName(),
                'middle_name' => $faker->lastName(),
                'last_name' => $faker->lastName(),
                'email' => $faker->email(),
                'password' => Hash::make('password123'),
                'phone_number' => $faker->phoneNumber(),
                'status_id' => $faker->numberBetween(1, 8),
            ]);
            $admin->refresh();
            $role = $admin_roles[array_rand($admin_roles)];
            if (Role::where('name', $role)->exists()) {
                $admin->assignRole($role);
            } else {
                Log::warning('Role "$role" not found!');
            }
        }
        $applicant_roles = [
            'student',
            'faculty',
            'staff'
        ];
        for ($i = 1; $i < 100; $i++) {
            $applicant = ModelsUser::create([
                'user_id' => $faker->optional(0.9) ->randomElement([
                    $faker->numerify('##-####'),
                    $faker->numerify('EMP-#####'),
                ]),
                'first_name' => $faker->firstName(),
                'middle_name' => $faker->optional(0.3)->lastName(),
                'last_name' => $faker->lastName(),
                'email' => $faker->email(),
                'password' => Hash::make('password123'),
                'phone_number' => $faker->phoneNumber(),
                'license' => $faker->randomNumber(5),
                'status_id' => $faker->numberBetween(1, 8),
            ]);
            $applicant->refresh();
            $role = $applicant_roles[array_rand($applicant_roles)]; 
            if (Role::where('name', $role)->exists()) {
                $applicant->assignRole($role);
            } else {
                Log::warning('Role "$role" not found!');
            }
        }
    }
}
