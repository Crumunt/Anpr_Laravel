@extends('layouts.app-layout')
@section('main-content')
    <div class="flex-1 md:ml-64 p-6 pt-24">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            @foreach ($cardData as $card)
                <x-dashboard.card :title="$card['title']" :totalNumber="$card['totalNumber']" :percent="$card['percent']"
                    :description="$card['description']" :icon="$card['icon']" />
            @endforeach
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
                            <x-table.data :type="'applicant'" context="user_applicant" :rows="$users" caption="Applicants list for Summer 2024 Program" />
                        </div>
                        <div x-show="activeTab === 'Registered Vehicles'" x-transition>
                            <x-table.data :type="'vehicle'" context="vehicles" :rows="$vehicles"
                                caption="Registered Vehicles list" />
                        </div>
                        <div x-show="activeTab === 'Gate Pass Management'" x-transition>
                            <x-table.data :type="'rfid'" context="gate_pass" :rows="$gatePasses" caption="RFID Tags Management list" />
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