@extends('layouts.applicant')

@section('title', 'Vehicle Details')

@section('content')
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6" aria-label="Breadcrumb">
        <a href="{{ route('applicant.dashboard') }}" class="hover:text-green-700 transition-colors">Dashboard</a>
        <i class="fas fa-chevron-right text-[10px] text-gray-300"></i>
        <a href="{{ route('applicant.vehicles') }}" class="hover:text-green-700 transition-colors">Vehicles</a>
        <i class="fas fa-chevron-right text-[10px] text-gray-300"></i>
        <span class="text-gray-900 font-medium font-mono">{{ $vehicle->plate_number }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Vehicle Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Vehicle Header Card -->
            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                <div class="p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-xl flex items-center justify-center flex-shrink-0
                            @if(strtolower($vehicle->status?->code ?? '') === 'active') bg-green-50
                            @elseif(strtolower($vehicle->status?->code ?? '') === 'pending_verification') bg-amber-50
                            @else bg-gray-50 @endif">
                            <i class="fas fa-{{ ($vehicle->type ?? 'car') === 'motorcycle' ? 'motorcycle' : (($vehicle->type ?? 'car') === 'truck' ? 'truck' : 'car') }} text-2xl
                                @if(strtolower($vehicle->status?->code ?? '') === 'active') text-green-600
                                @elseif(strtolower($vehicle->status?->code ?? '') === 'pending_verification') text-amber-600
                                @else text-gray-500 @endif"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 font-mono">{{ $vehicle->plate_number }}</h1>
                            <p class="text-gray-500 mt-0.5">{{ $vehicle->make }} {{ $vehicle->model }} ({{ $vehicle->year }})</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-semibold
                        @if(strtolower($vehicle->status?->code ?? '') === 'active')
                            bg-green-50 text-green-700 border border-green-200
                        @elseif(strtolower($vehicle->status?->code ?? '') === 'pending_verification')
                            bg-amber-50 text-amber-700 border border-amber-200
                        @elseif(in_array(strtolower($vehicle->status?->code ?? ''), ['blacklisted', 'inactive', 'decommissioned']))
                            bg-red-50 text-red-700 border border-red-200
                        @else
                            bg-gray-50 text-gray-700 border border-gray-200
                        @endif">
                        {{ $vehicle->status?->status_name ?? 'Pending' }}
                    </span>
                </div>

                <!-- Status Stepper -->
                @php
                    $statusCode = strtolower($vehicle->status?->code ?? 'pending');
                    $hasGatePass = !empty($vehicle->assigned_gate_pass);
                    $isRejected = in_array($statusCode, ['blacklisted', 'decommissioned']);
                    $steps = [
                        ['label' => 'Registered', 'icon' => 'fa-file-circle-check', 'desc' => 'Vehicle submitted'],
                        ['label' => 'Verification', 'icon' => 'fa-magnifying-glass', 'desc' => 'Under review'],
                        ['label' => 'Approved', 'icon' => 'fa-circle-check', 'desc' => 'Admin approved'],
                        ['label' => 'Gate Pass', 'icon' => 'fa-id-card', 'desc' => 'Pass assigned'],
                    ];
                    if ($isRejected) {
                        $stepStates = ['completed', 'completed', 'rejected', 'disabled'];
                        $steps[2]['label'] = ucfirst($statusCode);
                        $steps[2]['desc'] = 'Vehicle ' . $statusCode;
                    } elseif ($statusCode === 'pending_verification' || $statusCode === 'pending') {
                        $stepStates = ['completed', 'current', 'upcoming', 'upcoming'];
                    } elseif ($statusCode === 'inactive') {
                        $stepStates = ['completed', 'completed', 'warning', $hasGatePass ? 'completed' : 'upcoming'];
                        $steps[2]['desc'] = 'Currently inactive';
                    } elseif ($statusCode === 'active' && $hasGatePass) {
                        $stepStates = ['completed', 'completed', 'completed', 'completed'];
                    } elseif ($statusCode === 'active') {
                        $stepStates = ['completed', 'completed', 'completed', 'current'];
                    } else {
                        $stepStates = ['completed', 'upcoming', 'upcoming', 'upcoming'];
                    }
                @endphp
                <div class="px-6 pb-6">
                    <div class="bg-gray-50/60 rounded-xl p-5 border border-gray-100">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-4">Registration Progress</p>
                        <div class="flex items-start">
                            @foreach($steps as $i => $step)
                                @php $state = $stepStates[$i]; @endphp
                                <div class="flex items-start {{ $i < count($steps) - 1 ? 'flex-1' : '' }}">
                                    <div class="flex flex-col items-center text-center">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold
                                            @if($state === 'completed') bg-green-100 text-green-600
                                            @elseif($state === 'current') bg-amber-100 text-amber-600 ring-4 ring-amber-100/50
                                            @elseif($state === 'rejected') bg-red-100 text-red-600
                                            @elseif($state === 'warning') bg-yellow-100 text-yellow-600
                                            @else bg-gray-100 text-gray-400
                                            @endif">
                                            @if($state === 'completed')
                                                <i class="fas fa-check"></i>
                                            @elseif($state === 'rejected')
                                                <i class="fas fa-xmark"></i>
                                            @elseif($state === 'current')
                                                <i class="fas {{ $step['icon'] }}"></i>
                                            @elseif($state === 'warning')
                                                <i class="fas fa-exclamation"></i>
                                            @else
                                                <i class="fas {{ $step['icon'] }}"></i>
                                            @endif
                                        </div>
                                        <span class="text-xs font-semibold mt-2
                                            @if($state === 'completed') text-green-700
                                            @elseif($state === 'current') text-amber-700
                                            @elseif($state === 'rejected') text-red-700
                                            @elseif($state === 'warning') text-yellow-700
                                            @else text-gray-400
                                            @endif">
                                            {{ $step['label'] }}
                                        </span>
                                        <span class="text-[10px] text-gray-400 mt-0.5 hidden sm:block">{{ $step['desc'] }}</span>
                                    </div>
                                    @if($i < count($steps) - 1)
                                        <div class="flex-1 h-0.5 mx-3 mt-5 rounded-full
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
                </div>
            </div>

            <!-- Vehicle Information Card -->
            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-info-circle text-gray-400 text-sm"></i>
                        Vehicle Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="flex items-center gap-3 p-4 bg-gray-50/80 rounded-xl border-l-[3px] border-blue-400">
                            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center border border-gray-100 flex-shrink-0">
                                <i class="fas fa-industry text-blue-500 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Make</p>
                                <p class="font-semibold text-gray-900">{{ $vehicle->make }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-4 bg-gray-50/80 rounded-xl border-l-[3px] border-green-400">
                            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center border border-gray-100 flex-shrink-0">
                                <i class="fas fa-car-side text-green-500 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Model</p>
                                <p class="font-semibold text-gray-900">{{ $vehicle->model }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-4 bg-gray-50/80 rounded-xl border-l-[3px] border-purple-400">
                            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center border border-gray-100 flex-shrink-0">
                                <i class="fas fa-calendar text-purple-500 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Year</p>
                                <p class="font-semibold text-gray-900">{{ $vehicle->year }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-4 bg-gray-50/80 rounded-xl border-l-[3px] border-amber-400">
                            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center border border-gray-100 flex-shrink-0">
                                <i class="fas fa-palette text-amber-500 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Color</p>
                                <p class="font-semibold text-gray-900">{{ $vehicle->color ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-4 bg-gray-50/80 rounded-xl border-l-[3px] border-cyan-400">
                            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center border border-gray-100 flex-shrink-0">
                                <i class="fas fa-car text-cyan-500 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Type</p>
                                <p class="font-semibold text-gray-900">{{ ucfirst($vehicle->type ?? 'Car') }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-4 bg-gray-50/80 rounded-xl border-l-[3px] border-gray-400">
                            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center border border-gray-100 flex-shrink-0">
                                <i class="fas fa-clock text-gray-500 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Registered</p>
                                <p class="font-semibold text-gray-900">{{ $vehicle->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Gate Pass Card -->
            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-base font-bold text-gray-900">Gate Pass</h3>
                </div>
                <div class="p-5">
                    @if($vehicle->assigned_gate_pass)
                        <div class="text-center">
                            <div class="w-16 h-16 bg-green-50 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-green-100">
                                <i class="fas fa-id-badge text-green-600 text-2xl"></i>
                            </div>
                            <p class="text-2xl font-extrabold text-green-700 font-mono">{{ $vehicle->assigned_gate_pass }}</p>
                            <p class="text-sm text-gray-500 mt-1 font-medium">Active Gate Pass</p>

                            @if($vehicle->is_renewal)
                                <span class="inline-flex items-center mt-2 px-2.5 py-0.5 rounded-lg text-xs font-semibold bg-cyan-50 text-cyan-700 border border-cyan-100">
                                    <i class="fas fa-sync mr-1 text-[10px]"></i> Renewal
                                </span>
                            @endif
                        </div>

                        @if($vehicle->expires_at)
                            <div class="mt-5 pt-5 border-t border-gray-100 space-y-3">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500">Expires</span>
                                    <span class="font-semibold text-gray-900">{{ $vehicle->expires_at->format('M d, Y') }}</span>
                                </div>
                                <div class="inline-flex items-center w-full justify-center px-3 py-2 rounded-xl text-sm font-semibold {{ $vehicle->expiration_status['class'] }}">
                                    @if($vehicle->isExpired())
                                        <i class="fas fa-exclamation-circle mr-1.5"></i>Expired
                                    @elseif($vehicle->isExpiringSoon())
                                        <i class="fas fa-clock mr-1.5"></i>{{ $vehicle->time_until_expiration }}
                                    @else
                                        <i class="fas fa-check-circle mr-1.5"></i>{{ $vehicle->time_until_expiration }}
                                    @endif
                                </div>
                                @if($vehicle->approved_at)
                                    <p class="text-xs text-gray-500 text-center">
                                        Approved: {{ $vehicle->approved_at->format('M d, Y') }}
                                    </p>
                                @endif
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4 border-2 border-dashed border-gray-200">
                                <i class="fas fa-id-badge text-gray-300 text-2xl"></i>
                            </div>
                            <p class="text-gray-500 font-medium">No gate pass assigned</p>
                            @if(strtolower($vehicle->status?->code ?? '') === 'pending_verification')
                                <p class="text-sm text-amber-600 mt-2 flex items-center justify-center gap-1.5">
                                    <i class="fas fa-hourglass-half text-xs"></i> Awaiting verification
                                </p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-base font-bold text-gray-900">Actions</h3>
                </div>
                <div class="p-4 space-y-2">
                    @if($vehicle->canRenew())
                        <button type="button"
                            onclick="Livewire.dispatch('openRenewGatePassModal', { vehicleId: '{{ $vehicle->id }}' })"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-cyan-600 text-white text-sm font-semibold rounded-xl hover:bg-cyan-700 shadow-sm transition-colors focus-ring">
                            <i class="fas fa-sync text-xs"></i> Renew Gate Pass
                        </button>
                    @endif
                    <a href="{{ route('applicant.vehicles') }}"
                       class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-50 border border-gray-200 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-100 transition-colors focus-ring">
                        <i class="fas fa-arrow-left text-xs"></i> Back to Vehicles
                    </a>
                </div>
            </div>

            <!-- Registration Timeline -->
            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-base font-bold text-gray-900">Timeline</h3>
                </div>
                <div class="p-5">
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="fas fa-file-circle-check text-green-600 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Registered</p>
                                <p class="text-xs text-gray-400">{{ $vehicle->created_at->format('M d, Y \a\t g:i A') }}</p>
                            </div>
                        </div>
                        @if($vehicle->approved_at)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="fas fa-circle-check text-green-600 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Approved</p>
                                <p class="text-xs text-gray-400">{{ $vehicle->approved_at->format('M d, Y \a\t g:i A') }}</p>
                            </div>
                        </div>
                        @endif
                        @if($vehicle->expires_at)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 {{ $vehicle->isExpired() ? 'bg-red-100' : 'bg-blue-100' }} rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="fas fa-calendar {{ $vehicle->isExpired() ? 'text-red-600' : 'text-blue-600' }} text-xs"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $vehicle->isExpired() ? 'Expired' : 'Expires' }}</p>
                                <p class="text-xs text-gray-400">{{ $vehicle->expires_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
