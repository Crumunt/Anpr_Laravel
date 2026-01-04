@extends('layouts.app-layout')
@section('main-content')

    <body style="background-color: #f9fafb;">
        <div class="flex-1 md:ml-64 p-6 pt-24">
            <div
                class="w-full bg-white rounded-xl shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-md">
                <div class="p-6">
                    <div class="w-full space-y-6">
                        <!-- Header -->
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-800">Gate Pass Management</h2>
                                {{-- Role indicator --}}
                                @if(auth()->user()->hasRole('admin_viewer'))
                                    <p class="text-sm text-gray-500 mt-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                            View Only Mode
                                        </span>
                                    </p>
                                @elseif(auth()->user()->hasRole('security'))
                                    <p class="text-sm text-gray-500 mt-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Security Access
                                        </span>
                                    </p>
                                @endif
                            </div>
                            <div class="flex space-x-2">
                                {{-- Add Gate Pass button - Only for users who can create --}}
                                @can('create gate passes')
                                <x-dashboard.buttons :icon="false" class="bg-emerald-600 hover:bg-emerald-700">
                                    <span slot="icon" class="mr-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <path d="M8 12h8"></path>
                                            <path d="M12 8v8"></path>
                                        </svg>
                                    </span>
                                    <span>Add Gate Pass</span>
                                </x-dashboard.buttons>
                                @endcan
                            </div>
                        </div>

                        <!-- Moved search filter inside the space-y-6 container -->
                        <x-dashboard.search-filter :showType="'gatePass'" />

                        <!-- Removed mt-6 since space-y-6 will handle spacing -->
                        <div class="w-full space-y-6">
                            <x-table.data type="gate_pass" context="gate_pass" :rows="$gatePasses['rows']"
                                caption="RFID Tags Management list" />

                        </div>
                        <div id="pagination">
                            <x-pagination :pagination="$gatePasses['vehicles']" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <x-footer.footer></x-footer.footer>
    </body>
@endsection
