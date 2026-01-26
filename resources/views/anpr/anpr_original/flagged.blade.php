@extends('layouts.app-layout')

@section('title', 'CLSU Vehicle Monitoring System - Flagged Vehicles')

@section('styles')
<x-anpr.ui.styles />
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<!-- Ensure Alpine.js and Font Awesome are included in layouts.app-layout for flag-vehicle-modal drag-and-drop and icon functionality -->
@endsection

@section('content')
@php
$headers = [
    ['key' => 'license_plate', 'label' => 'License Plate'],
    ['key' => 'model', 'label' => 'Vehicle Info'],
    ['key' => 'reason_label', 'label' => 'Flag Reason'],
    ['key' => 'flagged_by', 'label' => 'Flagged By'],
    ['key' => 'date_flagged', 'label' => 'Date Flagged'],
    ['key' => 'status', 'label' => 'Status'],
    ['key' => 'actions', 'label' => 'Actions'],
];

$flaggedVehicles = [
    [
        'license_plate' => 'PQR-987',
        'model' => 'Mitsubishi Montero',
        'color' => 'Black',
        'type' => 'SUV',
        'reason' => 'suspicious',
        'reason_label' => 'Suspicious Activity',
        'reason_description' => 'Multiple unauthorized access attempts',
        'flagged_by' => 'John Doe',
        'flagged_by_role' => 'Security Officer',
        'date_flagged' => 'Mar 28, 2025',
        'time_flagged' => '14:15:02',
        'status' => 'active',
        'priority' => 'high',
        'image_url' => 'https://placehold.co/400x300/111827/cccccc?text=Vehicle+Image',
        'registration_status' => 'Valid until Dec 2025',
        'owner_name' => 'Unknown',
        'owner_info' => 'No registered owner in system',
        'status_detail' => 'Under Surveillance',
        'last_updated' => 'March 28, 2025 - 15:30:22',
        'incident_history' => [
            [
                'type' => 'attempted_entry',
                'icon' => 'exclamation-circle',
                'title' => 'Attempted Entry - Main Entrance',
                'date' => 'Mar 28, 2025',
                'time' => '14:15:02',
                'description' => 'Vehicle attempted to enter through main gate without proper authorization. Driver claimed to be visiting faculty but could not provide name or department.',
            ],
            [
                'type' => 'suspicious_activity',
                'icon' => 'eye',
                'title' => 'Suspicious Activity - Parking Area',
                'date' => 'Mar 27, 2025',
                'time' => '16:42:18',
                'description' => 'Vehicle observed circling restricted parking areas and taking photographs of security installations. Left when approached by security personnel.',
            ],
            [
                'type' => 'attempted_entry',
                'icon' => 'ban',
                'title' => 'Attempted Entry - Back Gate',
                'date' => 'Mar 25, 2025',
                'time' => '08:15:47',
                'description' => 'Vehicle attempted to tailgate behind an authorized vehicle at the back gate. Security stopped the vehicle, and driver claimed to be lost before leaving the area.',
            ],
        ],
        'comments' => [
            [
                'user_name' => 'John Doe',
                'user_initials' => 'JD',
                'user_initials_color' => 'indigo',
                'date' => 'Mar 28, 2025',
                'time' => '14:30:22',
                'text' => 'I\'ve notified all security personnel to be on the lookout for this vehicle. We should consider contacting local authorities if the vehicle is spotted again.',
            ],
            [
                'user_name' => 'Robert Chen',
                'user_initials' => 'RC',
                'user_initials_color' => 'purple',
                'date' => 'Mar 28, 2025',
                'time' => '15:15:08',
                'text' => 'I\'ve reviewed the camera footage from all incidents. The driver appears to be the same person in all cases. We should escalate this to campus police for further investigation.',
            ],
        ],
        'actions' => [
            [
                'label' => 'View details',
                'icon' => 'eye',
                'onclick' => "viewFlagDetails('PQR-987')",
                'tooltip' => 'View details',
                'color' => 'text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100'
            ],
            [
                'label' => 'Resolve flag',
                'icon' => 'check',
                'onclick' => "resolveFlag('PQR-987')",
                'tooltip' => 'Resolve',
                'color' => 'text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100'
            ],
            [
                'label' => 'Escalate flag',
                'icon' => 'arrow-up',
                'onclick' => "escalateFlag('PQR-987')",
                'tooltip' => 'Escalate',
                'color' => 'text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100'
            ],
        ]
    ],
    [
        'license_plate' => 'LMN-456',
        'model' => 'Ford Ranger',
        'color' => 'Blue',
        'type' => 'Pickup',
        'reason' => 'unauthorized',
        'reason_label' => 'Unauthorized Access',
        'reason_description' => 'Attempted entry without valid credentials',
        'flagged_by' => 'Sarah Williams',
        'flagged_by_role' => 'System Administrator',
        'date_flagged' => 'Mar 27, 2025',
        'time_flagged' => '09:42:18',
        'status' => 'active',
        'priority' => 'high',
        'actions' => [
            [
                'label' => 'View details',
                'icon' => 'eye',
                'onclick' => "viewFlagDetails('LMN-456')",
                'tooltip' => 'View details',
                'color' => 'text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100'
            ],
            [
                'label' => 'Resolve flag',
                'icon' => 'check',
                'onclick' => "resolveFlag('LMN-456')",
                'tooltip' => 'Resolve',
                'color' => 'text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100'
            ],
            [
                'label' => 'Escalate flag',
                'icon' => 'arrow-up',
                'onclick' => "escalateFlag('LMN-456')",
                'tooltip' => 'Escalate',
                'color' => 'text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100'
            ],
        ]
    ],
    [
        'license_plate' => 'ABC-123',
        'model' => 'Toyota Camry',
        'color' => 'Silver',
        'type' => 'Sedan',
        'reason' => 'expired',
        'reason_label' => 'Expired Registration',
        'reason_description' => 'Vehicle registration expired on Mar 15, 2025',
        'flagged_by' => 'Michael Johnson',
        'flagged_by_role' => 'Security Officer',
        'date_flagged' => 'Mar 26, 2025',
        'time_flagged' => '11:23:45',
        'status' => 'pending',
        'priority' => 'medium',
        'actions' => [
            [
                'label' => 'View details',
                'icon' => 'eye',
                'onclick' => "viewFlagDetails('ABC-123')",
                'tooltip' => 'View details',
                'color' => 'text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100'
            ],
            [
                'label' => 'Approve flag',
                'icon' => 'check',
                'onclick' => "approveFlag('ABC-123')",
                'tooltip' => 'Approve',
                'color' => 'text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100'
            ],
            [
                'label' => 'Reject flag',
                'icon' => 'times',
                'onclick' => "rejectFlag('ABC-123')",
                'tooltip' => 'Reject',
                'color' => 'text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100'
            ],
        ]
    ],
    [
        'license_plate' => 'XYZ-789',
        'model' => 'Honda CR-V',
        'color' => 'White',
        'type' => 'SUV',
        'reason' => 'investigation',
        'reason_label' => 'Under Investigation',
        'reason_description' => 'Reported for suspicious behavior in restricted areas',
        'flagged_by' => 'Robert Chen',
        'flagged_by_role' => 'Security Supervisor',
        'date_flagged' => 'Mar 25, 2025',
        'time_flagged' => '15:47:32',
        'status' => 'monitoring',
        'priority' => 'medium',
        'actions' => [
            [
                'label' => 'View details',
                'icon' => 'eye',
                'onclick' => "viewFlagDetails('XYZ-789')",
                'tooltip' => 'View details',
                'color' => 'text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100'
            ],
            [
                'label' => 'Resolve flag',
                'icon' => 'check',
                'onclick' => "resolveFlag('XYZ-789')",
                'tooltip' => 'Resolve',
                'color' => 'text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100'
            ],
            [
                'label' => 'Update status',
                'icon' => 'edit',
                'onclick' => "updateFlagStatus('XYZ-789')",
                'tooltip' => 'Update status',
                'color' => 'text-amber-600 hover:text-amber-900 bg-amber-50 hover:bg-amber-100'
            ],
        ]
    ],
    [
        'license_plate' => 'DEF-567',
        'model' => 'Hyundai Elantra',
        'color' => 'Red',
        'type' => 'Sedan',
        'reason' => 'violation',
        'reason_label' => 'Parking Violation',
        'reason_description' => 'Parked in faculty-only area without authorization',
        'flagged_by' => 'Emily Rodriguez',
        'flagged_by_role' => 'Parking Attendant',
        'date_flagged' => 'Mar 24, 2025',
        'time_flagged' => '10:12:05',
        'status' => 'resolved',
        'priority' => 'low',
        'actions' => [
            [
                'label' => 'View details',
                'icon' => 'eye',
                'onclick' => "viewFlagDetails('DEF-567')",
                'tooltip' => 'View details',
                'color' => 'text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100'
            ],
            [
                'label' => 'View report',
                'icon' => 'file-alt',
                'onclick' => "viewFlagReport('DEF-567')",
                'tooltip' => 'View report',
                'color' => 'text-purple-600 hover:text-purple-900 bg-purple-50 hover:bg-purple-100'
            ],
            [
                'label' => 'Archive flag',
                'icon' => 'archive',
                'onclick' => "archiveFlag('DEF-567')",
                'tooltip' => 'Archive',
                'color' => 'text-gray-600 hover:text-gray-900 bg-gray-50 hover:bg-gray-100'
            ],
        ]
    ],
];

