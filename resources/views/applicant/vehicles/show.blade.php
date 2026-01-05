@extends('layouts.applicant')

@section('title', 'Vehicle Details')

@section('content')
    <!-- Breadcrumb -->
    <nav class="mb-6" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2 text-sm text-gray-500">
            <li><a href="{{ route('applicant.dashboard') }}" class="hover:text-emerald-600">Dashboard</a></li>
            <li><i class="fas fa-chevron-right text-xs"></i></li>
            <li><a href="{{ route('applicant.vehicles') }}" class="hover:text-emerald-600">Vehicles</a></li>
            <li><i class="fas fa-chevron-right text-xs"></i></li>
            <li class="text-gray-900 font-medium">{{ $vehicle->license_plate }}</li>
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
                            <h1 class="text-2xl font-bold text-gray-900 font-mono">{{ $vehicle->license_plate }}</h1>
                            <p class="text-gray-500">{{ $vehicle->vehicle_make }} {{ $vehicle->vehicle_model }} ({{ $vehicle->vehicle_year }})</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        {{ $vehicle->status_badge === 'Approved' ? 'bg-emerald-100 text-emerald-800' : '' }}
                        {{ $vehicle->status_badge === 'Pending' ? 'bg-amber-100 text-amber-800' : '' }}
                        {{ $vehicle->status_badge === 'Rejected' ? 'bg-red-100 text-red-800' : '' }}">
                        {{ $vehicle->status_badge }}
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
                                <p class="font-medium text-gray-900">{{ $vehicle->vehicle_make }}</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                                <i class="fas fa-car-side text-gray-600"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Model</p>
                                <p class="font-medium text-gray-900">{{ $vehicle->vehicle_model }}</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                                <i class="fas fa-calendar text-gray-600"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Year</p>
                                <p class="font-medium text-gray-900">{{ $vehicle->vehicle_year }}</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                                <i class="fas fa-palette text-gray-600"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Color</p>
                                <p class="font-medium text-gray-900">{{ $vehicle->vehicle_color ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                                <i class="fas fa-car text-gray-600"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Vehicle Type</p>
                                <p class="font-medium text-gray-900">{{ ucfirst($vehicle->vehicle_type ?? 'Car') }}</p>
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
                        </div>
                    @else
                        <div class="text-center">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-id-badge text-gray-400 text-3xl"></i>
                            </div>
                            <p class="text-gray-500">No gate pass assigned</p>
                            @if($vehicle->status_badge === 'Pending')
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
                    <a href="{{ route('applicant.vehicles') }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Vehicles
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
