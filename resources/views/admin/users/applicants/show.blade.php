<x-details.layout title="Applicant Details" type="applicant">
    @php

        $rfidHeaders = ['Tag Number', 'Status', 'Issue Date', 'Expiry Date'];
        $rfidRows = [];

        $vehicleHeaders = ['Plate Number', 'Make & Model', 'Year', 'Registration Date'];

        $documents = [];
        $accessRecords = [];
        $activities = [];
      @endphp

    <x-slot name="breadcrumb">
        <x-details.parts.breadcrumb :items="$breadcrumbs" />
    </x-slot>

    <x-slot name="header">
        <x-details.parts.profile-header title="{{ $applicant_details['name'] }}"
            initials="{{ $applicant_details['user_details']['name_initials'] }}"
            status="{{ $applicant_details['status']['label'] }}"
            statusClass="{{ $applicant_details['user_details']['status_badge'] }}"
            user_id="{{ $applicant_details['clsu_id'] }}" :isActive="true" />
    </x-slot>

    <x-slot name="mainContent">
        <!-- Personal Information Card -->
        <x-details.parts.info-card title="Personal Information" editId="edit-personal-info-btn">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-details.parts.info-field label="Full Name" :value="$applicant_details['name']" :span="$info['span'] ?? 1" />
                <x-details.parts.info-field label="Email Address" :value="$applicant_details['email']"
                    :span="$info['span'] ?? 1" />
                <x-details.parts.info-field label="Phone Number" :value="$applicant_details['phone_number']"
                    :span="$info['span'] ?? 1" />
                <x-details.parts.info-field label="License Number" :value="$user_details['license_number']"
                    :span="$info['span'] ?? 1" />
            </div>
        </x-details.parts.info-card>

        <!-- Address Information Card -->
        <x-details.parts.info-card title="Address Information" editId="edit-address-info-btn">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-details.parts.info-field label="Country" :value="$user_details['country']" :span="$info['span'] ?? 1" />
                <x-details.parts.info-field label="Province" :value="$user_details['province']" :span="$info['span'] ?? 1" />
                <x-details.parts.info-field label="City/Municipality" :value="$user_details['city_municipality']"
                    :span="$info['span'] ?? 1" />
                <x-details.parts.info-field label="Postal Code" :value="$user_details['postal_code']"
                    :span="$info['span'] ?? 1" />
                <x-details.parts.info-field label="Current Address" :value="$user_details['curr_address']"
                    :span="$info['span'] ?? 1" />
            </div>
        </x-details.parts.info-card>

        <!-- RFID Tags Card -->
        <x-details.parts.info-card title="Gate Pass Stickers" :editButton="false">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-gray-700">Assigned Tags</h3>
                <button id="add-rfid-btn" class="text-green-600 hover:text-green-700 text-sm font-medium">
                    <i class="fas fa-plus mr-1"></i> Add Tag
                </button>
            </div>
            <x-details.parts.data-table :headers="$rfidHeaders" :rows="$gate_pass_details" />
        </x-details.parts.info-card>
        <x-details.parts.access-history title="Access History" :accessRecords="$accessRecords" />
        <!-- Vehicles Card -->
        <x-details.parts.info-card title="Registered Vehicles" :editButton="false">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-gray-700">Vehicles</h3>
                <button id="add-vehicle-btn" class="text-green-600 hover:text-green-700 text-sm font-medium">
                    <i class="fas fa-plus mr-1"></i> Add Vehicle
                </button>
            </div>
            <x-details.parts.data-table :headers="$vehicleHeaders" :rows="$vehicle_details" />
        </x-details.parts.info-card>
    </x-slot>

    <x-slot name="sideContent">
        <!-- Status Card -->
        <x-details.parts.status-card status="{{ $applicant_details['status']['label'] }}"
            statusClass="{{ $user_details['status_badge'] }}"
            applicationDate="{{ $applicant_details['submitted_date'] }}" approvalDate="Jan 15, 2023"
            approvedBy="Admin User" />

        <!-- Documents Card -->
        <x-details.parts.documents-card :documents="$documents" />

        <!-- Activity Log Card (excluded from print) -->
        <div class="no-print">
            <x-details.parts.activity-log :activities="$activities" />
        </div>
    </x-slot>
</x-details.layout>
