<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // List of permissions
        $permissions = [
            // Users
            'view users',
            'create users',
            'edit users',
            'delete users',

            // Applicants (Admin managing applicants)
            'view applicants',
            'create applicants',
            'edit applicants',
            'delete applicants',
            'approve applicants',
            'reject applicants',

            // Applicant self-service
            'view own profile',
            'edit own profile',
            'submit application',
            'upload documents',
            'view application status',

            // Vehicles
            'view vehicles',
            'create vehicles',
            'edit vehicles',
            'delete vehicles',

            // Gate Pass
            'view gate passes',
            'create gate passes',
            'edit gate passes',
            'delete gate passes',

            // Applications
            'view applications',
            'approve applications',
            'reject applications',

            // Security
            'view camera feed',
            'mark blacklisted',
            'mark flagged',

            // System
            'view reports',
            'manage maintenance',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Define roles and their permissions
        $rolesPermissions = [
            // Full system access
            'super_admin' => Permission::all()->pluck('name')->toArray(),

            // Admins
            'admin_editor' => [
                'view users',
                'create users',
                'edit users',
                'delete users',

                'view applicants',
                'create applicants',
                'edit applicants',
                'delete applicants',
                'approve applicants',
                'reject applicants',

                'view vehicles',
                'create vehicles',
                'edit vehicles',
                'delete vehicles',

                'view gate passes',
                'create gate passes',
                'edit gate passes',
                'delete gate passes',

                'view applications',
                'approve applications',
                'reject applications',

                'view reports',
            ],

            'admin_viewer' => [
                'view users',
                'view applicants',

                'view vehicles',
                'view gate passes',

                'view applications',
                'view reports',
            ],

            // Encoders (data entry staff)
            'encoder' => [
                'create applicants',
                'edit applicants',
                'create vehicles',
                'edit vehicles',
                'create gate passes',
                'edit gate passes',
                'view applications',
            ],

            // Security guards / checkpoint staff
            'security' => [
                'view camera feed',
                'mark blacklisted',
                'mark flagged',
                'view vehicles',
                'view gate passes',
                'view applications',
            ],

            // Maintenance staff
            'maintenance' => [
                'manage maintenance',
                'view reports',
            ],

            // Normal users (Applicants)
            'applicant' => [
                'view own profile',
                'edit own profile',
                'submit application',
                'upload documents',
                'view application status',
            ],
        ];

        // Create roles and assign permissions
        foreach ($rolesPermissions as $roleName => $perms) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($perms);
        }

    }
}