$selectedVehicle = $flaggedVehicles[0];

$flagVehicleProps = [
    'vehicleTypes' => ['Sedan', 'SUV', 'Truck', 'Van', 'Motorcycle', 'Other'],
    'flagReasons' => [
        ['value' => 'suspicious', 'label' => 'Suspicious Activity'],
        ['value' => 'unauthorized', 'label' => 'Unauthorized Access'],
        ['value' => 'expired', 'label' => 'Expired Registration'],
        ['value' => 'investigation', 'label' => 'Under Investigation'],
        ['value' => 'violation', 'label' => 'Parking Violation'],
        ['value' => 'other', 'label' => 'Other'],
    ],
    'priorityLevels' => [
        ['value' => 'high', 'label' => 'High'],
        ['value' => 'medium', 'label' => 'Medium'],
        ['value' => 'low', 'label' => 'Low'],
    ],
    'incidentLocations' => [
        'Main Entrance', 'Exit Gate', 'Parking Area', 'Back Gate',
        'Faculty Parking', 'Student Parking', 'Administration Building', 'Other'
    ],
    'notificationOptions' => [
        ['id' => 'notify-security', 'label' => 'Notify Security Team', 'checked' => true],
        ['id' => 'notify-admin', 'label' => 'Notify Administrators', 'checked' => true],
        ['id' => 'add-to-watchlist', 'label' => 'Add to Security Watchlist', 'checked' => true],
    ],
    'fieldRequirements' => [
        'license-plate' => true,
        'vehicle-model' => false,
        'vehicle-color' => false,
        'vehicle-type' => false,
        'flag-reason' => true,
        'priority-level' => true,
        'flag-description' => true,
        'incident-location' => true,
    ],
];
@endphp

