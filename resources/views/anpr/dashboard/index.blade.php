@extends('layouts.anpr')

@section('title', 'Dashboard')
@section('page-title', 'ANPR Dashboard')
@section('page-subtitle', 'Real-time Vehicle Monitoring System')

@section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
        <!-- Total Vehicles -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-car text-blue-600"></i>
                </div>
                <span class="text-xs text-gray-400 font-medium">TOTAL</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_vehicles']) }}</p>
            <p class="text-xs text-gray-500">Registered Vehicles</p>
        </div>

        <!-- Approved Vehicles -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-emerald-600"></i>
                </div>
                <span class="text-xs text-gray-400 font-medium">APPROVED</span>
            </div>
            <p class="text-2xl font-bold text-emerald-600">{{ number_format($stats['approved_vehicles']) }}</p>
            <p class="text-xs text-gray-500">Authorized Vehicles</p>
        </div>

        <!-- Scans Today -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-barcode text-indigo-600"></i>
                </div>
                <span class="text-xs text-gray-400 font-medium">TODAY</span>
            </div>
            <p class="text-2xl font-bold text-indigo-600">{{ number_format($stats['scans_today']) }}</p>
            <p class="text-xs text-gray-500">Plate Scans</p>
        </div>

        <!-- Active Cameras -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-video text-purple-600"></i>
                </div>
                <span class="text-xs text-gray-400 font-medium">ACTIVE</span>
            </div>
            <p class="text-2xl font-bold text-purple-600">{{ $stats['active_cameras'] }}</p>
            <p class="text-xs text-gray-500">ANPR Cameras</p>
        </div>

        <!-- Alerts Today -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-amber-600"></i>
                </div>
                <span class="text-xs text-gray-400 font-medium">TODAY</span>
            </div>
            <p class="text-2xl font-bold text-amber-600">{{ $stats['alerts_today'] }}</p>
            <p class="text-xs text-gray-500">Total Alerts</p>
        </div>

        <!-- Flagged Vehicles -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-flag text-red-600"></i>
                </div>
                <span class="text-xs text-gray-400 font-medium">FLAGGED</span>
            </div>
            <p class="text-2xl font-bold text-red-600">{{ $stats['flagged_vehicles'] }}</p>
            <p class="text-xs text-gray-500">Suspicious Vehicles</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Live Camera Feed -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                    <h3 class="text-lg font-semibold text-gray-800">Live Camera Feed</h3>
                </div>
                <div class="flex items-center space-x-2">
                    <select class="text-sm border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                        <option>Main Gate Camera</option>
                        <option>Back Gate Camera</option>
                        <option>Parking Lot Camera</option>
                    </select>
                    <button class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-expand"></i>
                    </button>
                </div>
            </div>
            <div class="aspect-video bg-gray-900 relative flex items-center justify-center">
                <!-- Placeholder for camera feed -->
                <div class="text-center text-gray-400">
                    <i class="fas fa-video text-6xl mb-4"></i>
                    <p class="text-lg font-medium">Camera Feed</p>
                    <p class="text-sm">Main Gate - Live</p>
                </div>

                <!-- Overlay Info -->
                <div class="absolute bottom-4 left-4 right-4 flex justify-between items-end">
                    <div class="bg-black/70 text-white px-3 py-2 rounded-lg">
                        <p class="text-xs text-gray-300">Last Detection</p>
                        <p class="text-lg font-bold font-mono">ABC 1234</p>
                    </div>
                    <div class="bg-emerald-600 text-white px-3 py-2 rounded-lg text-sm font-medium">
                        <i class="fas fa-check mr-1"></i> Authorized
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats Panel -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b border-gray-100 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-800">Alert Summary</h3>
            </div>
            <div class="p-4 space-y-4">
                <!-- Critical -->
                <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-radiation text-red-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Critical</p>
                            <p class="text-xs text-gray-500">Immediate action required</p>
                        </div>
                    </div>
                    <span class="text-2xl font-bold text-red-600">{{ $alertsCount['critical'] }}</span>
                </div>

                <!-- Warning -->
                <div class="flex items-center justify-between p-3 bg-amber-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-amber-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Warning</p>
                            <p class="text-xs text-gray-500">Review recommended</p>
                        </div>
                    </div>
                    <span class="text-2xl font-bold text-amber-600">{{ $alertsCount['warning'] }}</span>
                </div>

                <!-- Info -->
                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-info-circle text-blue-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Information</p>
                            <p class="text-xs text-gray-500">For your attention</p>
                        </div>
                    </div>
                    <span class="text-2xl font-bold text-blue-600">{{ $alertsCount['info'] }}</span>
                </div>

                <a href="{{ route('anpr.alerts') }}" class="block w-full text-center py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                    View All Alerts <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Scans Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">Recent Vehicle Scans</h3>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-500">Last 5 scans</span>
                <button class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">License Plate</th>
                        <th class="px-4 py-3 text-left">Time</th>
                        <th class="px-4 py-3 text-left">Gate</th>
                        <th class="px-4 py-3 text-left">Direction</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($recentScans as $scan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <span class="font-mono font-bold text-gray-900">{{ $scan['plate'] }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $scan['time'] }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $scan['gate'] }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center text-sm {{ $scan['direction'] === 'Entry' ? 'text-emerald-600' : 'text-blue-600' }}">
                                    <i class="fas fa-{{ $scan['direction'] === 'Entry' ? 'arrow-right' : 'arrow-left' }} mr-1"></i>
                                    {{ $scan['direction'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $scan['status'] === 'Authorized' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                    {{ $scan['status'] === 'Flagged' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $scan['status'] === 'Unknown' ? 'bg-gray-100 text-gray-800' : '' }}">
                                    @if($scan['status'] === 'Authorized')
                                        <i class="fas fa-check mr-1"></i>
                                    @elseif($scan['status'] === 'Flagged')
                                        <i class="fas fa-flag mr-1"></i>
                                    @else
                                        <i class="fas fa-question mr-1"></i>
                                    @endif
                                    {{ $scan['status'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
