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
            'email' => $faker->email(),
            'password' => Hash::make('password123'),
            'must_change_password' => false,
            'is_active' => true
        ]);


        $super_admin->details()->create([
            'first_name' => $faker->firstName(),
            'middle_name' => $faker->lastName(),
            'last_name' => $faker->lastName(),
            'clsu_id' => $faker->numerify('EMP-#######'),
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
                'email' => $faker->email(),
                'password' => Hash::make('password123'),
                'must_change_password' => false,
                'is_active' => $faker->randomElement([false, true])
            ]);

            $admin->details()->create([
                'clsu_id' => $faker->numerify('EMP-#####'),
                'first_name' => $faker->firstName(),
                'middle_name' => $faker->lastName(),
                'last_name' => $faker->lastName(),
                'phone_number' => $faker->numerify('09#########')
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
                'email' => $faker->email(),
                'password' => Hash::make('password123'),
                'created_at' => $random_date,
            ]);
            $applicant->details()->create([
                'clsu_id' => $faker->randomElement([
                    $faker->numerify('##-####'),
                    $faker->numerify('EMP-###') . $faker->randomNumber(3),
                ]),
                'first_name' => $faker->firstName(),
                'middle_name' => $faker->optional(0.3)->lastName(),
                'last_name' => $faker->lastName(),

                'region' => $faker->address(),
                'province' => $faker->city(),
                'municipality' => $faker->city(),
                'barangay' => $faker->streetAddress(),
                'zip_code' => $faker->postcode(),

                'phone_number' => $faker->numerify('09#########'),

                'college_unit_department' => $faker->word(),
                'position' => $faker->optional(0.3)->randomElement(['instructor']),
                'license_number' => $faker->randomNumber(9),
                'created_at' => $random_date
            ]);

            $application = $applicant->applications()->create([
                'applicant_type' => collect(ApplicantType::cases())->random(),
                'status_id' => $status->id,
            ]);

            // VEHICLE SEEDER
            $make = $faker->randomElement($vehicleMakes);
            $applicant->vehicles()->create([
                'application_id' => $application->id,
                'plate_number' => strtoupper(Str::random(3)) . ' ' . $faker->numberBetween(100, 9999),
                'type' => $faker->randomElement($vehicleTypes),
                'make' => $make,
                'model' => $faker->word(),
                'year' => $faker->numberBetween(2000, 2023),
                'assigned_gate_pass' => $faker->numberBetween(100, 9999),
                'color' => $faker->safeColorName(),
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
