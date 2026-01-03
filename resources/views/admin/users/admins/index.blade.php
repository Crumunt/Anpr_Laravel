@extends('layouts.app-layout')
@section('main-content')

    <div class="flex-1 md:ml-64 p-6 pt-24">
        <!-- Dashboard Cards -->
        @livewire('admin.admins.admin-stat-cards')

        <!-- Add Admin Modal -->
        @livewire('admin.admins.add-admin-modal')

        <!-- Edit Admin Modal -->
        @livewire('admin.admins.edit-admin-modal')

        <!-- Admin Management Container -->
        <div
            class="w-full bg-white rounded-xl shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-md">

            <div class="p-6">
                <div class="w-full space-y-6">
                    <!-- Header -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <h2 class="text-2xl font-bold text-gray-800">
                            Admin Management
                        </h2>
                        <div class="flex space-x-2">
                            <!-- Add Admin Button -->
                            <button
                                onclick="Livewire.dispatch('openAddAdminModal')"
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Add Admin
                            </button>
                        </div>
                    </div>

                    <!-- Search Filter -->
                    @livewire('table.partials.search-filter', ['showType' => 'admins'])

                    <!-- Table container -->
                    <div class="w-full space-y-6" id="table_wrapper">
                        <!-- All Admins Tab Content -->
                        <livewire:table.data-table caption="All Administrators" type="admin" />
                    </div>

                    <!-- Pagination -->
                    <div id="pagination">
                        @livewire('table.partials.pagination', [
                                'targetTable' => 'admins_table'
                            ], key('admins_table'))
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
