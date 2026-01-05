@extends('layouts.applicant')

@section('title', 'My Vehicles')

@section('content')
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">My Vehicles</h1>
            <p class="text-gray-500 mt-1">Manage your registered vehicles and gate passes.</p>
        </div>
        <a href="{{ route('gate-pass.gate-pass-applicant-form') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition-colors">
            <i class="fas fa-plus mr-2"></i> Register New Vehicle
        </a>
    </div>

    @if($vehicles->count() > 0)
        <!-- Vehicles Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($vehiclesData as $vehicle)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
                    <!-- Vehicle Header -->
                    <div class="p-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                @switch($vehicle['vehicle_type'])
                                    @case('car')
                                        <i class="fas fa-car text-blue-600 text-xl"></i>
                                        @break
                                    @case('motorcycle')
                                        <i class="fas fa-motorcycle text-blue-600 text-xl"></i>
                                        @break
                                    @case('truck')
                                        <i class="fas fa-truck text-blue-600 text-xl"></i>
                                        @break
                                    @default
                                        <i class="fas fa-car text-blue-600 text-xl"></i>
                                @endswitch
                            </div>
                            <div>
                                <p class="font-bold text-gray-900 font-mono">{{ $vehicle['license_plate'] }}</p>
                                <p class="text-xs text-gray-500">{{ ucfirst($vehicle['vehicle_type'] ?? 'Vehicle') }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if(strtolower($vehicle['status']) === 'approved') bg-emerald-100 text-emerald-800
                            @elseif(strtolower($vehicle['status']) === 'pending') bg-amber-100 text-amber-800
                            @elseif(strtolower($vehicle['status']) === 'rejected') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ $vehicle['status'] }}
                        </span>
                    </div>

                    <!-- Vehicle Details -->
                    <div class="p-4 space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Make/Model</span>
                            <span class="text-sm font-medium text-gray-900">{{ $vehicle['make'] }} {{ $vehicle['model'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Year</span>
                            <span class="text-sm font-medium text-gray-900">{{ $vehicle['year'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Registered</span>
                            <span class="text-sm font-medium text-gray-900">{{ $vehicle['registered_date'] }}</span>
                        </div>
                        @if($vehicle['gate_pass'])
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">Gate Pass</span>
                                <span class="text-sm font-medium text-emerald-600">
                                    <i class="fas fa-id-badge mr-1"></i>{{ $vehicle['gate_pass'] }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="p-4 border-t border-gray-100 bg-gray-50">
                        <a href="{{ route('applicant.vehicles.show', $vehicle['id']) }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="fas fa-eye mr-2"></i> View Details
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $vehicles->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-car text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No vehicles registered</h3>
            <p class="text-gray-500 mb-6">You haven't registered any vehicles yet. Register a vehicle to apply for a gate pass.</p>
            <a href="{{ route('gate-pass.gate-pass-applicant-form') }}" class="inline-flex items-center px-6 py-3 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 transition-colors">
                <i class="fas fa-plus mr-2"></i> Register Your First Vehicle
            </a>
        </div>
    @endif
@endsection
