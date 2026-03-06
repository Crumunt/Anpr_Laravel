@extends('layouts.applicant')

@section('title', 'Vehicle Details')

@section('content')
    <!-- Renew Gate Pass Modal Component -->
    @livewire('applicant.renew-gate-pass-modal')

    <!-- Breadcrumb -->
    <nav class="mb-6" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2 text-sm text-gray-500">
            <li><a href="{{ route('applicant.dashboard') }}" class="hover:text-emerald-600">Dashboard</a></li>
            <li><i class="fas fa-chevron-right text-xs"></i></li>
            <li><a href="{{ route('applicant.vehicles') }}" class="hover:text-emerald-600">Vehicles</a></li>
            <li><i class="fas fa-chevron-right text-xs"></i></li>
            <li class="text-gray-900 font-medium">{{ $vehicle->plate_number }}</li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Vehicle Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Vehicle Header Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-car text-blue-600 text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 font-mono">{{ $vehicle->plate_number }}</h1>
                            <p class="text-gray-500">{{ $vehicle->make }} {{ $vehicle->model }} ({{ $vehicle->year }})</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $vehicle->status_badge['class'] }}">
                        {{ $vehicle->status_badge['label'] ?? 'Pending' }}
                    </span>
                </div>

                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Vehicle Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                                <i class="fas fa-industry text-gray-600"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Make</p>
                                <p class="font-medium text-gray-900">{{ $vehicle->make }}</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                                <i class="fas fa-car-side text-gray-600"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Model</p>
                                <p class="font-medium text-gray-900">{{ $vehicle->model }}</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                                <i class="fas fa-calendar text-gray-600"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Year</p>
                                <p class="font-medium text-gray-900">{{ $vehicle->year }}</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                                <i class="fas fa-palette text-gray-600"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Color</p>
                                <p class="font-medium text-gray-900">{{ $vehicle->color ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                                <i class="fas fa-car text-gray-600"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Vehicle Type</p>
                                <p class="font-medium text-gray-900">{{ ucfirst($vehicle->type ?? 'Car') }}</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                                <i class="fas fa-clock text-gray-600"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Registered On</p>
                                <p class="font-medium text-gray-900">{{ $vehicle->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Gate Pass Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800">Gate Pass</h3>
                </div>
                <div class="p-4">
                    @if($vehicle->assigned_gate_pass)
                        <div class="text-center">
                            <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-id-badge text-emerald-600 text-3xl"></i>
                            </div>
                            <p class="text-2xl font-bold text-emerald-600 font-mono">{{ $vehicle->assigned_gate_pass }}</p>
                            <p class="text-sm text-gray-500 mt-1">Active Gate Pass</p>

                            @if($vehicle->is_renewal)
                                <p class="inline-flex items-center mt-2 px-2 py-0.5 rounded text-xs font-medium bg-cyan-100 text-cyan-800">
                                    <i class="fas fa-sync mr-1"></i> Renewal Application
                                </p>
                            @endif
                        </div>

                        <!-- Expiration Info -->
                        @if($vehicle->expires_at)
                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm text-gray-500">Expires</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $vehicle->expires_at->format('M d, Y') }}</span>
                                </div>

                                <div class="inline-flex items-center w-full justify-center px-3 py-1.5 rounded-lg text-sm font-medium {{ $vehicle->expiration_status['class'] }}">
                                    @if($vehicle->isExpired())
                                        <i class="fas fa-exclamation-circle mr-1.5"></i>
                                        Expired
                                    @elseif($vehicle->isExpiringSoon())
                                        <i class="fas fa-clock mr-1.5"></i>
                                        {{ $vehicle->time_until_expiration }}
                                    @else
                                        <i class="fas fa-check-circle mr-1.5"></i>
                                        {{ $vehicle->time_until_expiration }}
                                    @endif
                                </div>

                                @if($vehicle->approved_at)
                                    <p class="text-xs text-gray-500 text-center mt-2">
                                        Approved: {{ $vehicle->approved_at->format('M d, Y') }}
                                    </p>
                                @endif
                            </div>
                        @endif
                    @else
                        <div class="text-center">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-id-badge text-gray-400 text-3xl"></i>
                            </div>
                            <p class="text-gray-500">No gate pass assigned</p>
                            @if(($vehicle->status_badge['label'] ?? 'Pending') === 'Pending')
                                <p class="text-sm text-amber-600 mt-2">
                                    <i class="fas fa-clock mr-1"></i> Awaiting approval
                                </p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800">Actions</h3>
                </div>
                <div class="p-4 space-y-3">
                    @if($vehicle->canRenew())
                        <button
                            type="button"
                            onclick="Livewire.dispatch('openRenewGatePassModal', { vehicleId: '{{ $vehicle->id }}' })"
                            class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-cyan-600 text-white text-sm font-semibold rounded-lg hover:bg-cyan-700 transition-colors">
                            <i class="fas fa-sync mr-2"></i> Renew Gate Pass
                        </button>
                    @endif
                    <a href="{{ route('applicant.vehicles') }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Vehicles
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
