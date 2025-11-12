<?php

namespace Database\Seeders;

use App\ApplicantType;
use App\Helpers\SeederStatusHelper;
use App\Models\User as ModelsUser;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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

        $status = \App\Models\Status::where('code', 'active')->firstOrFail();

        // SuperAdmin
        $super_admin = ModelsUser::create([
            'first_name' => $faker->firstName(),
            'middle_name' => $faker->lastName(),
            'last_name' => $faker->lastName(),
            'email' => $faker->email(),
            'password' => Hash::make('password123'),
        ]);

        $super_admin_status =

            $super_admin->details()->create([
                'clsu_id' => $faker->numerify('EMP-#######'),
                'status_id' => $status->id,
            ]);

        $super_admin->refresh();
        if (Role::where('name', 'super_admin')->exists()) {
            $super_admin->assignRole('super_admin');
        } else {
            Log::warning('Role "super admin" not found!');
        }

        $admin_roles = [
            'admin_editor',
            'admin_viewer',
            'security',
            'encoder',
        ];

        $admin_statuses = [4, 5, 7, 8];
        for ($i = 0; $i < 5; $i++) {
            $status = SeederStatusHelper::generateRandomStatus();

            $admin = ModelsUser::create([
                'first_name' => $faker->firstName(),
                'middle_name' => $faker->lastName(),
                'last_name' => $faker->lastName(),
                'email' => $faker->email(),
                'password' => Hash::make('password123'),
            ]);

            $admin->details()->create([
                'clsu_id' => $faker->numerify('EMP-#####'),
                'phone_number' => $faker->numerify('09#########'),
                'status_id' => $status->id,
            ]);

            $admin->refresh();
            $role = $admin_roles[array_rand($admin_roles)];
            if (Role::where('name', $role)->exists()) {
                $admin->assignRole($role);
            } else {
                Log::warning('Role "$role" not found!');
            }
        }
        $vehicleTypes = ['Sedan', 'SUV', 'Motorcycle', 'Van'];
        $vehicleMakes = ['Toyota', 'Honda', 'Ford', 'Mitsubishi', 'Chevrolet', 'Hyundai', 'Nissan'];

        for ($i = 1; $i < 400; $i++) {
            $random_date = $faker->randomElement([
                $faker->dateTimeBetween(Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()),
                $faker->dateTimeBetween(Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()),
            ]);

            $status = SeederStatusHelper::generateRandomStatus();

            $applicant = ModelsUser::create([
                'first_name' => $faker->firstName(),
                'middle_name' => $faker->optional(0.3)->lastName(),
                'last_name' => $faker->lastName(),
                'email' => $faker->email(),
                'password' => Hash::make('password123'),
                'created_at' => $random_date,
            ]);
            $applicant->details()->create([
                'clsu_id' => $faker->randomElement([
                    $faker->numerify('##-####'),
                    $faker->numerify('EMP-###') . $faker->randomNumber(3),
                ]),
                'current_address' => $faker->address(),
                'street_address' => $faker->streetAddress(),
                'barangay' => $faker->streetAddress(),
                'city_municipality' => $faker->city(),
                'province' => $faker->city(),
                'postal_code' => $faker->postcode(),
                'country' => $faker->country(),
                'license_number' => $faker->randomNumber(9),
                'college_unit_department' => $faker->word(),
                'phone_number' => $faker->numerify('09#########'),
                'applicant_type' => collect(ApplicantType::cases())->random(),
                'status_id' => $status->id,
                'created_at' => $random_date
            ]);

            // VEHICLE SEEDER
            $make = $faker->randomElement($vehicleMakes);
            $applicant->vehicles()->create([
                'license_plate' => strtoupper(Str::random(3)) . ' ' . $faker->numberBetween(100, 9999),
                'vehicle_type' => $faker->randomElement($vehicleTypes),
                'vehicle_make' => $make,
                'vehicle_model' => $faker->word(),
                'vehicle_year' => $faker->numberBetween(2000, 2023),
                'assigned_gate_pass' => $faker->numberBetween(100, 9999),
                'status_id' => $status->id,
                'created_at' => $random_date,
            ]);
            $applicant->refresh();
            $role = 'applicant';
            if (Role::where('name', $role)->exists()) {
                $applicant->assignRole($role);
            } else {
                Log::warning('Role "$role" not found!');
            }
        }
    }
}