<div class="flex h-screen overflow-hidden">
    <div id="overlay" class="overlay" onclick="toggleSidebar()"></div>
    {{-- Sidebar --}}
    @include('components.anpr.nav.sidebar')
    <div id="main-content" class="flex-1 overflow-auto bg-gray-50">
        @include('components.anpr.nav.header')

        {{-- Dashboard Statistics --}}
        <x-anpr.flagged.dashboard-statistics
            :total-flagged="['count' => 36, 'change' => '8%', 'isIncrease' => true]"
            :active-flags="['count' => 18, 'change' => '12%', 'isIncrease' => true]"
            :pending-review="['count' => 7, 'change' => '3%', 'isIncrease' => false]"
            :resolved="['count' => 11, 'change' => '15%', 'isIncrease' => true]"
        />

        {{-- Flagged Vehicles Management Section --}}
        <x-anpr.flagged.flagged-vehicles-management>
            <x-anpr.flagged.recent-flagged-table :headers="$headers" :rows="$flaggedVehicles" />
            <x-anpr.anpr-dashboard.ui.pagination
                :current-page="1"
                :total-entries="36"
                :per-page="10"
                :start-entry="1"
                :end-entry="5"
                :total-pages="4"
            />
        </x-anpr.flagged.flagged-vehicles-management>

        {{-- Modals and Toasts --}}
        <x-anpr.flagged.flag-details-modal :vehicle="$selectedVehicle" />
        <x-anpr.flagged.flag-vehicle-modal :$flagVehicleProps />
        <x-anpr.flagged.ui.confirmation-dialog />
        <x-anpr.flagged.ui.notification-toast />
    </div>
</div>

@section('scripts')
<script src="{{ asset('js/anpr/flagged.js') }}"></script>
@endsection