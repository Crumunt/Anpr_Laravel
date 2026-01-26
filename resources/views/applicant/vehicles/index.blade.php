@extends('layouts.applicant')

@section('title', 'My Vehicles')

@section('content')
    <!-- Add Vehicle Modal Component -->
    @livewire('applicant.add-vehicle-modal')

    <!-- Enhanced Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 lg:mb-10 gap-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 tracking-tight mb-2">My Vehicles</h1>
            <p class="text-base text-gray-600 leading-relaxed">Manage your registered vehicles and gate passes</p>
        </div>
        <button
            type="button"
            onclick="Livewire.dispatch('openAddVehicleModal')"
            class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white text-sm font-semibold rounded-xl hover:from-emerald-700 hover:to-emerald-800 shadow-md hover:shadow-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transform hover:scale-105">
            <i class="fas fa-plus"></i> 
            <span>Register New Vehicle</span>
        </button>
    </div>

    @if($vehicles->count() > 0)
        <!-- Enhanced Vehicles Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
            @foreach($vehiclesData as $vehicle)
                <div class="group bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-xl hover:border-emerald-300 transition-all duration-300">
                    <!-- Enhanced Vehicle Header -->
                    <div class="p-5 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white flex items-center justify-between">
                        <div class="flex items-center gap-3 min-w-0 flex-1">
                            <div class="w-14 h-14 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform duration-300 flex-shrink-0">
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
                            <div class="min-w-0 flex-1">
                                <p class="font-bold text-gray-900 font-mono text-lg mb-0.5">{{ $vehicle['license_plate'] }}</p>
                                <p class="text-xs font-medium text-gray-500">{{ ucfirst($vehicle['vehicle_type'] ?? 'Vehicle') }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold border
                            @if(strtolower($vehicle['status']) === 'active' || strtolower($vehicle['status']) === 'approved') 
                                bg-emerald-100 text-emerald-800 border-emerald-200
                            @elseif(str_contains(strtolower($vehicle['status']), 'pending')) 
                                bg-amber-100 text-amber-800 border-amber-200
                            @elseif(strtolower($vehicle['status']) === 'inactive' || strtolower($vehicle['status']) === 'blacklisted' || strtolower($vehicle['status']) === 'rejected') 
                                bg-red-100 text-red-800 border-red-200
                            @else 
                                bg-gray-100 text-gray-800 border-gray-200 
                            @endif">
                            {{ $vehicle['status'] }}
                        </span>
                    </div>

                    <!-- Enhanced Vehicle Details -->
                    <div class="p-5 space-y-3">
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Make/Model</span>
                            <span class="text-sm font-bold text-gray-900">{{ $vehicle['make'] }} {{ $vehicle['model'] }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Year</span>
                            <span class="text-sm font-bold text-gray-900">{{ $vehicle['year'] }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Registered</span>
                            <span class="text-sm font-bold text-gray-900">{{ $vehicle['registered_date'] }}</span>
                        </div>
                        @if($vehicle['gate_pass'])
                            <div class="flex justify-between items-center p-3 bg-gradient-to-r from-emerald-50 to-emerald-100/50 rounded-xl border border-emerald-200">
                                <span class="text-xs font-semibold text-emerald-700 uppercase tracking-wide">Gate Pass</span>
                                <span class="text-sm font-bold text-emerald-700 font-mono flex items-center gap-1.5">
                                    <i class="fas fa-id-badge"></i>{{ $vehicle['gate_pass'] }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Enhanced Actions -->
                    <div class="p-4 border-t border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                        <a href="{{ route('applicant.vehicles.show', $vehicle['id']) }}" 
                           class="w-full inline-flex justify-center items-center gap-2 px-4 py-3 bg-white border-2 border-gray-300 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-50 hover:border-emerald-300 hover:text-emerald-700 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 group">
                            <i class="fas fa-eye group-hover:scale-110 transition-transform"></i> 
                            <span>View Details</span>
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Enhanced Pagination -->
        @if($vehicles->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $vehicles->links() }}
        </div>
        @endif
    @else
        <!-- Enhanced Empty State -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 lg:p-16 text-center">
            <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-sm">
                <i class="fas fa-car text-gray-400 text-4xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-3">No vehicles registered</h3>
            <p class="text-gray-600 mb-8 max-w-md mx-auto leading-relaxed">
                You haven't registered any vehicles yet. Register a vehicle to apply for a gate pass and start accessing CLSU facilities.
            </p>
            <button
                type="button"
                onclick="Livewire.dispatch('openAddVehicleModal')"
                class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white font-semibold rounded-xl hover:from-emerald-700 hover:to-emerald-800 shadow-lg hover:shadow-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transform hover:scale-105">
                <i class="fas fa-plus"></i> 
                <span>Register Your First Vehicle</span>
            </button>
        </div>
    @endif
@endsection
