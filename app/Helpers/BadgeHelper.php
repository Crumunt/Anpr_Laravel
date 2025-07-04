<?php


namespace App\Helpers;


class BadgeHelper
{

    public static function statusClass($status)
    {
        return match ($status) {
            'Active', 'Registered', 'Approved' => 'bg-green-100/80 text-green-800 hover:bg-green-200/60',
            'Inactive' => 'bg-gray-100/80 text-gray-800 hover:bg-gray-200/60',
            'Pending' => 'bg-yellow-100/80 text-yellow-800 hover:bg-yellow-200/60',
            default => 'bg-red-100/80 text-red-800 hover:bg-red-200/60',
        };
    }


    public static function roleClass($role)
    {
        return match ($role) {
            'Super Admin' => 'bg-purple-100/80 text-purple-800 hover:bg-purple-200/60',
            'Admin' => 'bg-blue-100/80 text-blue-800 hover:bg-blue-200/60',
            default => 'bg-indigo-100/80 text-indigo-800 hover:bg-indigo-200/60',
        };
    }

}