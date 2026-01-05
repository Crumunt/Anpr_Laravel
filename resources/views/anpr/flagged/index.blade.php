@extends('layouts.anpr')

@section('title', 'Flagged Vehicles')
@section('page-title', 'Flagged Vehicles')
@section('page-subtitle', 'Manage suspicious and flagged vehicles')

@section('content')
<div class="p-4 md:p-8">
    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-flag text-red-600"></i>
                </div>
                <span class="text-xs text-gray-400 font-medium">TOTAL</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ count($vehicles) }}</p>
            <p class="text-xs text-gray-500">Flagged Vehicles</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exclamation-circle text-red-600"></i>
                </div>
                <span class="text-xs text-gray-400 font-medium">HIGH</span>
            </div>
            <p class="text-2xl font-bold text-red-600">{{ collect($vehicles)->where('priority', 'high')->count() }}</p>
            <p class="text-xs text-gray-500">High Priority</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exclamation text-amber-600"></i>
                </div>
                <span class="text-xs text-gray-400 font-medium">MEDIUM</span>
            </div>
            <p class="text-2xl font-bold text-amber-600">{{ collect($vehicles)->where('priority', 'medium')->count() }}</p>
            <p class="text-xs text-gray-500">Medium Priority</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-emerald-600"></i>
                </div>
                <span class="text-xs text-gray-400 font-medium">RESOLVED</span>
            </div>
            <p class="text-2xl font-bold text-emerald-600">{{ collect($vehicles)->where('status', 'resolved')->count() }}</p>
            <p class="text-xs text-gray-500">Resolved Cases</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="flex items-center space-x-4">
                <h3 class="font-semibold text-gray-800">Filter Vehicles</h3>
                <select class="text-sm border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                    <option>All Priorities</option>
                    <option>High</option>
                    <option>Medium</option>
                    <option>Low</option>
                </select>
                <select class="text-sm border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                    <option>All Status</option>
                    <option>Active</option>
                    <option>Resolved</option>
                </select>
            </div>
            <div class="relative w-full md:w-64">
                <input type="text" placeholder="Search plate number..." class="w-full pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>
    </div>

    <!-- Flagged Vehicles Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">Flagged Vehicles List</h3>
            <button class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                <i class="fas fa-plus mr-2"></i> Flag Vehicle
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">Vehicle</th>
                        <th class="px-4 py-3 text-left">Reason</th>
                        <th class="px-4 py-3 text-left">Priority</th>
                        <th class="px-4 py-3 text-left">Flagged By</th>
                        <th class="px-4 py-3 text-left">Date</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($vehicles as $vehicle)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-car text-gray-500"></i>
                                    </div>
                                    <div>
                                        <p class="font-mono font-bold text-gray-900">{{ $vehicle['licensePlate'] }}</p>
                                        <p class="text-xs text-gray-500">{{ $vehicle['model'] }} - {{ $vehicle['color'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <p class="text-sm font-medium text-gray-900">{{ $vehicle['reasonLabel'] }}</p>
                                <p class="text-xs text-gray-500">{{ Str::limit($vehicle['description'], 30) }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $vehicle['priority'] === 'high' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $vehicle['priority'] === 'medium' ? 'bg-amber-100 text-amber-800' : '' }}
                                    {{ $vehicle['priority'] === 'low' ? 'bg-blue-100 text-blue-800' : '' }}">
                                    {{ ucfirst($vehicle['priority']) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <p class="text-sm text-gray-900">{{ $vehicle['flaggedBy'] }}</p>
                                <p class="text-xs text-gray-500">{{ $vehicle['flaggedByRole'] }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <p class="text-sm text-gray-900">{{ $vehicle['dateFlagged'] }}</p>
                                <p class="text-xs text-gray-500">{{ $vehicle['timeFlagged'] }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $vehicle['status'] === 'active' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $vehicle['status'] === 'resolved' ? 'bg-green-100 text-green-800' : '' }}">
                                    {{ ucfirst($vehicle['status']) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <button class="p-2 text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if($vehicle['status'] === 'active')
                                        <button class="p-2 text-gray-500 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors" title="Mark Resolved">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    @endif
                                    <button class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Remove Flag">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
