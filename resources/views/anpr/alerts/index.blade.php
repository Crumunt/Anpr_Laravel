@extends('layouts.anpr')

@section('title', 'Alerts')
@section('page-title', 'Security Alerts')
@section('page-subtitle', 'Monitor and manage vehicle alerts')

@section('content')
<div class="p-4 md:p-8">
    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @foreach($stats as $stat)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-10 h-10 {{ $stat['bg'] }} rounded-lg flex items-center justify-center">
                        <i class="{{ $stat['icon'] }} {{ $stat['color'] }}"></i>
                    </div>
                    <span class="text-xs {{ $stat['trend'] === 'up' ? 'text-emerald-600' : 'text-red-600' }} font-medium">
                        <i class="fas fa-arrow-{{ $stat['trend'] }} mr-1"></i>{{ $stat['trendValue'] }}
                    </span>
                </div>
                <p class="text-2xl font-bold {{ $stat['color'] }}">{{ $stat['value'] }}</p>
                <p class="text-xs text-gray-500">{{ $stat['label'] }}</p>
            </div>
        @endforeach
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="flex items-center space-x-4">
                <h3 class="font-semibold text-gray-800">Filter Alerts</h3>
                <select class="text-sm border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                    <option>All Priorities</option>
                    <option>Critical</option>
                    <option>Warning</option>
                    <option>Info</option>
                </select>
                <select class="text-sm border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                    <option>All Status</option>
                    <option>Unresolved</option>
                    <option>Investigating</option>
                    <option>Resolved</option>
                </select>
            </div>
            <div class="relative w-full md:w-64">
                <input type="text" placeholder="Search plate number..." class="w-full pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>
    </div>

    <!-- Alerts Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">Recent Alerts</h3>
            <span class="text-sm text-gray-500">{{ count($alerts) }} alerts</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">Plate</th>
                        <th class="px-4 py-3 text-left">Type</th>
                        <th class="px-4 py-3 text-left">Priority</th>
                        <th class="px-4 py-3 text-left">Time</th>
                        <th class="px-4 py-3 text-left">Gate</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($alerts as $alert)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <span class="font-mono font-bold text-gray-900">{{ $alert['plate'] }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $alert['type'] }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $alert['priority'] === 'critical' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $alert['priority'] === 'warning' ? 'bg-amber-100 text-amber-800' : '' }}
                                    {{ $alert['priority'] === 'info' ? 'bg-blue-100 text-blue-800' : '' }}">
                                    {{ ucfirst($alert['priority']) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $alert['time'] }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $alert['gate'] }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $alert['status'] === 'unresolved' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $alert['status'] === 'investigating' ? 'bg-amber-100 text-amber-800' : '' }}
                                    {{ $alert['status'] === 'resolved' ? 'bg-green-100 text-green-800' : '' }}">
                                    {{ ucfirst($alert['status']) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <button class="p-2 text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                        <i class="fas fa-check"></i>
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
