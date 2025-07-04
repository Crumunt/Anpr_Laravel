@extends('layouts.app-layout')
@section('main-content')
    <div class="flex-1 md:ml-64 p-6 pt-24">
        <!-- Dashboard Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <x-dashboard.card title="Total Applicants" totalNumber="1,111" percent="+2"
                description="Total registered applicants" icon="users" />
            <x-dashboard.card title="Active Gate Pass Stickers" totalNumber="894" percent="+2"
                description="Currently Active RFID Tags" icon="rfid" />
            <x-dashboard.card title="Registered Vehicles" totalNumber="115" percent="+5"
                description="Total registered vehicles" icon="car" />
            <x-dashboard.card title="Pending Approvals" color="red" totalNumber="1,111" percent="-12"
                description="Total registered applicants" icon="approval" />
        </div>
        <!-- Use ONE Alpine.js data context for the entire section -->
        <div x-data="{ activeTab: 'Applicants' }"
            class="w-full bg-white rounded-xl shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-md">
            <div dir="ltr" class="w-full">
                <x-dashboard.navigation-tabs :tabs="['Applicants', 'Registered Vehicles', 'Gate Pass Management']" />
            </div>
            <div class="p-6">
                <div class="w-full space-y-6">
                    <!-- Header -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <h2 class="text-2xl font-bold text-gray-800">
                            <span
                                x-text="activeTab === 'Applicants' ? 'Applicant Management' : (activeTab === 'Registered Vehicles' ? 'Vehicle Management' : 'Gate Pass Management')"></span>
                        </h2>
                        <div class="flex space-x-2">
                            <!-- Applicant Button -->
                            <div x-show="activeTab === 'Applicants'">
                                <x-dashboard.buttons :icon="false" class="bg-emerald-600 hover:bg-emerald-700"
                                    data-open-modal="applicant-modal">
                                    <span slot="icon" class="mr-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <path d="M8 12h8"></path>
                                            <path d="M12 8v8"></path>
                                        </svg>
                                    </span>
                                    <span>Add Applicant</span>
                                </x-dashboard.buttons>
                            </div>
                        </div>
                    </div>
                    <x-details.modal id="applicant-modal" type="applicant" action="add" />
                    <x-details.modal id="vehicle-modal" type="vehicle" action="add" />
                    <x-details.modal id="rfid-modal" type="rfid" action="add" />

                    <!-- Move the search filter inside the padded content div -->
                    <x-dashboard.search-filter />

                    <!-- Remove the mt-6 class since space-y-6 will add consistent spacing -->
                    <div class="w-full space-y-6">
                        <div x-show="activeTab === 'Applicants'" x-transition>
                            <x-dashboard.application-table :type="'applicant'" context="user_applicant" :rows="[
            ['name' => 'John Doe', 'email' => 'john.doe@example.com', 'phone' => '(555) 123-4567', 'status' => ['label' => 'Pending', 'class' => 'bg-yellow-100/80 text-yellow-800 hover:bg-yellow-200/60'], 'submitted_date' => '2023-05-15', 'rfid_tags' => 2, 'vehicles' => 1],
            ['name' => 'Jane Smith', 'email' => 'jane.smith@example.com', 'phone' => '(555) 765-4321', 'status' => ['label' => 'Approved', 'class' => 'bg-green-100/80 text-green-800 hover:bg-green-200/60'], 'submitted_date' => '2023-05-16', 'rfid_tags' => 1, 'vehicles' => 2]
        ]"
                                caption="Applicants list for Summer 2024 Program" />
                        </div>
                        <div x-show="activeTab === 'Registered Vehicles'" x-transition>
                            <x-dashboard.application-table :type="'vehicle'" context="vehicles" :rows="[
            ['vehicle' => 'Toyota Camry', 'owner' => 'John Doe', 'registration_date' => '2023-05-15'],
            ['vehicle' => 'Honda Civic', 'owner' => 'Jane Smith', 'registration_date' => '2023-05-16']
        ]"
                                caption="Registered Vehicles list" />
                        </div>
                        <div x-show="activeTab === 'Gate Pass Management'" x-transition>
                            <x-dashboard.application-table :type="'rfid'" context="gate_pass" :rows="[
            ['gate_pass' => 'GP12345', 'status' => 'Active', 'assigned_to' => 'John Doe'],
            ['gate_pass' => 'GP67890', 'status' => 'Inactive', 'assigned_to' => 'Jane Smith'],
            ['gate_pass' => 'GP67890', 'status' => 'Pending', 'assigned_to' => 'Jane Smith'],
            ['gate_pass' => 'GP67890', 'status' => 'Red', 'assigned_to' => 'Jane Smith'],
            ['gate_pass' => 'GP67890', 'status' => 'Default', 'assigned_to' => 'Jane Smith'],
        ]" caption="RFID Tags Management list" />
                        </div>
                        <x-dashboard.pagination></x-dashboard.pagination>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-footer.footer></x-footer.footer>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchFilter = document.querySelector('x-dashboard\\.search-filter');
        if (searchFilter) {
            searchFilter.setAttribute('showType', 'applicants');
        }
    });
</script>