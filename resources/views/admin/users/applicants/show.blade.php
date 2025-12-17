<x-details.layout title="Applicant Details" type="applicant">
    @php

        $applicationHeaders = ['ID', 'Applicant Type', 'Status', 'Date Applied'];
        $rfidRows = [];

        $vehicleHeaders = ['Plate Number', 'Make & Model', 'Year', 'Registration Date'];

        $vehicleDataHeaders = ['Vehicle Info', 'Plate Number', 'Gate Pass Number', 'Status', 'Expiry Date'];

        $documents = [];
        $accessRecords = [];
        $activities = [];
      @endphp

    <x-slot name="breadcrumb">
        <x-details.parts.breadcrumb :items="$breadcrumbs" />
    </x-slot>

    <x-slot name="header">
        <x-details.parts.profile-header title="{{ $personal_information['full_name'] }}"
            initials="{{ $personal_information['name_initials'] }}"
            status="{{ $personal_information['active_status'] }}"
            statusClass="{{ $personal_information['badge_class'] }}"
            user_id="{{ $personal_information['clsu_id'] }}" :isActive="true" />
    </x-slot>

    <x-slot name="mainContent">
        <!-- Personal Information Card -->
        <livewire:admin.applicant.details.info-card modelName="User" cardTitle="Personal Information" :canEdit="true" :userId="$applicant_details['id']"/>

        <!-- Address Information Card -->
        <livewire:admin.applicant.details.info-card modelName="User" cardTitle="Address Information" :canEdit="true" :userId="$applicant_details['id']" context="address" />


        <livewire:admin.applicant.details.info-table cardTitle="Applications Submitted" :canCreate="true" :headers="$applicationHeaders" :rows="$application_details" />

        <livewire:admin.applicant.details.info-table cardTitle="Registered Vehicles" :headers="$vehicleDataHeaders" :rows="$vehicle_details" />


        <x-details.parts.access-history title="Access History" :accessRecords="$accessRecords" />
    </x-slot>

    <x-slot name="sideContent">
        <!-- Status Card -->
        {{-- <x-details.parts.status-card status="status"
            statusClass="false"
            applicationDate="{{ $applicant_details['submitted_date'] }}" approvalDate="Jan 15, 2023"
            approvedBy="Admin User" /> --}}

        <!-- Documents Card -->
        <x-details.parts.documents-card :applications="$application_documents" />

        <!-- Activity Log Card (excluded from print) -->
        <div class="no-print">
            <x-details.parts.activity-log :activities="$activities" />
        </div>
    </x-slot>
</x-details.layout>
