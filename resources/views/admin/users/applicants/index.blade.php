@extends('layouts.app-layout')
@section('main-content')

    <div class="flex-1 md:ml-64 p-6 pt-24">
        <!-- Dashboard Cards -->
        @livewire('admin.applicant.applicant-stat-cards')

        <!-- Users Management Container -->
        <div
            class="w-full bg-white rounded-xl shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-md">

            <div class="p-6">
                <div class="w-full space-y-6">
                    <!-- Header -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <h2 class="text-2xl font-bold text-gray-800">
                            Applicant Management
                        </h2>
                        <div class="flex space-x-2">
                            @livewire('admin.modal.modal')
                        </div>
                    </div>

                    <!-- Search Filter -->
                    @livewire('table.partials.search-filter')

                    <!-- Table container -->
                    <div class="w-full space-y-6" id="table_wrapper">
                        <!-- All Users Tab Content -->
                        <livewire:table.data-table caption="All Applicants" type="applicant" />
                    </div>

                    <!-- Pagination -->
                    <div id="pagination">
                        @livewire('table.partials.pagination', [
                                'targetTable' => 'applications_table'
                            ], key('applications_table'))
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-footer.footer></x-footer.footer>
@endsection
