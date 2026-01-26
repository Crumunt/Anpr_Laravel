@extends('layouts.anpr-layout')

@section('title', 'Alerts')
@section('page-title', 'Security Alerts')
@section('page-subtitle', 'Monitor and manage vehicle alerts')

@section('content')
<div class="space-y-6 lg:space-y-8">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
        @if(isset($stats) && count($stats) > 0)
            @foreach($stats as $stat)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 lg:p-6 hover:shadow-md hover:border-gray-300 transition-all duration-200 group">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 {{ $stat['bg'] ?? 'bg-gray-50' }} group-hover:opacity-90 rounded-xl flex items-center justify-center transition-all duration-200">
                            <i class="{{ $stat['icon'] ?? 'fas fa-circle' }} {{ $stat['color'] ?? 'text-gray-600' }} text-lg" aria-hidden="true"></i>
                        </div>
                        <span class="text-xs font-semibold {{ ($stat['trend'] ?? '') === 'up' ? 'text-emerald-600' : 'text-red-600' }} flex items-center">
                            <i class="fas fa-arrow-{{ $stat['trend'] ?? 'up' }} mr-1" aria-hidden="true"></i>
                            {{ $stat['trendValue'] ?? '0%' }}
                        </span>
                    </div>
                    <p class="text-3xl font-bold {{ $stat['color'] ?? 'text-gray-900' }} mb-1 leading-tight">{{ $stat['value'] ?? 0 }}</p>
                    <p class="text-sm text-gray-600 font-medium">{{ $stat['label'] ?? 'N/A' }}</p>
                </div>
            @endforeach
        @else
            <!-- Default Stats if not provided -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 lg:p-6">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-red-600 text-lg" aria-hidden="true"></i>
                    </div>
                </div>
                <p class="text-3xl font-bold text-gray-900 mb-1">0</p>
                <p class="text-sm text-gray-600 font-medium">Total Alerts</p>
            </div>
        @endif
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 lg:p-6">
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 flex-1 w-full lg:w-auto">
                <h3 class="font-semibold text-gray-900 text-base whitespace-nowrap">Filter Alerts</h3>
                <div class="flex flex-wrap items-center gap-3 flex-1">
                    <label for="priority-filter" class="sr-only">Filter by Priority</label>
                    <select id="priority-filter" class="text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 px-3 py-2 bg-white shadow-sm min-w-[140px]">
                        <option value="">All Priorities</option>
                        <option value="critical">Critical</option>
                        <option value="warning">Warning</option>
                        <option value="info">Info</option>
                    </select>
                    <label for="status-filter" class="sr-only">Filter by Status</label>
                    <select id="status-filter" class="text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 px-3 py-2 bg-white shadow-sm min-w-[140px]">
                        <option value="">All Status</option>
                        <option value="unresolved">Unresolved</option>
                        <option value="investigating">Investigating</option>
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

    <!-- Alerts Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Recent Alerts</h3>
                <p class="text-sm text-gray-600 mt-1">{{ count($alerts ?? []) }} alert(s) found</p>
            </div>
            <div class="flex items-center space-x-2">
                <button type="button" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                    Export
                </button>
                <button type="button" class="px-4 py-2 text-sm font-semibold text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 shadow-sm">
                    Mark All Read
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            @if(isset($alerts) && count($alerts) > 0)
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Plate</th>
                            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Type</th>
                            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Priority</th>
                            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Time</th>
                            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Gate</th>
                            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                            <th class="px-5 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($alerts as $alert)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-5 py-4">
                                    <span class="font-mono font-bold text-gray-900 text-sm">{{ $alert['plate'] ?? 'N/A' }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="text-sm text-gray-700 font-medium">{{ $alert['type'] ?? 'N/A' }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    @php
                                        $priority = strtolower($alert['priority'] ?? 'info');
                                        $priorityClasses = [
                                            'critical' => 'bg-red-100 text-red-800 border-red-200',
                                            'warning' => 'bg-amber-100 text-amber-800 border-amber-200',
                                            'info' => 'bg-blue-100 text-blue-800 border-blue-200'
                                        ];
                                        $priorityIcons = [
                                            'critical' => 'fa-exclamation-circle',
                                            'warning' => 'fa-exclamation-triangle',
                                            'info' => 'fa-info-circle'
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $priorityClasses[$priority] ?? $priorityClasses['info'] }}">
                                        <i class="fas {{ $priorityIcons[$priority] ?? 'fa-info-circle' }} mr-1.5" aria-hidden="true"></i>
                                        {{ ucfirst($priority) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="text-sm text-gray-700 font-medium">{{ $alert['time'] ?? 'N/A' }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="text-sm text-gray-700 font-medium">{{ $alert['gate'] ?? 'N/A' }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    @php
                                        $status = strtolower($alert['status'] ?? 'unresolved');
                                        $statusClasses = [
                                            'unresolved' => 'bg-red-100 text-red-800 border-red-200',
                                            'investigating' => 'bg-amber-100 text-amber-800 border-amber-200',
                                            'resolved' => 'bg-emerald-100 text-emerald-800 border-emerald-200'
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $statusClasses[$status] ?? $statusClasses['unresolved'] }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-center space-x-1">
                                        <button type="button" class="p-2 text-gray-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2" aria-label="View Alert" title="View Alert">
                                            <i class="fas fa-eye" aria-hidden="true"></i>
                                        </button>
                                        <button type="button" class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" aria-label="Resolve Alert" title="Resolve Alert">
                                            <i class="fas fa-check" aria-hidden="true"></i>
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
                        <i class="fas fa-exclamation-triangle text-gray-400 text-2xl" aria-hidden="true"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No Alerts Found</h3>
                    <p class="text-sm text-gray-600 max-w-sm mx-auto">There are currently no alerts in the system. Alerts will appear here when vehicles trigger security notifications.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
