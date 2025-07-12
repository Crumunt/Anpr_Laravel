@extends('layouts.app-layout')
@section('main-content')
<body style="background-color: #f9fafb;">
<div class="flex-1 md:ml-64 p-6 pt-24">
<!-- Dashboard Cards -->
        <div class="w-full bg-white rounded-xl shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-md">
            <div dir="ltr" class="w-full">
            </div>
            <div class="p-6">
                <div class="w-full space-y-6">
                    <!-- Header -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <h2 class="text-2xl font-bold text-gray-800">Applicant Management</h2>
                        <div class="flex space-x-2">
                            <x-dashboard.buttons
                                :icon="false"
                                class="bg-emerald-600 hover:bg-emerald-700"
                                data-open-modal="applicant-modal">
                                <span slot="icon" class="mr-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <path d="M8 12h8"></path>
                                        <path d="M12 8v8"></path>
                                    </svg>
                                </span>
                                <span>Add Applicant</span>
                            </x-dashboard.buttons>
                        </div>
                    </div>
                    <x-details.modal id="applicant-modal" type="applicant" action="add" />
                    
                    <!-- Moved search filter inside the space-y-6 container -->
                    <x-dashboard.search-filter :showType="'applicant'" />
                    
                    <!-- Removed mt-6 since space-y-6 will handle spacing -->
                    <div class="w-full space-y-6">
                        <x-dashboard.application-table
                            :type="'applicant'"
                            context="user_applicant"
                            :rows="$users"
                            caption="Applicants list for Summer 2024 Program" />
                        <x-dashboard.pagination></x-dashboard.pagination>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-footer.footer></x-footer.footer>
</body>
@endsection