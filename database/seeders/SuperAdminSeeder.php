<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if super_admin role exists, create if not
        if (!Role::where('name', 'super_admin')->exists()) {
            $this->call(RolePermissionSeeder::class);
        }

        // Check if super admin already exists
        $existingAdmin = User::where('email', 'superadmin@clsu.edu.ph')->first();

        if ($existingAdmin) {
            $this->command->info('Super Admin already exists!');
            $this->command->info('Email: superadmin@clsu.edu.ph');
            return;
        }

        // Create super admin user
        $superAdmin = User::create([
            'email' => 'superadmin@clsu.edu.ph',
            'password' => Hash::make('SuperAdmin@123'),
            'must_change_password' => false,
            'is_active' => true,
        ]);

        // Create user details
        $superAdmin->details()->create([
            'first_name' => 'Super',
            'middle_name' => '',
            'last_name' => 'Admin',
            'clsu_id' => 'ADMIN-0001',
        ]);

        // Assign super_admin role
        $superAdmin->assignRole('super_admin');

        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('  Super Admin Account Created!');
        $this->command->info('========================================');
        $this->command->info('  Email:    superadmin@clsu.edu.ph');
        $this->command->info('  Password: SuperAdmin@123');
        $this->command->info('========================================');
        $this->command->info('');
    }
}
