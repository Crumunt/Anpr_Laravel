@extends('layouts.app-layout')
@section('main-content')

    <div class="flex-1 md:ml-64 p-6 pt-24">
        <!-- Archived Applicants Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Archived Applicants</h1>
                    <p class="text-sm text-gray-500 mt-1">Manage archived applicant accounts. You can restore or permanently delete them.</p>
                </div>
                <a href="{{ route('admin.applicant') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Applicants
                </a>
            </div>
        </div>

        <!-- Archived Count Card -->
        <div class="mb-6">
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-center">
                <div class="p-3 bg-amber-100 rounded-full mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-amber-700">Archived Accounts</p>
                    <p class="text-2xl font-bold text-amber-800" id="archived-count">
                        @livewire('admin.applicant.archived-count')
                    </p>
                </div>
            </div>
        </div>

        <!-- Archived Applicants Table Container -->
        <div class="w-full bg-white rounded-xl shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-md">
            <div class="p-6">
                <div class="w-full space-y-6">
                    <!-- Header -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <h2 class="text-xl font-bold text-gray-800">
                                Archived Accounts
                            </h2>
                            <p class="text-sm text-gray-500 mt-1">
                                Select accounts to restore or permanently delete
                            </p>
                        </div>
                    </div>

                    <!-- Search Filter -->
                    @livewire('table.partials.search-filter', ['targetTable' => 'archived_applications_table'])

                    <!-- Table container -->
                    <div class="w-full space-y-6" id="table_wrapper">
                        <livewire:table.data-table caption="Archived Applicants" type="archived_applicant" />
                    </div>

                    <!-- Pagination -->
                    <div id="pagination">
                        @livewire('table.partials.pagination', [
                                'targetTable' => 'archived_applications_table'
                            ], key('archived_applications_table'))
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
