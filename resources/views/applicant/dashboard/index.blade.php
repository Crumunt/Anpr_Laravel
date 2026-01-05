@extends('layouts.applicant')

@section('title', 'Dashboard')

@section('content')
    <!-- Welcome Header -->
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
            Welcome back, {{ $user->details?->first_name ?? 'User' }}!
        </h1>
        <p class="text-gray-500 mt-1">Here's an overview of your vehicle registrations and gate passes.</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <!-- Total Vehicles -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Vehicles</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['total_vehicles'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-car text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Active Vehicles -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Active Vehicles</p>
                    <p class="text-3xl font-bold text-emerald-600 mt-1">{{ $stats['active_vehicles'] }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-emerald-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Pending Vehicles -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Pending Approval</p>
                    <p class="text-3xl font-bold text-amber-600 mt-1">{{ $stats['pending_vehicles'] }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-amber-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Active Gate Passes -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Active Gate Passes</p>
                    <p class="text-3xl font-bold text-purple-600 mt-1">{{ $stats['active_gate_passes'] }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-id-card text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Vehicles -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">My Vehicles</h3>
                <a href="{{ route('applicant.vehicles') }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            @if($recentVehicles->count() > 0)
                <div class="divide-y divide-gray-100">
                    @foreach($recentVehicles as $vehicle)
                        <div class="p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-car text-gray-500"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $vehicle['license_plate'] }}</p>
                                        <p class="text-sm text-gray-500">{{ $vehicle['make_model'] }} ({{ $vehicle['year'] }})</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if(strtolower($vehicle['status']) === 'approved') bg-emerald-100 text-emerald-800
                                        @elseif(strtolower($vehicle['status']) === 'pending') bg-amber-100 text-amber-800
                                        @elseif(strtolower($vehicle['status']) === 'rejected') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ $vehicle['status'] }}
                                    </span>
                                    @if($vehicle['gate_pass'])
                                        <p class="text-xs text-gray-500 mt-1">
                                            <i class="fas fa-id-badge mr-1"></i>{{ $vehicle['gate_pass'] }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-8 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-car text-gray-400 text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-medium text-gray-900 mb-2">No vehicles registered</h4>
                    <p class="text-gray-500 mb-4">You haven't registered any vehicles yet.</p>
                    <a href="{{ route('gate-pass.gate-pass-applicant-form') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i> Register Vehicle
                    </a>
                </div>
            @endif
        </div>

        <!-- Quick Actions & Info -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800">Quick Actions</h3>
                </div>
                <div class="p-4 space-y-3">
                    <a href="{{ route('gate-pass.gate-pass-applicant-form') }}" class="flex items-center p-3 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition-colors group">
                        <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-emerald-200 transition-colors">
                            <i class="fas fa-plus text-emerald-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Register New Vehicle</p>
                            <p class="text-xs text-gray-500">Apply for a new gate pass</p>
                        </div>
                    </a>

                    <a href="{{ route('applicant.vehicles') }}" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors group">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-200 transition-colors">
                            <i class="fas fa-list text-blue-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">View All Vehicles</p>
                            <p class="text-xs text-gray-500">Manage your registered vehicles</p>
                        </div>
                    </a>

                    <a href="{{ route('applicant.profile') }}" class="flex items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors group">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-purple-200 transition-colors">
                            <i class="fas fa-user-edit text-purple-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Update Profile</p>
                            <p class="text-xs text-gray-500">Edit your personal information</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Account Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800">Account Information</h3>
                </div>
                <div class="p-4 space-y-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-envelope text-gray-500"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Email</p>
                            <p class="text-sm font-medium text-gray-900">{{ $user->email }}</p>
                        </div>
                    </div>

                    @if($user->details?->phone_number)
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-phone text-gray-500"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Phone Number</p>
                                <p class="text-sm font-medium text-gray-900">{{ $user->phone_number }}</p>
                            </div>
                        </div>
                    @endif

                    @if($user->details?->clsu_id)
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-id-badge text-gray-500"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">CLSU ID</p>
                                <p class="text-sm font-medium text-gray-900">{{ $user->details->clsu_id }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-calendar text-gray-500"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Member Since</p>
                            <p class="text-sm font-medium text-gray-900">{{ $user->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
