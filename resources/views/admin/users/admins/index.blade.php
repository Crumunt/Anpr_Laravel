@extends('layouts.app-layout')
@section('main-content')

    <body style="background-color: #f9fafb;">
        <div class="flex-1 md:ml-64 p-6 pt-24">
            <!-- Dashboard Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <x-dashboard.card title="Total Admins" totalNumber="42" percent="+2" description="Total administrators"
                    icon="users" />
                <x-dashboard.card title="Active Admins" totalNumber="36" percent="+1" description="Currently active admins"
                    icon="check" />
                <x-dashboard.card title="Inactive Admins" totalNumber="6" percent="-3"
                    description="Currently inactive admins" icon="ban" />
                <x-dashboard.card title="Super Admins" color="blue" totalNumber="3" percent="0"
                    description="System super administrators" icon="shield" />
            </div>

            <!-- Admin tabs container -->
            <div x-data="{ activeTab: 'All Admins' }" @tab-changed="activeTab = $event.detail"
                class="w-full bg-white rounded-xl shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-md">
                <div dir="ltr" class="w-full">
                    <x-dashboard.navigation-tabs :tabs="['All Admins', 'Active', 'Inactive']" />
                </div>
                <div class="p-6">
                    <div class="w-full space-y-6">
                        <!-- Header -->
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <h2 class="text-2xl font-bold text-gray-800">
                                <span>Admin Management</span>
                            </h2>
                            <div class="flex space-x-2">
                                <!-- Admin Button -->
                                <div>
                                    <x-dashboard.buttons :icon="false" class="bg-emerald-600 hover:bg-emerald-700"
                                        data-open-modal="admin-modal">
                                        <span slot="icon" class="mr-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <path d="M8 12h8"></path>
                                                <path d="M12 8v8"></path>
                                            </svg>
                                        </span>
                                        <span>Add Admin</span>
                                    </x-dashboard.buttons>
                                </div>
                            </div>
                        </div>

                        <!-- Admin Modal -->
                        <x-modal.modal id="admin-modal" type="admin" action="add" />

                        <x-dashboard.search-filter showType="admins" />
                        <!-- Table container -->
                        <div class="w-full space-y-6 mt-6">
                            <!-- All Admins Tab Content -->
                            <div x-show="activeTab === 'All Admins'" x-transition>
                                <x-table.data :type="'admin'" context="user_admin" :rows="$users['all']"
                                    caption="All Administrators" />
                            </div>

                            <!-- Active Admins Tab Content -->
                            <div x-show="activeTab === 'Active'" x-transition>
                                <x-table.data :type="'admin'" context="user_admin" tab="active" :rows="$users['active']"
                                    caption="Active Administrators" />
                            </div>

                            <!-- Inactive Admins Tab Content -->
                            <div x-show="activeTab === 'Inactive'" x-transition>
                                <x-table.data :type="'admin'" context="user_admin" tab="inactive" :rows="$users['inactive']"
                                    caption="Inactive Administrators" />
                            </div>

                            <div id="pagination">
                                <x-pagination :pagination="$users['paginate']" />
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <x-footer.footer></x-footer.footer>
    </body>
    <script>
        document.querySelector('searchFilter').setAttribute('showType', 'admins')
    </script>
@endsection