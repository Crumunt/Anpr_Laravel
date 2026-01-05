@extends('layouts.anpr')

@section('title', 'Analytics')
@section('page-title', 'Analytics & Reports')
@section('page-subtitle', 'Vehicle traffic insights and statistics')

@section('content')
<div class="p-4 md:p-8">
    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-barcode text-blue-600"></i>
                </div>
                <span class="text-xs text-gray-400 font-medium">TODAY</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_scans_today']) }}</p>
            <p class="text-xs text-gray-500">Total Scans</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar-week text-emerald-600"></i>
                </div>
                <span class="text-xs text-gray-400 font-medium">THIS WEEK</span>
            </div>
            <p class="text-2xl font-bold text-emerald-600">{{ number_format($stats['total_scans_week']) }}</p>
            <p class="text-xs text-gray-500">Weekly Scans</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-purple-600"></i>
                </div>
                <span class="text-xs text-gray-400 font-medium">AVERAGE</span>
            </div>
            <p class="text-2xl font-bold text-purple-600">{{ number_format($stats['avg_daily_scans']) }}</p>
            <p class="text-xs text-gray-500">Daily Average</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-amber-600"></i>
                </div>
                <span class="text-xs text-gray-400 font-medium">PEAK</span>
            </div>
            <p class="text-lg font-bold text-amber-600">{{ $stats['peak_hour'] }}</p>
            <p class="text-xs text-gray-500">Peak Hour</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Hourly Traffic Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b border-gray-100 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-800">Hourly Traffic</h3>
            </div>
            <div class="p-4">
                <div class="space-y-3">
                    @foreach(array_slice($hourlyData, 0, 10) as $data)
                        <div class="flex items-center">
                            <span class="text-sm text-gray-500 w-16">{{ $data['hour'] }}</span>
                            <div class="flex-1 flex items-center space-x-2">
                                <div class="flex-1 bg-gray-100 rounded-full h-4 overflow-hidden">
                                    <div class="bg-emerald-500 h-4 rounded-full" style="width: {{ min(($data['entries'] / 50) * 100, 100) }}%"></div>
                                </div>
                                <span class="text-xs text-gray-500 w-8">{{ $data['entries'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Gate Distribution -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b border-gray-100 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-800">Traffic by Gate</h3>
            </div>
            <div class="p-4">
                <div class="space-y-4">
                    @foreach($gateDistribution as $gate)
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-sm font-medium text-gray-700">{{ $gate['gate'] }}</span>
                                <span class="text-sm text-gray-500">{{ $gate['count'] }} ({{ $gate['percentage'] }}%)</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-3">
                                <div class="bg-emerald-500 h-3 rounded-full transition-all" style="width: {{ $gate['percentage'] }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Vehicle Types -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-800">Vehicle Type Distribution</h3>
        </div>
        <div class="p-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($vehicleTypes as $type)
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            @switch(strtolower($type['type']))
                                @case('car')
                                    <i class="fas fa-car text-emerald-600 text-xl"></i>
                                    @break
                                @case('motorcycle')
                                    <i class="fas fa-motorcycle text-emerald-600 text-xl"></i>
                                    @break
                                @case('truck')
                                    <i class="fas fa-truck text-emerald-600 text-xl"></i>
                                    @break
                                @default
                                    <i class="fas fa-car-side text-emerald-600 text-xl"></i>
                            @endswitch
                        </div>
                        <p class="text-2xl font-bold text-gray-900">{{ $type['count'] }}</p>
                        <p class="text-sm text-gray-500">{{ $type['type'] }}</p>
                        <p class="text-xs text-emerald-600 mt-1">{{ $type['percentage'] }}%</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
