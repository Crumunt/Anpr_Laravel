@extends('layouts.anpr-layout')

@section('title', 'Analytics')
@section('page-title', 'Analytics & Reports')
@section('page-subtitle', 'Vehicle traffic insights and statistics')

@section('content')
<div class="space-y-6 lg:space-y-8">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 lg:p-6 hover:shadow-md hover:border-gray-300 transition-all duration-200 group">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-blue-50 group-hover:bg-blue-100 rounded-xl flex items-center justify-center transition-colors duration-200">
                    <i class="fas fa-barcode text-blue-600 text-lg" aria-hidden="true"></i>
                </div>
                <span class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Today</span>
            </div>
            <p class="text-3xl font-bold text-gray-900 mb-1 leading-tight">{{ number_format($stats['total_scans_today'] ?? 0) }}</p>
            <p class="text-sm text-gray-600 font-medium">Total Scans</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 lg:p-6 hover:shadow-md hover:border-gray-300 transition-all duration-200 group">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-emerald-50 group-hover:bg-emerald-100 rounded-xl flex items-center justify-center transition-colors duration-200">
                    <i class="fas fa-calendar-week text-emerald-600 text-lg" aria-hidden="true"></i>
                </div>
                <span class="text-xs text-gray-500 font-semibold uppercase tracking-wide">This Week</span>
            </div>
            <p class="text-3xl font-bold text-emerald-600 mb-1 leading-tight">{{ number_format($stats['total_scans_week'] ?? 0) }}</p>
            <p class="text-sm text-gray-600 font-medium">Weekly Scans</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 lg:p-6 hover:shadow-md hover:border-gray-300 transition-all duration-200 group">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-purple-50 group-hover:bg-purple-100 rounded-xl flex items-center justify-center transition-colors duration-200">
                    <i class="fas fa-chart-line text-purple-600 text-lg" aria-hidden="true"></i>
                </div>
                <span class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Average</span>
            </div>
            <p class="text-3xl font-bold text-purple-600 mb-1 leading-tight">{{ number_format($stats['avg_daily_scans'] ?? 0) }}</p>
            <p class="text-sm text-gray-600 font-medium">Daily Average</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 lg:p-6 hover:shadow-md hover:border-gray-300 transition-all duration-200 group">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-amber-50 group-hover:bg-amber-100 rounded-xl flex items-center justify-center transition-colors duration-200">
                    <i class="fas fa-clock text-amber-600 text-lg" aria-hidden="true"></i>
                </div>
                <span class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Peak</span>
            </div>
            <p class="text-2xl font-bold text-amber-600 mb-1 leading-tight">{{ $stats['peak_hour'] ?? 'N/A' }}</p>
            <p class="text-sm text-gray-600 font-medium">Peak Hour</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
        <!-- Hourly Traffic Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                <h3 class="text-lg font-semibold text-gray-900">Hourly Traffic</h3>
                <p class="text-sm text-gray-600 mt-1">Vehicle entries by hour</p>
            </div>
            <div class="p-5 lg:p-6">
                @if(isset($hourlyData) && count($hourlyData) > 0)
                    <div class="space-y-4">
                        @foreach(array_slice($hourlyData, 0, 12) as $data)
                            <div class="flex items-center group">
                                <span class="text-sm text-gray-700 font-medium w-16 flex-shrink-0">{{ $data['hour'] ?? 'N/A' }}</span>
                                <div class="flex-1 flex items-center space-x-3">
                                    <div class="flex-1 bg-gray-100 rounded-full h-5 overflow-hidden shadow-inner">
                                        @php
                                            $maxValue = max(array_column($hourlyData ?? [], 'entries')) ?: 1;
                                            $percentage = min((($data['entries'] ?? 0) / $maxValue) * 100, 100);
                                        @endphp
                                        <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 h-5 rounded-full transition-all duration-500 group-hover:from-emerald-600 group-hover:to-emerald-700" style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <span class="text-sm text-gray-700 font-semibold w-12 text-right">{{ $data['entries'] ?? 0 }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="py-12 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                            <i class="fas fa-chart-bar text-gray-400 text-2xl" aria-hidden="true"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No Data Available</h3>
                        <p class="text-sm text-gray-600">Hourly traffic data will appear here once available.</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Gate Distribution -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                <h3 class="text-lg font-semibold text-gray-900">Traffic by Gate</h3>
                <p class="text-sm text-gray-600 mt-1">Vehicle distribution across gates</p>
            </div>
            <div class="p-5 lg:p-6">
                @if(isset($gateDistribution) && count($gateDistribution) > 0)
                    <div class="space-y-5">
                    @foreach($gateDistribution as $gate)
                        <div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-semibold text-gray-900">{{ $gate['gate'] ?? 'N/A' }}</span>
                                    <span class="text-sm text-gray-700 font-medium">{{ $gate['count'] ?? 0 }} <span class="text-gray-500">({{ $gate['percentage'] ?? 0 }}%)</span></span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-3 shadow-inner overflow-hidden">
                                    <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 h-3 rounded-full transition-all duration-500" style="width: {{ min(($gate['percentage'] ?? 0), 100) }}%"></div>
                                </div>
                            </div>
                        @endforeach
                            </div>
                @else
                    <!-- Empty State -->
                    <div class="py-12 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                            <i class="fas fa-door-open text-gray-400 text-2xl" aria-hidden="true"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No Data Available</h3>
                        <p class="text-sm text-gray-600">Gate distribution data will appear here once available.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Vehicle Types -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
            <h3 class="text-lg font-semibold text-gray-900">Vehicle Type Distribution</h3>
            <p class="text-sm text-gray-600 mt-1">Breakdown by vehicle category</p>
        </div>
        <div class="p-5 lg:p-6">
            @if(isset($vehicleTypes) && count($vehicleTypes) > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 lg:gap-6">
                @foreach($vehicleTypes as $type)
                        <div class="text-center p-5 bg-gradient-to-br from-gray-50 to-white rounded-xl border border-gray-200 hover:shadow-md transition-all duration-200 group">
                            <div class="w-14 h-14 bg-emerald-100 group-hover:bg-emerald-200 rounded-full flex items-center justify-center mx-auto mb-3 transition-colors">
                                @php
                                    $vehicleType = strtolower($type['type'] ?? '');
                                    $icons = [
                                        'car' => 'fa-car',
                                        'motorcycle' => 'fa-motorcycle',
                                        'truck' => 'fa-truck',
                                        'van' => 'fa-shuttle-van',
                                        'suv' => 'fa-car-side'
                                    ];
                                    $icon = $icons[$vehicleType] ?? 'fa-car-side';
                                @endphp
                                <i class="fas {{ $icon }} text-emerald-600 text-xl" aria-hidden="true"></i>
                            </div>
                            <p class="text-2xl font-bold text-gray-900 mb-1">{{ $type['count'] ?? 0 }}</p>
                            <p class="text-sm font-semibold text-gray-700 mb-1">{{ $type['type'] ?? 'N/A' }}</p>
                            <p class="text-xs text-emerald-600 font-semibold">{{ $type['percentage'] ?? 0 }}%</p>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="py-12 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                        <i class="fas fa-car text-gray-400 text-2xl" aria-hidden="true"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No Data Available</h3>
                    <p class="text-sm text-gray-600">Vehicle type distribution will appear here once available.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
