@extends('layouts.applicant')

@section('title', 'My Vehicles')

@section('content')
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <nav class="flex items-center gap-2 text-sm text-gray-500 mb-3" aria-label="Breadcrumb">
                <a href="{{ route('applicant.dashboard') }}" class="hover:text-green-700 transition-colors">Dashboard</a>
                <i class="fas fa-chevron-right text-[10px] text-gray-300"></i>
                <span class="text-gray-900 font-medium">My Vehicles</span>
            </nav>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">My Vehicles</h1>
            <p class="text-gray-500 mt-1 text-sm sm:text-base">Manage your registered vehicles and track gate pass status.</p>
        </div>
        <button type="button" onclick="Livewire.dispatch('openAddVehicleModal')"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#1a5c1f] text-white text-sm font-semibold rounded-xl shadow-sm hover:bg-green-800 transition-colors focus-ring flex-shrink-0">
            <i class="fas fa-plus text-xs"></i>
            <span>Register New Vehicle</span>
        </button>
    </div>

    @if($vehicles->count() > 0)
        <!-- Vehicles Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            @foreach($vehiclesData as $vehicle)
                <div class="bg-white rounded-xl border border-gray-100 overflow-hidden card-hover group">
                    <!-- Vehicle Header -->
                    <div class="p-5 pb-3 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3.5 min-w-0 flex-1">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 transition-colors
                                @if(strtolower($vehicle['status']) === 'active' || strtolower($vehicle['status']) === 'approved')
                                    bg-green-50 group-hover:bg-green-100
                                @elseif(str_contains(strtolower($vehicle['status']), 'pending'))
                                    bg-amber-50 group-hover:bg-amber-100
                                @else
                                    bg-gray-50 group-hover:bg-gray-100
                                @endif">
                                @switch($vehicle['vehicle_type'])
                                    @case('motorcycle')
                                        <i class="fas fa-motorcycle text-lg
                                            @if(strtolower($vehicle['status']) === 'active' || strtolower($vehicle['status']) === 'approved') text-green-600
                                            @elseif(str_contains(strtolower($vehicle['status']), 'pending')) text-amber-600
                                            @else text-gray-500 @endif"></i>
                                        @break
                                    @case('truck')
                                        <i class="fas fa-truck text-lg
                                            @if(strtolower($vehicle['status']) === 'active' || strtolower($vehicle['status']) === 'approved') text-green-600
                                            @elseif(str_contains(strtolower($vehicle['status']), 'pending')) text-amber-600
                                            @else text-gray-500 @endif"></i>
                                        @break
                                    @default
                                        <i class="fas fa-car text-lg
                                            @if(strtolower($vehicle['status']) === 'active' || strtolower($vehicle['status']) === 'approved') text-green-600
                                            @elseif(str_contains(strtolower($vehicle['status']), 'pending')) text-amber-600
                                            @else text-gray-500 @endif"></i>
                                @endswitch
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-bold text-gray-900 font-mono text-lg leading-tight">{{ $vehicle['license_plate'] }}</p>
                                <p class="text-xs text-gray-500 mt-0.5 font-medium">{{ ucfirst($vehicle['vehicle_type'] ?? 'Vehicle') }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold flex-shrink-0
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

                    <!-- Vehicle Details -->
                    <div class="px-5 pb-3">
                        <div class="grid grid-cols-3 gap-2">
                            <div class="flex items-center gap-2 py-2.5 px-3 bg-gray-50/80 rounded-xl text-sm">
                                <i class="fas fa-car-side text-gray-400 text-[11px]"></i>
                                <div class="min-w-0">
                                    <span class="block text-gray-400 text-[10px] font-medium leading-tight">Make / Model</span>
                                    <span class="font-semibold text-gray-900 text-sm truncate block">{{ $vehicle['make'] }} {{ $vehicle['model'] }}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 py-2.5 px-3 bg-gray-50/80 rounded-xl text-sm">
                                <i class="fas fa-calendar text-gray-400 text-[11px]"></i>
                                <div>
                                    <span class="block text-gray-400 text-[10px] font-medium leading-tight">Year</span>
                                    <span class="font-semibold text-gray-900 text-sm">{{ $vehicle['year'] }}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 py-2.5 px-3 bg-gray-50/80 rounded-xl text-sm">
                                <i class="fas fa-clock text-gray-400 text-[11px]"></i>
                                <div>
                                    <span class="block text-gray-400 text-[10px] font-medium leading-tight">Registered</span>
                                    <span class="font-semibold text-gray-900 text-sm">{{ $vehicle['registered_date'] }}</span>
                                </div>
                            </div>
                        </div>
                        @if($vehicle['gate_pass'])
                            <div class="flex items-center justify-center gap-2 mt-2 py-2.5 px-3.5 bg-green-50 rounded-xl text-sm border border-green-100">
                                <i class="fas fa-id-badge text-green-600 text-xs"></i>
                                <span class="text-green-700 font-medium">Gate Pass:</span>
                                <span class="font-bold text-green-700 font-mono">{{ $vehicle['gate_pass'] }}</span>
                            </div>
                        @endif

                        <!-- Status Stepper -->
                        @php
                            $statusCode = strtolower($vehicle['status_code'] ?? 'pending');
                            $hasGatePass = !empty($vehicle['gate_pass']);
                            $isRejected = in_array($statusCode, ['blacklisted', 'decommissioned']);
                            $steps = [
                                ['label' => 'Registered', 'icon' => 'fa-file-circle-check'],
                                ['label' => 'Verification', 'icon' => 'fa-magnifying-glass'],
                                ['label' => 'Approved', 'icon' => 'fa-circle-check'],
                                ['label' => 'Gate Pass', 'icon' => 'fa-id-card'],
                            ];
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
                        <div class="flex items-center gap-0 mt-3 px-1">
                            @foreach($steps as $i => $step)
                                @php $state = $stepStates[$i]; @endphp
                                <div class="flex items-center {{ $i < count($steps) - 1 ? 'flex-1' : '' }}">
                                    <div class="flex flex-col items-center">
                                        <div class="w-7 h-7 rounded-full flex items-center justify-center text-[11px]
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
                    </div>

                    <!-- Action -->
                    <div class="px-5 pb-5">
                        <a href="{{ route('applicant.vehicles.show', $vehicle['id']) }}"
                           class="w-full inline-flex justify-center items-center gap-2 px-4 py-2.5 bg-gray-50 border border-gray-200 text-gray-700 text-sm font-semibold rounded-xl hover:bg-green-50 hover:border-green-200 hover:text-green-700 transition-all duration-200 focus-ring group/btn">
                            <i class="fas fa-eye text-xs"></i>
                            <span>View Details</span>
                            <i class="fas fa-arrow-right text-[10px] ml-auto opacity-0 -translate-x-2 group-hover/btn:opacity-100 group-hover/btn:translate-x-0 transition-all"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($vehicles->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $vehicles->links() }}
        </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl border border-gray-100 p-12 lg:p-16 text-center">
            <div class="w-20 h-20 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-5 border-2 border-dashed border-gray-200">
                <i class="fas fa-car text-gray-300 text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">No vehicles registered</h3>
            <p class="text-gray-500 mb-6 max-w-md mx-auto text-sm leading-relaxed">
                You haven't registered any vehicles yet. Register a vehicle to apply for a gate pass and start accessing CLSU facilities.
            </p>
            <button type="button" onclick="Livewire.dispatch('openAddVehicleModal')"
                class="inline-flex items-center gap-2 px-6 py-3 bg-[#1a5c1f] text-white font-semibold rounded-xl shadow-sm hover:bg-green-800 transition-colors focus-ring">
                <i class="fas fa-plus text-xs"></i>
                <span>Register Your First Vehicle</span>
            </button>
        </div>
    @endif
@endsection
