@extends('layouts.anpr-layout')

@section('title', 'Flagged Vehicles')
@section('page-title', 'Flagged Vehicles')
@section('page-subtitle', 'Manage suspicious and flagged vehicles')

@section('content')
<div class="space-y-6 lg:space-y-8">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 lg:p-6 hover:shadow-md hover:border-gray-300 transition-all duration-200 group">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-red-50 group-hover:bg-red-100 rounded-xl flex items-center justify-center transition-colors duration-200">
                    <i class="fas fa-flag text-red-600 text-lg" aria-hidden="true"></i>
                </div>
                <span class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Total</span>
            </div>
            <p class="text-3xl font-bold text-gray-900 mb-1 leading-tight">{{ count($vehicles ?? []) }}</p>
            <p class="text-sm text-gray-600 font-medium">Flagged Vehicles</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 lg:p-6 hover:shadow-md hover:border-gray-300 transition-all duration-200 group">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-red-50 group-hover:bg-red-100 rounded-xl flex items-center justify-center transition-colors duration-200">
                    <i class="fas fa-exclamation-circle text-red-600 text-lg" aria-hidden="true"></i>
                </div>
                <span class="text-xs text-gray-500 font-semibold uppercase tracking-wide">High</span>
            </div>
            <p class="text-3xl font-bold text-red-600 mb-1 leading-tight">{{ collect($vehicles ?? [])->where('priority', 'high')->count() }}</p>
            <p class="text-sm text-gray-600 font-medium">High Priority</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 lg:p-6 hover:shadow-md hover:border-gray-300 transition-all duration-200 group">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-amber-50 group-hover:bg-amber-100 rounded-xl flex items-center justify-center transition-colors duration-200">
                    <i class="fas fa-exclamation text-amber-600 text-lg" aria-hidden="true"></i>
                </div>
                <span class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Medium</span>
            </div>
            <p class="text-3xl font-bold text-amber-600 mb-1 leading-tight">{{ collect($vehicles ?? [])->where('priority', 'medium')->count() }}</p>
            <p class="text-sm text-gray-600 font-medium">Medium Priority</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 lg:p-6 hover:shadow-md hover:border-gray-300 transition-all duration-200 group">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-emerald-50 group-hover:bg-emerald-100 rounded-xl flex items-center justify-center transition-colors duration-200">
                    <i class="fas fa-check-circle text-emerald-600 text-lg" aria-hidden="true"></i>
                </div>
                <span class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Resolved</span>
            </div>
            <p class="text-3xl font-bold text-emerald-600 mb-1 leading-tight">{{ collect($vehicles ?? [])->where('status', 'resolved')->count() }}</p>
            <p class="text-sm text-gray-600 font-medium">Resolved Cases</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 lg:p-6">
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 flex-1 w-full lg:w-auto">
                <h3 class="font-semibold text-gray-900 text-base whitespace-nowrap">Filter Vehicles</h3>
                <div class="flex flex-wrap items-center gap-3 flex-1">
                    <label for="priority-filter" class="sr-only">Filter by Priority</label>
                    <select id="priority-filter" class="text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 px-3 py-2 bg-white shadow-sm min-w-[140px]">
                        <option value="">All Priorities</option>
                        <option value="high">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                    </select>
                    <label for="status-filter" class="sr-only">Filter by Status</label>
                    <select id="status-filter" class="text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 px-3 py-2 bg-white shadow-sm min-w-[140px]">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="resolved">Resolved</option>
                    </select>
                </div>
            </div>
            <div class="relative w-full lg:w-64">
                <label for="search-input" class="sr-only">Search plate number</label>
                <input type="text" id="search-input" placeholder="Search plate number..." class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white shadow-sm">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" aria-hidden="true"></i>
            </div>
        </div>
    </div>

    <!-- Flagged Vehicles Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Flagged Vehicles List</h3>
                <p class="text-sm text-gray-600 mt-1">{{ count($vehicles ?? []) }} vehicle(s) flagged</p>
            </div>
            <button type="button" class="px-4 py-2.5 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 shadow-sm flex items-center">
                <i class="fas fa-plus mr-2" aria-hidden="true"></i>
                Flag Vehicle
            </button>
        </div>
        <div class="overflow-x-auto">
            @if(isset($vehicles) && count($vehicles) > 0)
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Vehicle</th>
                            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Reason</th>
                            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Priority</th>
                            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Flagged By</th>
                            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Date</th>
                            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                            <th class="px-5 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($vehicles as $vehicle)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-5 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-11 h-11 bg-gray-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-car text-gray-500" aria-hidden="true"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-mono font-bold text-gray-900 text-sm">{{ $vehicle['licensePlate'] ?? 'N/A' }}</p>
                                            <p class="text-xs text-gray-600 mt-0.5">{{ ($vehicle['model'] ?? '') }} - {{ ($vehicle['color'] ?? '') }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="text-sm font-semibold text-gray-900">{{ $vehicle['reasonLabel'] ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-600 mt-0.5 line-clamp-1">{{ Str::limit($vehicle['description'] ?? '', 40) }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    @php
                                        $priority = strtolower($vehicle['priority'] ?? 'low');
                                        $priorityClasses = [
                                            'high' => 'bg-red-100 text-red-800 border-red-200',
                                            'medium' => 'bg-amber-100 text-amber-800 border-amber-200',
                                            'low' => 'bg-blue-100 text-blue-800 border-blue-200'
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $priorityClasses[$priority] ?? $priorityClasses['low'] }}">
                                        {{ ucfirst($priority) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="text-sm font-medium text-gray-900">{{ $vehicle['flaggedBy'] ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-600 mt-0.5">{{ $vehicle['flaggedByRole'] ?? 'N/A' }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="text-sm font-medium text-gray-900">{{ $vehicle['dateFlagged'] ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-600 mt-0.5">{{ $vehicle['timeFlagged'] ?? 'N/A' }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    @php
                                        $status = strtolower($vehicle['status'] ?? 'active');
                                        $statusClasses = [
                                            'active' => 'bg-red-100 text-red-800 border-red-200',
                                            'resolved' => 'bg-emerald-100 text-emerald-800 border-emerald-200'
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $statusClasses[$status] ?? $statusClasses['active'] }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-center space-x-1">
                                        <button type="button" class="p-2 text-gray-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2" aria-label="View Details" title="View Details">
                                            <i class="fas fa-eye" aria-hidden="true"></i>
                                        </button>
                                        @if(($vehicle['status'] ?? '') === 'active')
                                            <button type="button" class="p-2 text-gray-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2" aria-label="Mark Resolved" title="Mark Resolved">
                                                <i class="fas fa-check" aria-hidden="true"></i>
                                            </button>
                                        @endif
                                        <button type="button" class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2" aria-label="Remove Flag" title="Remove Flag">
                                            <i class="fas fa-times" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <!-- Empty State -->
                <div class="px-5 py-16 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                        <i class="fas fa-flag text-gray-400 text-2xl" aria-hidden="true"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No Flagged Vehicles</h3>
                    <p class="text-sm text-gray-600 max-w-sm mx-auto mb-6">There are currently no vehicles flagged in the system. Flagged vehicles will appear here once marked.</p>
                    <button type="button" class="px-4 py-2.5 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 shadow-sm inline-flex items-center">
                        <i class="fas fa-plus mr-2" aria-hidden="true"></i>
                        Flag Vehicle
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
