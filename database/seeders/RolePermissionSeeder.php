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
        //
        $permissions = [
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            'assign_roles',

            'view_vehicles',
            'create_vehicles',
            'edit_vehicles',
            'delete_vehicles',

            'view_gate_passes',
            'create_gate_passes',
            'edit_gate_passes',
            'approve_gate_passes',

            'view_anpr_logs',
            'manage_anpr_settings',

            'view_roles',
            'create_roles',
            'edit_roles',
            'delete_roles',
            'assign_permissions',

            'submit_application',
            'view_application_status',
            'view_application_profile',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $roles = [
            'super admin' => Permission::all()->pluck('name')->toArray(),
            'admin' => [
                'view_users',
                'create_users',
                'edit_users',
                'delete_users',

                'view_vehicles',
                'create_vehicles',
                'edit_vehicles',
                'delete_vehicles',

                'view_gate_passes',
                'create_gate_passes',
                'edit_gate_passes',
                'approve_gate_passes',

                'view_anpr_logs',

                'view_roles',
                'create_roles',
                'edit_roles',
                'delete_roles',
            ],
            'security staff' => [
                'view_anpr_logs',
                'view_vehicles'
            ],
            'encoder' => [
                'view_users',
                'create_users',

                'view_vehicles',
                'create_vehicles',

                'view_gate_passes',
                'create_gate_passes',
            ],
            'student' => [
                'submit_application',
                'view_application_status',
                'view_application_profile',
            ],
            'faculty' => [
                'submit_application',
                'view_application_status',
                'view_application_profile',
            ],
            'staff' => [
                'submit_application',
                'view_application_status',
                'view_application_profile',
            ]
        ];

        foreach ($roles as $roleName => $perms) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($perms);
        }
    }
}
