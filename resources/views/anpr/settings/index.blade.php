@extends('layouts.anpr')

@section('title', 'Settings')
@section('page-title', 'System Settings')
@section('page-subtitle', 'Configure ANPR system preferences')

@section('content')
<div class="p-4 md:p-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Settings -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Camera Settings -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800">Camera Settings</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-video text-emerald-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Main Gate Camera</p>
                                <p class="text-sm text-gray-500">192.168.1.101</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 text-xs font-medium rounded-full">Online</span>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-video text-emerald-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Back Gate Camera</p>
                                <p class="text-sm text-gray-500">192.168.1.102</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 text-xs font-medium rounded-full">Online</span>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                                <i class="fas fa-video text-gray-500"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Parking Lot Camera</p>
                                <p class="text-sm text-gray-500">192.168.1.103</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-0.5 bg-red-100 text-red-800 text-xs font-medium rounded-full">Offline</span>
                    </div>
                </div>
            </div>

            <!-- Alert Preferences -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800">Alert Preferences</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900">Sound Alerts</p>
                            <p class="text-sm text-gray-500">Play sound for critical alerts</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:ring-4 peer-focus:ring-emerald-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900">Desktop Notifications</p>
                            <p class="text-sm text-gray-500">Show browser notifications</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:ring-4 peer-focus:ring-emerald-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900">Auto-refresh Dashboard</p>
                            <p class="text-sm text-gray-500">Automatically update every 30 seconds</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:ring-4 peer-focus:ring-emerald-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- System Status -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800">System Status</h3>
                </div>
                <div class="p-4 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">ANPR Engine</span>
                        <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 text-xs font-medium rounded-full">Running</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Database</span>
                        <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 text-xs font-medium rounded-full">Connected</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Last Sync</span>
                        <span class="text-sm font-medium text-gray-900">{{ now()->format('H:i:s') }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800">Quick Links</h3>
                </div>
                <div class="p-4 space-y-2">
                    <a href="{{ route('anpr.user-management.profile') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-user w-5 text-gray-500"></i>
                        <span class="ml-3 text-sm font-medium text-gray-700">My Profile</span>
                    </a>
                    <a href="{{ route('anpr.dashboard') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-tachometer-alt w-5 text-gray-500"></i>
                        <span class="ml-3 text-sm font-medium text-gray-700">Dashboard</span>
                    </a>
                    <a href="{{ route('anpr.alerts') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-exclamation-triangle w-5 text-gray-500"></i>
                        <span class="ml-3 text-sm font-medium text-gray-700">View Alerts</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
