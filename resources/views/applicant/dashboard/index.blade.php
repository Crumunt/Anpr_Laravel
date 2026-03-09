@extends('layouts.applicant')

@section('title', 'Dashboard')

@section('content')
    <!-- Welcome Banner -->
    <div class="relative overflow-hidden rounded-2xl bg-[#1a5c1f] p-6 sm:p-8 mb-6">
        <!-- Decorative -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
        <div class="absolute bottom-0 left-1/3 w-48 h-48 bg-white/[0.03] rounded-full translate-y-1/2"></div>

        <div class="relative flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <p class="text-green-300/70 text-sm font-medium mb-1">{{ now()->format('l, F j, Y') }}</p>
                <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight mb-1.5">
                    Welcome back, {{ $user->details?->first_name ?? 'User' }}!
                </h1>
                <p class="text-green-200/60 text-sm sm:text-base max-w-lg">
                    Here's a quick overview of your vehicle registrations and gate pass status.
                </p>
            </div>
            <button onclick="Livewire.dispatch('openAddVehicleModal')"
                    class="inline-flex items-center gap-2 px-5 py-3 bg-yellow-400 text-green-900 text-sm font-bold rounded-xl hover:bg-yellow-300 transition-all duration-200 focus-ring shadow-lg flex-shrink-0">
                <i class="fas fa-plus text-xs"></i>
                <span>Register Vehicle</span>
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Total Vehicles -->
        <div class="bg-white rounded-xl border border-gray-100 p-5 hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute top-0 left-0 right-0 h-1 bg-blue-500 rounded-t-xl"></div>
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fas fa-car text-blue-600 text-base"></i>
                </div>
                <span class="text-3xl font-extrabold text-gray-900 tabular-nums">{{ $stats['total_vehicles'] }}</span>
            </div>
            <p class="text-sm text-gray-500 font-medium">Total Vehicles</p>
        </div>

        <!-- Active Vehicles -->
        <div class="bg-white rounded-xl border border-gray-100 p-5 hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute top-0 left-0 right-0 h-1 bg-green-500 rounded-t-xl"></div>
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 bg-green-50 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fas fa-circle-check text-green-600 text-base"></i>
                </div>
                <span class="text-3xl font-extrabold text-green-700 tabular-nums">{{ $stats['active_vehicles'] }}</span>
            </div>
            <p class="text-sm text-gray-500 font-medium">Active</p>
        </div>

        <!-- Pending -->
        <div class="bg-white rounded-xl border border-gray-100 p-5 hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute top-0 left-0 right-0 h-1 bg-amber-500 rounded-t-xl"></div>
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 bg-amber-50 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fas fa-hourglass-half text-amber-600 text-base"></i>
                </div>
                <span class="text-3xl font-extrabold text-amber-600 tabular-nums">{{ $stats['pending_vehicles'] }}</span>
            </div>
            <p class="text-sm text-gray-500 font-medium">Pending</p>
        </div>

        <!-- Gate Passes -->
        <div class="bg-white rounded-xl border border-gray-100 p-5 hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute top-0 left-0 right-0 h-1 bg-purple-500 rounded-t-xl"></div>
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 bg-purple-50 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fas fa-id-card text-purple-600 text-base"></i>
                </div>
                <span class="text-3xl font-extrabold text-purple-700 tabular-nums">{{ $stats['active_gate_passes'] }}</span>
            </div>
            <p class="text-sm text-gray-500 font-medium">Gate Passes</p>
        </div>
    </div>

    <!-- Alert Banners -->
    @if(($stats['rejected_documents'] ?? 0) > 0)
    <div class="mb-6">
        <a href="{{ route('applicant.notifications') }}" class="flex items-center gap-4 bg-red-50 border border-red-200 rounded-2xl p-4 sm:p-5 hover:bg-red-100/60 transition-all duration-200 group" role="alert">
            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                <i class="fas fa-file-circle-exclamation text-red-600 text-xl"></i>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <h4 class="text-red-800 font-bold text-base">Action Required</h4>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-200 text-red-800">
                        {{ $stats['rejected_documents'] }} document(s)
                    </span>
                </div>
                <p class="text-red-700 text-sm mt-1">
                    You have rejected documents that need to be reviewed and resubmitted.
                </p>
            </div>
            <i class="fas fa-arrow-right text-red-400 group-hover:translate-x-1 transition-transform hidden sm:block"></i>
        </a>
    </div>
    @endif

    @if(($stats['expiring_soon'] ?? 0) > 0 || ($stats['expired'] ?? 0) > 0)
    <div class="mb-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
        @if(($stats['expiring_soon'] ?? 0) > 0)
        <div class="flex items-center gap-4 bg-amber-50 border border-amber-200 rounded-2xl p-4 sm:p-5" role="alert">
            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fas fa-triangle-exclamation text-amber-600 text-xl"></i>
            </div>
            <div class="flex-1 min-w-0">
                <h4 class="text-amber-800 font-bold">Expiring Soon</h4>
                <p class="text-amber-700 text-sm mt-0.5">
                    <strong>{{ $stats['expiring_soon'] }}</strong> gate pass(es) expiring within {{ config('anpr.gate_pass.renewal_warning_days', 90) }} days.
                </p>
            </div>
        </div>
        @endif
        @if(($stats['expired'] ?? 0) > 0)
        <div class="flex items-center gap-4 bg-red-50 border border-red-200 rounded-2xl p-4 sm:p-5" role="alert">
            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fas fa-circle-xmark text-red-600 text-xl"></i>
            </div>
            <div class="flex-1 min-w-0">
                <h4 class="text-red-800 font-bold">Expired</h4>
                <p class="text-red-700 text-sm mt-0.5">
                    <strong>{{ $stats['expired'] }}</strong> expired gate pass(es). Please renew to continue access.
                </p>
            </div>
        </div>
        @endif
    </div>
    @endif

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <!-- Recent Vehicles -->
        <div class="xl:col-span-2">
            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                <!-- Section Header -->
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-gray-900">My Vehicles</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Recent registrations</p>
                    </div>
                    <a href="{{ route('applicant.vehicles') }}" class="text-sm font-semibold text-green-700 hover:text-green-800 flex items-center gap-1.5 transition-colors">
                        View all <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>

                @if($recentVehicles->count() > 0)
                    <div class="divide-y divide-gray-100">
                        @foreach($recentVehicles as $vehicle)
                            <a href="{{ route('applicant.vehicles.show', $vehicle['id']) }}"
                               class="block px-6 py-5 hover:bg-gray-50/60 transition-colors group">
                                <!-- Vehicle Info Row -->
                                <div class="flex items-center justify-between gap-4 mb-4">
                                    <div class="flex items-center gap-3.5 min-w-0 flex-1">
                                        <div class="w-11 h-11 bg-gray-50 rounded-xl flex items-center justify-center border border-gray-100 flex-shrink-0 group-hover:border-green-200 group-hover:bg-green-50 transition-colors">
                                            <i class="fas fa-car text-gray-400 group-hover:text-green-600 transition-colors"></i>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center gap-2">
                                                <p class="font-bold text-gray-900 font-mono text-base group-hover:text-green-800 transition-colors">
                                                    {{ $vehicle['license_plate'] }}
                                                </p>
                                                @if($vehicle['gate_pass'])
                                                    <span class="text-xs font-medium text-green-700 bg-green-50 px-2 py-0.5 rounded-md border border-green-100">
                                                        <i class="fas fa-id-badge text-[10px] mr-0.5"></i>{{ $vehicle['gate_pass'] }}
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-sm text-gray-400 mt-0.5 truncate">
                                                {{ $vehicle['make_model'] }}
                                                @if($vehicle['year'])
                                                    &middot; {{ $vehicle['year'] }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        @if($vehicle['can_renew'] ?? false)
                                            <button type="button"
                                                onclick="event.preventDefault(); event.stopPropagation(); Livewire.dispatch('openRenewGatePassModal', { vehicleId: '{{ $vehicle['id'] }}' })"
                                                class="text-xs font-semibold text-cyan-700 bg-cyan-50 border border-cyan-200 px-2.5 py-1 rounded-lg hover:bg-cyan-100 transition-colors">
                                                <i class="fas fa-sync mr-1 text-[10px]"></i>Renew
                                            </button>
                                        @endif
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold
                                            @if(strtolower($vehicle['status']) === 'active' || strtolower($vehicle['status']) === 'approved')
                                                bg-green-50 text-green-700 border border-green-200
                                            @elseif(str_contains(strtolower($vehicle['status']), 'pending'))
                                                bg-amber-50 text-amber-700 border border-amber-200
                                            @elseif(strtolower($vehicle['status']) === 'inactive' || strtolower($vehicle['status']) === 'blacklisted' || strtolower($vehicle['status']) === 'rejected')
                                                bg-red-50 text-red-700 border border-red-200
                                            @else
                                                bg-gray-50 text-gray-700 border border-gray-200
                                            @endif">
                                            {{ $vehicle['status'] }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Status Stepper -->
                                @php
                                    $statusCode = strtolower($vehicle['status_code'] ?? 'pending');
                                    $hasGatePass = !empty($vehicle['gate_pass']);
                                    $isRejected = in_array($statusCode, ['blacklisted', 'decommissioned']);
                                    // Define step states: completed, current, upcoming, rejected
                                    $steps = [
                                        ['label' => 'Registered', 'icon' => 'fa-file-circle-check'],
                                        ['label' => 'Verification', 'icon' => 'fa-magnifying-glass'],
                                        ['label' => 'Approved', 'icon' => 'fa-circle-check'],
                                        ['label' => 'Gate Pass', 'icon' => 'fa-id-card'],
                                    ];
                                    // Determine which step is active
                                    if ($isRejected) {
                                        $stepStates = ['completed', 'completed', 'rejected', 'disabled'];
                                        $steps[2]['label'] = ucfirst($statusCode);
                                    } elseif ($statusCode === 'pending_verification' || $statusCode === 'pending') {
                                        $stepStates = ['completed', 'current', 'upcoming', 'upcoming'];
                                    } elseif ($statusCode === 'inactive') {
                                        $stepStates = ['completed', 'completed', 'warning', $hasGatePass ? 'completed' : 'upcoming'];
                                    } elseif ($statusCode === 'active' && $hasGatePass) {
                                        $stepStates = ['completed', 'completed', 'completed', 'completed'];
                                    } elseif ($statusCode === 'active') {
                                        $stepStates = ['completed', 'completed', 'completed', 'current'];
                                    } else {
                                        $stepStates = ['completed', 'upcoming', 'upcoming', 'upcoming'];
                                    }
                                @endphp
                                <div class="flex items-center gap-0 mt-1">
                                    @foreach($steps as $i => $step)
                                        @php $state = $stepStates[$i]; @endphp
                                        <div class="flex items-center {{ $i < count($steps) - 1 ? 'flex-1' : '' }}">
                                            <div class="flex flex-col items-center">
                                                <div class="w-7 h-7 rounded-full flex items-center justify-center text-[11px] font-bold
                                                    @if($state === 'completed') bg-green-100 text-green-600
                                                    @elseif($state === 'current') bg-amber-100 text-amber-600 ring-2 ring-amber-300 ring-offset-1
                                                    @elseif($state === 'rejected') bg-red-100 text-red-600
                                                    @elseif($state === 'warning') bg-yellow-100 text-yellow-600
                                                    @else bg-gray-100 text-gray-400
                                                    @endif">
                                                    @if($state === 'completed')
                                                        <i class="fas fa-check text-[10px]"></i>
                                                    @elseif($state === 'rejected')
                                                        <i class="fas fa-xmark text-[10px]"></i>
                                                    @elseif($state === 'current')
                                                        <i class="fas {{ $step['icon'] }} text-[10px]"></i>
                                                    @elseif($state === 'warning')
                                                        <i class="fas fa-exclamation text-[10px]"></i>
                                                    @else
                                                        <i class="fas {{ $step['icon'] }} text-[10px]"></i>
                                                    @endif
                                                </div>
                                                <span class="text-[10px] font-medium mt-1
                                                    @if($state === 'completed') text-green-600
                                                    @elseif($state === 'current') text-amber-600
                                                    @elseif($state === 'rejected') text-red-600
                                                    @elseif($state === 'warning') text-yellow-600
                                                    @else text-gray-400
                                                    @endif">
                                                    {{ $step['label'] }}
                                                </span>
                                            </div>
                                            @if($i < count($steps) - 1)
                                                <div class="flex-1 h-0.5 mx-2 mt-[-12px] rounded-full
                                                    @if($stepStates[$i] === 'completed' && $stepStates[$i + 1] !== 'upcoming')
                                                        bg-green-300
                                                    @elseif($stepStates[$i + 1] === 'rejected')
                                                        bg-red-200
                                                    @else
                                                        bg-gray-200
                                                    @endif">
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="p-10 text-center">
                        <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4 border-2 border-dashed border-gray-200">
                            <i class="fas fa-car text-gray-300 text-2xl"></i>
                        </div>
                        <h4 class="text-base font-bold text-gray-900 mb-1">No vehicles yet</h4>
                        <p class="text-gray-400 mb-5 text-sm max-w-sm mx-auto">
                            Register your first vehicle to get started with your gate pass application.
                        </p>
                        <button type="button" onclick="Livewire.dispatch('openAddVehicleModal')"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-700 text-white text-sm font-semibold rounded-xl hover:bg-green-800 transition-colors focus-ring">
                            <i class="fas fa-plus text-xs"></i>
                            Register Vehicle
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar Column -->
        <div class="space-y-5">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-base font-bold text-gray-900">Quick Actions</h3>
                </div>
                <div class="p-3 space-y-1">
                    <button type="button" onclick="Livewire.dispatch('openAddVehicleModal')"
                        class="w-full flex items-center gap-3 p-3 rounded-lg hover:bg-green-50 transition-colors group text-left border border-transparent hover:border-green-200">
                        <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center group-hover:bg-green-100 transition-colors flex-shrink-0">
                            <i class="fas fa-plus text-green-600 text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-900 text-sm group-hover:text-green-800 transition-colors">Register New Vehicle</p>
                            <p class="text-xs text-gray-400 mt-0.5">Apply for a new gate pass</p>
                        </div>
                        <i class="fas fa-chevron-right text-xs text-gray-300 group-hover:text-green-500 transition-colors"></i>
                    </button>

                    <a href="{{ route('applicant.vehicles') }}"
                       class="flex items-center gap-3 p-3 rounded-lg hover:bg-blue-50 transition-colors group border border-transparent hover:border-blue-200">
                        <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center group-hover:bg-blue-100 transition-colors flex-shrink-0">
                            <i class="fas fa-list-ul text-blue-600 text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-900 text-sm group-hover:text-blue-800 transition-colors">View All Vehicles</p>
                            <p class="text-xs text-gray-400 mt-0.5">Manage registrations</p>
                        </div>
                        <i class="fas fa-chevron-right text-xs text-gray-300 group-hover:text-blue-500 transition-colors"></i>
                    </a>

                    <a href="{{ route('applicant.notifications') }}"
                       class="flex items-center gap-3 p-3 rounded-lg hover:bg-purple-50 transition-colors group border border-transparent hover:border-purple-200">
                        <div class="w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center group-hover:bg-purple-100 transition-colors flex-shrink-0">
                            <i class="fas fa-bell text-purple-600 text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-900 text-sm group-hover:text-purple-800 transition-colors">Notifications</p>
                            <p class="text-xs text-gray-400 mt-0.5">Check updates & alerts</p>
                        </div>
                        <i class="fas fa-chevron-right text-xs text-gray-300 group-hover:text-purple-500 transition-colors"></i>
                    </a>
                </div>
            </div>

            <!-- Account Info -->
            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-base font-bold text-gray-900">Account Info</h3>
                </div>
                <div class="p-4 space-y-1">
                    <div class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="w-9 h-9 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-envelope text-gray-400 text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Email</p>
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $user->email }}</p>
                        </div>
                    </div>

                    @if($user->details?->phone_number)
                    <div class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="w-9 h-9 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-phone text-gray-400 text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Phone</p>
                            <p class="text-sm font-medium text-gray-900">{{ $user->details->phone_number }}</p>
                        </div>
                    </div>
                    @endif

                    @if($user->details?->clsu_id)
                    <div class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="w-9 h-9 bg-green-50 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-id-badge text-green-600 text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide">CLSU ID</p>
                            <p class="text-sm font-bold text-green-700">{{ $user->details->clsu_id }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="w-9 h-9 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-calendar text-gray-400 text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Member Since</p>
                            <p class="text-sm font-medium text-gray-900">{{ $user->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
