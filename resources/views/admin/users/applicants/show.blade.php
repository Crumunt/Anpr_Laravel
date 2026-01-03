<x-details.layout title="Applicant Details" type="applicant">
    @php

        $applicationHeaders = ['ID', 'Applicant Type', 'Status', 'Date Applied'];
        $rfidRows = [];

        $vehicleHeaders = ['Plate Number', 'Make & Model', 'Year', 'Registration Date'];

        $vehicleDataHeaders = ['Vehicle Info', 'Plate Number', 'Gate Pass Number', 'Expiry Date'];

        $documents = [];
        $accessRecords = [];
        $activities = [];
      @endphp

    <x-slot name="breadcrumb">
        <x-details.parts.breadcrumb :items="$breadcrumbs" />
    </x-slot>

    <x-slot name="header">
        <!-- Profile Header - Livewire component for reactive updates -->
        <livewire:admin.applicant.details.profile-header
            :userId="$applicant_details['id']"
            :title="$personal_information['full_name']"
            :initials="$personal_information['name_initials']"
            :status="$personal_information['active_status']"
            :user_id="$personal_information['clsu_id']"
            :email="$personal_information['email'] ?? ''"
            :isActive="$personal_information['active_status'] === 'Active'" />

        <!-- Hidden component to handle applicant account actions -->
        <livewire:admin.applicant.applicant-account-manager :userId="$applicant_details['id']" />
    </x-slot>

    <x-slot name="mainContent">
        <!-- Personal Information Card -->
        <livewire:admin.applicant.details.info-card modelName="User" cardTitle="Personal Information" :canEdit="true" :userId="$applicant_details['id']"/>

        <!-- Address Information Card -->
        <livewire:admin.applicant.details.info-card modelName="User" cardTitle="Address Information" :canEdit="true" :userId="$applicant_details['id']" context="address" />


        <livewire:admin.applicant.details.info-table :tableId="'application-table'" wire:key="'application-table'" cardTitle="Applications Submitted" :canCreate="true" :headers="$applicationHeaders" :rows="$application_details" :userId="$applicant_details['id']" />

        <livewire:admin.applicant.details.info-table :tableId="'vehicles-table'" wire:key="'vehicles-table'" cardTitle="Registered Vehicles" :headers="$vehicleDataHeaders" :rows="$vehicle_details" :userId="$applicant_details['id']" type="vehicle"/>

    </x-slot>

    <x-slot name="sideContent">
        <!-- Status Card -->
        {{-- <x-details.parts.status-card status="status"
            statusClass="false"
            applicationDate="{{ $applicant_details['submitted_date'] }}" approvalDate="Jan 15, 2023"
            approvedBy="Admin User" /> --}}

        <!-- Documents Card -->
        <livewire:admin.applicant.details.documents.document-card :user_id="$applicant_details['id']" />

        <!-- Activity Log Card (excluded from print) -->
        <div class="no-print">
            <x-details.parts.activity-log :activities="$activities" />
        </div>
    </x-slot>
</x-details.layout>
