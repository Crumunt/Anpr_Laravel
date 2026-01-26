@extends('layouts.app-layout')

@section('title', 'CLSU Vehicle Monitoring System - Alerts')

@section('styles')
<x-anpr.ui.styles />
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection

@section('content')
@php
$headers = [
    ['key' => 'alert_id', 'label' => 'Alert ID'],
    ['key' => 'time', 'label' => 'Time'],
    ['key' => 'type_label', 'label' => 'Alert Type'],
    ['key' => 'details', 'label' => 'Details'],
    ['key' => 'location', 'label' => 'Location'],
    ['key' => 'priority', 'label' => 'Priority'],
    ['key' => 'actions', 'label' => 'Actions'],
];

$alerts = [
    [
        'alert_id' => 'ALT-2025-0042',
        'time' => '14:32:45',
        'type' => 'unauthorized',
        'type_label' => 'Unauthorized Vehicle',
        'details' => 'Unregistered vehicle detected. License Plate: PQR-987 • Black Mitsubishi Montero',
        'location' => 'Main Entrance (Camera #1)',
        'priority' => 'critical',
        'status' => 'active',
        'actions' => [
            [
                'label' => 'View details',
                'icon' => 'eye',
                'onclick' => "viewAlertDetails('ALT-2025-0042')",
                'tooltip' => 'View details',
                'color' => 'text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100'
            ],
            [
                'label' => 'Resolve alert',
                'icon' => 'check',
                'onclick' => "resolveAlert('ALT-2025-0042')",
                'tooltip' => 'Resolve',
                'color' => 'text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100'
            ],
            [
                'label' => 'Escalate alert',
                'icon' => 'arrow-up',
                'onclick' => "escalateAlert('ALT-2025-0042')",
                'tooltip' => 'Escalate',
                'color' => 'text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100'
            ],
        ]
    ],
    [
        'alert_id' => 'ALT-2025-0043',
        'time' => '15:10:12',
        'type' => 'suspicious',
        'type_label' => 'Suspicious Activity',
        'details' => 'Suspicious person loitering near parking lot. Wearing dark hoodie, face partially covered.',
        'location' => 'Parking Lot B (Camera #3)',
        'priority' => 'high',
        'status' => 'active',
        'actions' => [
            [
                'label' => 'View details',
                'icon' => 'eye',
                'onclick' => "viewAlertDetails('ALT-2025-0043')",
                'tooltip' => 'View details',
                'color' => 'text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100'
            ],
            [
                'label' => 'Resolve alert',
                'icon' => 'check',
                'onclick' => "resolveAlert('ALT-2025-0043')",
                'tooltip' => 'Resolve',
                'color' => 'text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100'
            ],
            [
                'label' => 'Escalate alert',
                'icon' => 'arrow-up',
                'onclick' => "escalateAlert('ALT-2025-0043')",
                'tooltip' => 'Escalate',
                'color' => 'text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100'
            ],
        ]
    ],
];

$selectedAlert = $alerts[0];

$alertTypes = [
    ['value' => 'unauthorized', 'label' => 'Unauthorized Vehicle'],
    ['value' => 'suspicious', 'label' => 'Suspicious Activity'],
    ['value' => 'system', 'label' => 'System Error'],
    ['value' => 'rfid', 'label' => 'RFID Mismatch'],
    ['value' => 'maintenance', 'label' => 'Maintenance'],
];

$priorityLevels = [
    ['value' => 'critical', 'label' => 'Critical'],
    ['value' => 'high', 'label' => 'High'],
    ['value' => 'medium', 'label' => 'Medium'],
    ['value' => 'low', 'label' => 'Low'],
];

$locations = [
    ['value' => 'Main Entrance', 'label' => 'Main Entrance (Camera #1)'],
    ['value' => 'Exit Gate', 'label' => 'Exit Gate (Camera #2)'],
    ['value' => 'Parking Area', 'label' => 'Parking Area (Camera #3)'],
    ['value' => 'Back Gate', 'label' => 'Back Gate (Camera #4)'],
    ['value' => 'System-wide', 'label' => 'System-wide'],
];

$vehicleTypes = [
    'Sedan', 'SUV', 'Truck', 'Van', 'Motorcycle', 'Other'
];

$assignToOptions = [
    ['value' => '', 'label' => 'Unassigned'],
    ['value' => 'Security Team', 'label' => 'Security Team'],
    ['value' => 'Maintenance Team', 'label' => 'Maintenance Team'],
    ['value' => 'IT Support', 'label' => 'IT Support'],
    ['value' => 'Admin Staff', 'label' => 'Admin Staff'],
    ['value' => 'John Doe', 'label' => 'John Doe'],
    ['value' => 'Sarah Williams', 'label' => 'Sarah Williams'],
];
@endphp

<div class="flex h-screen overflow-hidden">
    <div id="overlay" class="overlay" onclick="toggleSidebar()"></div>
    {{-- Sidebar --}}
    @include('components.anpr.nav.sidebar')
    <div id="main-content" class="flex-1 overflow-auto bg-gray-50">
        @include('components.anpr.nav.header')

        {{-- Dashboard Statistics --}}
        <x-anpr.alerts.dashboard-statistics
            :total-alerts="['count' => 42, 'change' => '12%', 'isIncrease' => true]"
            :critical="['count' => 8, 'change' => '33%', 'isIncrease' => true]"
            :high="['count' => 14, 'change' => '8%', 'isIncrease' => true]"
            :medium="['count' => 12, 'change' => '5%', 'isIncrease' => false]"
            :resolved="['count' => 26, 'change' => '18%', 'isIncrease' => true]"
        />

        {{-- Alerts Management Section --}}
        <x-anpr.alerts.alerts-management>
            <x-anpr.alerts.recent-alerts-table :headers="$headers" :rows="$alerts" />
            <x-anpr.anpr-dashboard.ui.pagination
                :current-page="1"
                :total-entries="42"
                :per-page="10"
                :start-entry="1"
                :end-entry="5"
                :total-pages="5"
            />
        </x-anpr.alerts.alerts-management>

        {{-- Modals and Toasts --}}
        <x-anpr.alerts.alert-details-modal :alert="$selectedAlert" />
        <x-anpr.alerts.add-alert-modal
            :alertTypes="$alertTypes"
            :priorityLevels="$priorityLevels"
            :locations="$locations"
            :vehicleTypes="$vehicleTypes"
            :assignToOptions="$assignToOptions"
        />
        <x-anpr.alerts.ui.confirmation-dialog />
        <x-anpr.alerts.ui.notification-toast />
    </div>
</div>

@section('scripts')
<script src="{{ asset('js/anpr/alerts.js') }}"></script>
@endsection