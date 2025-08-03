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
    <x-details.parts.profile-header title="{{ $user_details['full_name'] }}"
      initials="{{ $user_details['name_initials'] }}" status="{{ $user_details['status_name'] }}"
      statusClass="{{ $user_details['status_badge'] }}" user_id="{{ $user_details['clsu_id'] }}" :isActive="true" />
  </x-slot>

  <x-slot name="mainContent">
    <!-- Personal Information Card -->
    <x-details.parts.info-card title="Personal Information" editId="edit-personal-info-btn">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <x-details.parts.info-field label="Full Name" :value="$user_details['full_name']" :span="$info['span'] ?? 1" />
        <x-details.parts.info-field label="Email Address" :value="$user_details['email_address']" :span="$info['span'] ?? 1" />
        <x-details.parts.info-field label="Phone Number" :value="$user_details['phone_number']" :span="$info['span'] ?? 1" />
        <x-details.parts.info-field label="License Number" :value="$user_details['license_number']" :span="$info['span'] ?? 1" />
      </div>
    </x-details.parts.info-card>

    <!-- Address Information Card -->
    <x-details.parts.info-card title="Address Information" editId="edit-address-info-btn">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <x-details.parts.info-field label="Country" :value="$user_details['country']" :span="$info['span'] ?? 1" />
        <x-details.parts.info-field label="Province" :value="$user_details['province']" :span="$info['span'] ?? 1" />
        <x-details.parts.info-field label="City/Municipality" :value="$user_details['city_municipality']"
          :span="$info['span'] ?? 1" />
        <x-details.parts.info-field label="Postal Code" :value="$user_details['postal_code']" :span="$info['span'] ?? 1" />
        <x-details.parts.info-field label="Current Address" :value="$user_details['curr_address']" :span="$info['span'] ?? 1" />
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
    <x-details.parts.status-card status="{{ $user_details['status_name'] }}"
      statusClass="{{ $user_details['status_badge'] }}" applicationDate="{{ $user_details['submitted_date'] }}"
      approvalDate="Jan 15, 2023" approvedBy="Admin User" />

    <!-- Documents Card -->
    <x-details.parts.documents-card :documents="$documents" />

    <!-- Activity Log Card -->
    <x-details.parts.activity-log :activities="$activities" />
  </x-slot>
</x-details.layout>