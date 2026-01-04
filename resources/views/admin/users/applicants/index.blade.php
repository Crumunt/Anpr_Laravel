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
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">
                                Applicant Management
                            </h2>
                            {{-- Show role indicator for context --}}
                            @if(auth()->user()->hasRole('admin_viewer'))
                                <p class="text-sm text-gray-500 mt-1">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                        View Only Mode
                                    </span>
                                </p>
                            @elseif(auth()->user()->hasRole('encoder'))
                                <p class="text-sm text-gray-500 mt-1">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                        Encoder Mode
                                    </span>
                                </p>
                            @endif
                        </div>
                        <div class="flex space-x-2">
                            {{-- Add Applicant button - Only for users who can create applicants --}}
                            @can('create applicants')
                                @livewire('admin.modal.modal')
                            @endcan
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

    @push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('toast', (data) => {
                const params = Array.isArray(data) ? data[0] : data;
                const type = params.type || 'info';
                const message = params.message || '';

                const icons = {
                    success: 'success',
                    error: 'error',
                    warning: 'warning',
                    info: 'info'
                };

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: icons[type] || 'info',
                        title: type.charAt(0).toUpperCase() + type.slice(1),
                        text: message,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                } else {
                    alert(message);
                }
            });
        });
    </script>
    @endpush
@endsection
