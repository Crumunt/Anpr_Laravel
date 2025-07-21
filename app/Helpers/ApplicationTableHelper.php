<?php


namespace App\Helpers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class ApplicationTableHelper
{


    public static function headerHelper($context = 'user_admin', $tab = '')
    {
        $base = [
            ['key' => 'id', 'label' => 'ID', 'width' => '200px'],
            ['key' => 'name', 'label' => 'Name', 'width' => '200px'],
            ['key' => 'email', 'label' => 'Email', 'width' => '250px'],
            ['key' => 'phone_number', 'label' => 'Phone', 'width' => '150px'],
        ];

        return match ($context) {
            'user_admin' => match ($tab) {
                    'default' => array_merge($base, [
                        ['key' => 'role', 'label' => 'Role', 'width' => '120px'],
                        ['key' => 'status', 'label' => 'Status', 'width' => '120px'],
                        ['key' => 'lastLogin', 'label' => 'Last Login', 'width' => '150px']
                    ]),
                    'active' => [...$base],
                    'inactive' => array_merge($base, [
                        ['key' => 'inactiveSince', 'label' => 'Inactive Since', 'width' => '150px'],
                        ['key' => 'lastLogin', 'label' => 'Last Login', 'width' => '150px'],
                    ]),
                },
            'vehicles' => match ($tab) {
                    'default' => [
                        ['key' => 'vehicle', 'label' => 'Vehicle', 'width' => '250px'],
                        ['key' => 'owner', 'label' => 'Owner', 'width' => '250px'],
                        ['key' => 'registration_date', 'label' => 'Registration Date', 'width' => '150px'],
                    ]
                },
            'gate_pass' => match ($tab) {
                    'default' => [
                        ['key' => 'gate_pass', 'label' => 'Gate Pass', 'width' => '200px'],
                        ['key' => 'status', 'label' => 'Status', 'width' => '120px'],
                        ['key' => 'assigned_to', 'label' => 'Assigned To', 'width' => '200px']
                    ]
                },
            'user_applicant' => match ($tab) {
                    default => [
                        ...$base,
                        ['key' => 'status', 'label' => 'Status', 'width' => '120px'],
                        ['key' => 'vehicles', 'label' => 'Vehicles', 'width' => '100px'],
                        ['key' => 'submitted_date', 'label' => 'Submitted Date', 'width' => '150px'],
                    ]
                },
            default => []
        };
    }


    public static function renderBadgeClass($label)
    {
        return match ($label) {
            'Active', 'Registered', 'Approved' => 'bg-green-100/80 text-green-800 hover:bg-green-200/60',
            'Inactive' => 'bg-gray-100/80 text-gray-800 hover:bg-gray-200/60',
            'Pending' => 'bg-yellow-100/80 text-yellow-800 hover:bg-yellow-200/60',
            'Super Admin' => 'bg-purple-100/80 text-purple-800 hover:bg-purple-200/60',
            'Admin' => 'bg-blue-100/80 text-blue-800 hover:bg-blue-200/60',
            'Encoder' => 'bg-indigo-100/80 text-indigo-800 hover:bg-indigo-200/60',
            default => 'bg-red-100/80 text-red-800 hover:bg-red-200/60',
        };
    }

    public static function renderCellBadge($key)
    {
        $admin = [
            'Super Admin',
            'Admin',
            'Encoder',
        ];

        return match (true) {
            is_array($key) => ['type' => 'status', 'label' => $key['label']],
            in_array($key, $admin) => ['type' => 'role', 'label' => $key],
            default => null
        };
    }

    public static function getBulkActions(string $type)
    {
        return [
            ['key' => 'export', 'action' => 'bulk-export'],
            $type !== 'admin'
            ? ['key' => 'approve', 'action' => 'approve']
            : ['key' => 'deactivate', 'action' => 'deactivate'],
            ['key' => 'delete', 'action' => 'delete']
        ];
    }

    public static function getVehicleName($vehicle_make, $vehicle_model)
    {
        return $vehicle_make . ' ' . $vehicle_model;
    }

    public static function getFullNameAttribute($first_name, $middle_name, $last_name)
    {
        $middle_initial = self::getMiddleInitialsAttribute($middle_name);
        return $first_name . ' ' . $middle_initial . ' ' . $last_name;
    }

    private static function getMiddleInitialsAttribute($middle_name)
    {
        if (!$middle_name)
            return '';

        // Extract each part (in case of compound middle names)
        $parts = preg_split('/\s+/', $middle_name);
        $initials = array_map(fn($part) => strtoupper(mb_substr($part, 0, 1)) . '.', $parts);

        return implode('', $initials); // e.g., "R.J."
    }

}