@extends('layouts.applicant')

@section('title', 'Dashboard')

@section('content')
    <!-- Add Vehicle Modal Component -->
    @livewire('applicant.add-vehicle-modal')

    <!-- Renew Gate Pass Modal Component -->
    @livewire('applicant.renew-gate-pass-modal')

    <!-- Welcome Header with improved typography and spacing -->
    <div class="mb-8 lg:mb-10">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 tracking-tight mb-2">
                    Welcome back, <span class="text-emerald-600">{{ $user->details?->first_name ?? 'User' }}</span>!
                </h1>
                <p class="text-base md:text-lg text-gray-600 leading-relaxed">
                    Here's an overview of your vehicle registrations and gate passes.
                </p>
            </div>
            <div class="hidden lg:flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-100 to-emerald-200 shadow-sm">
                <i class="fas fa-car-side text-emerald-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Enhanced Stats Cards with better visual hierarchy -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8 lg:mb-10">
        <!-- Total Vehicles -->
        <div class="group relative bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-lg hover:border-blue-300 transition-all duration-300 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Vehicles</p>
                    <p class="text-3xl md:text-4xl font-bold text-gray-900 leading-none">{{ $stats['total_vehicles'] }}</p>
                    <p class="text-xs text-gray-500 mt-2">All registered</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-car text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Active Vehicles -->
        <div class="group relative bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-lg hover:border-emerald-300 transition-all duration-300 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-emerald-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-600 mb-1">Active Vehicles</p>
                    <p class="text-3xl md:text-4xl font-bold text-emerald-600 leading-none">{{ $stats['active_vehicles'] }}</p>
                    <p class="text-xs text-gray-500 mt-2">Approved & active</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-emerald-100 to-emerald-200 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-check-circle text-emerald-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Pending Vehicles -->
        <div class="group relative bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-lg hover:border-amber-300 transition-all duration-300 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-amber-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-600 mb-1">Pending Approval</p>
                    <p class="text-3xl md:text-4xl font-bold text-amber-600 leading-none">{{ $stats['pending_vehicles'] }}</p>
                    <p class="text-xs text-gray-500 mt-2">Under review</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-amber-100 to-amber-200 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-clock text-amber-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Active Gate Passes -->
        <div class="group relative bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-lg hover:border-purple-300 transition-all duration-300 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-purple-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-600 mb-1">Active Gate Passes</p>
                    <p class="text-3xl md:text-4xl font-bold text-purple-600 leading-none">{{ $stats['active_gate_passes'] }}</p>
                    <p class="text-xs text-gray-500 mt-2">Currently active</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-purple-100 to-purple-200 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-id-card text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Rejected Documents Alert -->
    @if(($stats['rejected_documents'] ?? 0) > 0)
    <div class="mb-8 lg:mb-10">
        <a href="{{ route('applicant.notifications') }}" class="block bg-red-50 border border-red-200 rounded-xl p-4 hover:bg-red-100 transition-colors">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-file-exclamation text-red-600 text-xl"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <h4 class="text-red-800 font-semibold">Action Required: Document Rejected</h4>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-red-200 text-red-800">
                            {{ $stats['rejected_documents'] }}
                        </span>
                    </div>
                    <p class="text-red-700 text-sm mt-1">
                        You have <strong>{{ $stats['rejected_documents'] }}</strong> rejected document(s) that require your attention. Please review the feedback and resubmit.
                    </p>
                    <p class="text-red-600 text-xs mt-2 flex items-center gap-1">
                        <i class="fas fa-arrow-right"></i>
                        Click here to view and resubmit your documents
                    </p>
                </div>
            </div>
        </a>
    </div>
    @endif

    <!-- Expiration Alerts Section -->
    @if(($stats['expiring_soon'] ?? 0) > 0 || ($stats['expired'] ?? 0) > 0)
    <div class="mb-8 lg:mb-10 grid grid-cols-1 md:grid-cols-2 gap-4">
        @if(($stats['expiring_soon'] ?? 0) > 0)
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-start gap-4">
            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-amber-600 text-xl"></i>
            </div>
            <div class="flex-1 min-w-0">
                <h4 class="text-amber-800 font-semibold">Gate Pass Expiring Soon</h4>
                <p class="text-amber-700 text-sm mt-1">
                    You have <strong>{{ $stats['expiring_soon'] }}</strong> gate pass(es) expiring within the next {{ config('anpr.gate_pass.renewal_warning_days', 90) }} days. Consider renewing now to avoid interruption.
                </p>
            </div>
        </div>
        @endif
        @if(($stats['expired'] ?? 0) > 0)
        <div class="bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-4">
            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fas fa-times-circle text-red-600 text-xl"></i>
            </div>
            <div class="flex-1 min-w-0">
                <h4 class="text-red-800 font-semibold">Expired Gate Pass</h4>
                <p class="text-red-700 text-sm mt-1">
                    You have <strong>{{ $stats['expired'] }}</strong> expired gate pass(es). Please renew to continue using the gate pass.
                </p>
            </div>
        </div>
        @endif
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
        <!-- Recent Vehicles with enhanced design -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">My Vehicles</h3>
                    <p class="text-sm text-gray-500 mt-1">Recent vehicle registrations</p>
                </div>
            </div>

            @if($recentVehicles->count() > 0)
                <div class="divide-y divide-gray-100">
                    @foreach($recentVehicles as $vehicle)
                        <a href="{{ route('applicant.vehicles.show', $vehicle['id']) }}"
                           class="block p-5 hover:bg-gradient-to-r hover:from-emerald-50/50 hover:to-transparent transition-all duration-200 group">
                            <div class="flex items-center justify-between gap-4">
                                <div class="flex items-center gap-4 min-w-0 flex-1">
                                    <div class="w-14 h-14 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform duration-200 flex-shrink-0">
                                        <i class="fas fa-car text-blue-600 text-lg"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="font-bold text-gray-900 text-lg font-mono mb-1 group-hover:text-emerald-600 transition-colors">
                                            {{ $vehicle['license_plate'] }}
                                        </p>
                                        <p class="text-sm text-gray-600 truncate">
                                            {{ $vehicle['make_model'] }}
                                            @if($vehicle['year'])
                                                <span class="text-gray-400">•</span> {{ $vehicle['year'] }}
                                            @endif
                                        </p>
                                        @if($vehicle['gate_pass'])
                                            <div class="flex items-center gap-1.5 mt-2">
                                                <i class="fas fa-id-badge text-xs text-emerald-600"></i>
                                                <span class="text-xs font-medium text-emerald-600">{{ $vehicle['gate_pass'] }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex flex-col items-end gap-2 flex-shrink-0">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                        @if(strtolower($vehicle['status']) === 'active' || strtolower($vehicle['status']) === 'approved')
                                            bg-emerald-100 text-emerald-800 border border-emerald-200
                                        @elseif(str_contains(strtolower($vehicle['status']), 'pending'))
                                            bg-amber-100 text-amber-800 border border-amber-200
                                        @elseif(strtolower($vehicle['status']) === 'inactive' || strtolower($vehicle['status']) === 'blacklisted' || strtolower($vehicle['status']) === 'rejected')
                                            bg-red-100 text-red-800 border border-red-200
                                        @else
                                            bg-gray-100 text-gray-800 border border-gray-200
                                        @endif">
                                        {{ $vehicle['status'] }}
                                    </span>

                                    @if(isset($vehicle['expires_at']))
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $vehicle['expiration_status']['class'] ?? 'bg-gray-100 text-gray-800' }}">
                                            @if($vehicle['is_expired'] ?? false)
                                                <i class="fas fa-exclamation-circle mr-1"></i>Expired
                                            @elseif($vehicle['is_expiring_soon'] ?? false)
                                                <i class="fas fa-clock mr-1"></i>{{ $vehicle['time_until_expiration'] ?? $vehicle['days_until_expiration'] . ' days left' }}
                                            @else
                                                <i class="fas fa-calendar mr-1"></i>{{ $vehicle['time_until_expiration'] ?? $vehicle['expires_at'] }}
                                            @endif
                                        </span>
                                    @endif

                                    @if($vehicle['can_renew'] ?? false)
                                        <button
                                            type="button"
                                            onclick="event.preventDefault(); event.stopPropagation(); Livewire.dispatch('openRenewGatePassModal', { vehicleId: '{{ $vehicle['id'] }}' })"
                                            class="inline-flex items-center px-2.5 py-1 text-xs font-semibold text-cyan-700 bg-cyan-100 border border-cyan-200 rounded-lg hover:bg-cyan-200 transition-colors">
                                            <i class="fas fa-sync mr-1.5"></i>
                                            Renew
                                        </button>
                                    @endif

                                    @if($vehicle['is_renewal'] ?? false)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-cyan-100 text-cyan-800">
                                            <i class="fas fa-sync mr-1"></i>Renewal
                                        </span>
                                    @endif

                                    <span class="text-xs text-gray-500">{{ $vehicle['registered_date'] }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <!-- Enhanced Empty State -->
                <div class="p-12 lg:p-16 text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-sm">
                        <i class="fas fa-car text-gray-400 text-3xl"></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-2">No vehicles registered</h4>
                    <p class="text-gray-600 mb-6 max-w-sm mx-auto leading-relaxed">
                        You haven't registered any vehicles yet. Register your first vehicle to get started with gate pass applications.
                    </p>
                    <button
                        type="button"
                        onclick="Livewire.dispatch('openAddVehicleModal')"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white text-sm font-semibold rounded-xl hover:from-emerald-700 hover:to-emerald-800 shadow-md hover:shadow-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transform hover:scale-105">
                        <i class="fas fa-plus"></i>
                        <span>Register Your First Vehicle</span>
                    </button>
                </div>
            @endif
        </div>

        <!-- Quick Actions & Info with enhanced design -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                    <h3 class="text-lg font-bold text-gray-900">Quick Actions</h3>
                    <p class="text-sm text-gray-500 mt-1">Common tasks and shortcuts</p>
                </div>
                <div class="p-4 space-y-2">
                    <button
                        type="button"
                        onclick="Livewire.dispatch('openAddVehicleModal')"
                        class="w-full flex items-center gap-3 p-4 bg-gradient-to-r from-emerald-50 to-emerald-100/50 rounded-xl hover:from-emerald-100 hover:to-emerald-200 transition-all duration-200 group text-left border border-emerald-200/50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-100 to-emerald-200 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform duration-200">
                            <i class="fas fa-plus text-emerald-600 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-900 group-hover:text-emerald-700 transition-colors">Register New Vehicle</p>
                            <p class="text-xs text-gray-600 mt-0.5">Apply for a new gate pass</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-emerald-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <a href="{{ route('applicant.vehicles') }}"
                       class="flex items-center gap-3 p-4 bg-gradient-to-r from-blue-50 to-blue-100/50 rounded-xl hover:from-blue-100 hover:to-blue-200 transition-all duration-200 group border border-blue-200/50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform duration-200">
                            <i class="fas fa-list text-blue-600 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-900 group-hover:text-blue-700 transition-colors">View All Vehicles</p>
                            <p class="text-xs text-gray-600 mt-0.5">Manage your registered vehicles</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Account Info with enhanced design -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                    <h3 class="text-lg font-bold text-gray-900">Account Information</h3>
                    <p class="text-sm text-gray-500 mt-1">Your account details</p>
                </div>
                <div class="p-4 space-y-3">
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                        <div class="w-11 h-11 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center shadow-sm">
                            <i class="fas fa-envelope text-gray-600"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-0.5">Email</p>
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $user->email }}</p>
                        </div>
                    </div>

                    @if($user->details?->phone_number)
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                            <div class="w-11 h-11 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center shadow-sm">
                                <i class="fas fa-phone text-gray-600"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-0.5">Phone Number</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $user->details->phone_number }}</p>
                            </div>
                        </div>
                    @endif

                    @if($user->details?->clsu_id)
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                            <div class="w-11 h-11 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center shadow-sm">
                                <i class="fas fa-id-badge text-gray-600"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-0.5">CLSU ID</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $user->details->clsu_id }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                        <div class="w-11 h-11 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center shadow-sm">
                            <i class="fas fa-calendar text-gray-600"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-0.5">Member Since</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $user->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
