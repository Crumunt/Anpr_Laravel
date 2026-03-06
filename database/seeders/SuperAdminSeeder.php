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
        // Check if roles exist, create if not
        if (!Role::where('name', 'super_admin')->exists()) {
            $this->call(RolePermissionSeeder::class);
        }

        // Create Super Admin
        $this->createSuperAdmin();

        // Create Security Admin
        $this->createSecurityAdmin();
    }

    /**
     * Create Super Admin account
     */
    private function createSuperAdmin(): void
    {
        $existingAdmin = User::where('email', 'superadmin@clsu.edu.ph')->first();

        if ($existingAdmin) {
            $this->command->info('Super Admin already exists!');
            return;
        }

        $superAdmin = User::create([
            'email' => 'superadmin@clsu.edu.ph',
            'password' => Hash::make('SuperAdmin@123'),
            'must_change_password' => false,
            'is_active' => true,
        ]);

        $superAdmin->details()->create([
            'first_name' => 'Super',
            'middle_name' => '',
            'last_name' => 'Admin',
            'clsu_id' => 'ADMIN-0001',
        ]);

        $superAdmin->assignRole('super_admin');

        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('  Super Admin Account Created!');
        $this->command->info('========================================');
        $this->command->info('  Email:    superadmin@clsu.edu.ph');
        $this->command->info('  Password: SuperAdmin@123');
        $this->command->info('========================================');
    }

    /**
     * Create Security Admin account
     */
    private function createSecurityAdmin(): void
    {
        $existingAdmin = User::where('email', 'securityadmin@clsu.edu.ph')->first();

        if ($existingAdmin) {
            $this->command->info('Security Admin already exists!');
            return;
        }

        $securityAdmin = User::create([
            'email' => 'securityadmin@clsu.edu.ph',
            'password' => Hash::make('SecurityAdmin@123'),
            'must_change_password' => false,
            'is_active' => true,
        ]);

        $securityAdmin->details()->create([
            'first_name' => 'Security',
            'middle_name' => '',
            'last_name' => 'Admin',
            'clsu_id' => 'ADMIN-0002',
        ]);

        $securityAdmin->assignRole('security_admin');

        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('  Security Admin Account Created!');
        $this->command->info('========================================');
        $this->command->info('  Email:    securityadmin@clsu.edu.ph');
        $this->command->info('  Password: SecurityAdmin@123');
        $this->command->info('========================================');
        $this->command->info('');
    }
}
