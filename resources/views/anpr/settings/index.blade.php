@extends('layouts.anpr-layout')

@section('title', 'Settings')
@section('page-title', 'System Settings')
@section('page-subtitle', 'Configure ANPR system preferences')

@section('content')
<div class="space-y-6 lg:space-y-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
        <!-- Main Settings -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Camera Settings -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                    <h3 class="text-lg font-semibold text-gray-900">Camera Settings</h3>
                    <p class="text-sm text-gray-600 mt-1">Manage and monitor ANPR camera connections</p>
                </div>
                <div class="p-5 lg:p-6 space-y-3">
                    <!-- Camera Item 1 -->
                    <div class="flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 rounded-xl border border-gray-200 transition-all duration-200 group">
                        <div class="flex items-center space-x-4 flex-1 min-w-0">
                            <div class="w-12 h-12 bg-emerald-100 group-hover:bg-emerald-200 rounded-xl flex items-center justify-center flex-shrink-0 transition-colors">
                                <i class="fas fa-video text-emerald-600 text-lg" aria-hidden="true"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-semibold text-gray-900 text-base">Main Gate Camera</p>
                                <p class="text-sm text-gray-600 mt-0.5">192.168.1.101</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3 ml-4">
                            <span class="px-3 py-1.5 bg-emerald-100 text-emerald-800 text-xs font-semibold rounded-full border border-emerald-200 flex items-center">
                                <span class="w-2 h-2 bg-emerald-600 rounded-full mr-2 animate-pulse" aria-hidden="true"></span>
                                Online
                            </span>
                            <button type="button" class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-200 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2" aria-label="Camera settings">
                                <i class="fas fa-cog" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Camera Item 2 -->
                    <div class="flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 rounded-xl border border-gray-200 transition-all duration-200 group">
                        <div class="flex items-center space-x-4 flex-1 min-w-0">
                            <div class="w-12 h-12 bg-emerald-100 group-hover:bg-emerald-200 rounded-xl flex items-center justify-center flex-shrink-0 transition-colors">
                                <i class="fas fa-video text-emerald-600 text-lg" aria-hidden="true"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-semibold text-gray-900 text-base">Back Gate Camera</p>
                                <p class="text-sm text-gray-600 mt-0.5">192.168.1.102</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3 ml-4">
                            <span class="px-3 py-1.5 bg-emerald-100 text-emerald-800 text-xs font-semibold rounded-full border border-emerald-200 flex items-center">
                                <span class="w-2 h-2 bg-emerald-600 rounded-full mr-2 animate-pulse" aria-hidden="true"></span>
                                Online
                            </span>
                            <button type="button" class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-200 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2" aria-label="Camera settings">
                                <i class="fas fa-cog" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Camera Item 3 -->
                    <div class="flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 rounded-xl border border-gray-200 transition-all duration-200 group">
                        <div class="flex items-center space-x-4 flex-1 min-w-0">
                            <div class="w-12 h-12 bg-gray-200 group-hover:bg-gray-300 rounded-xl flex items-center justify-center flex-shrink-0 transition-colors">
                                <i class="fas fa-video text-gray-500 text-lg" aria-hidden="true"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-semibold text-gray-900 text-base">Parking Lot Camera</p>
                                <p class="text-sm text-gray-600 mt-0.5">192.168.1.103</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3 ml-4">
                            <span class="px-3 py-1.5 bg-red-100 text-red-800 text-xs font-semibold rounded-full border border-red-200 flex items-center">
                                <span class="w-2 h-2 bg-red-600 rounded-full mr-2" aria-hidden="true"></span>
                                Offline
                            </span>
                            <button type="button" class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-200 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2" aria-label="Camera settings">
                                <i class="fas fa-cog" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Add Camera Button -->
                    <button type="button" class="w-full p-4 border-2 border-dashed border-gray-300 hover:border-emerald-500 rounded-xl text-gray-600 hover:text-emerald-600 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 group">
                        <div class="flex items-center justify-center space-x-2">
                            <i class="fas fa-plus text-lg group-hover:scale-110 transition-transform" aria-hidden="true"></i>
                            <span class="font-semibold">Add New Camera</span>
                        </div>
                    </button>
                </div>
            </div>

            <!-- Alert Preferences -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                    <h3 class="text-lg font-semibold text-gray-900">Alert Preferences</h3>
                    <p class="text-sm text-gray-600 mt-1">Customize how you receive notifications</p>
                </div>
                <div class="p-5 lg:p-6 space-y-4">
                    <!-- Sound Alerts -->
                    <div class="flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 rounded-xl border border-gray-200 transition-all duration-200">
                        <div class="flex-1 min-w-0 pr-4">
                            <p class="font-semibold text-gray-900 text-base mb-1">Sound Alerts</p>
                            <p class="text-sm text-gray-600">Play sound for critical alerts</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                            <input type="checkbox" class="sr-only peer" checked aria-label="Toggle sound alerts">
                            <div class="w-12 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        </label>
                    </div>

                    <!-- Desktop Notifications -->
                    <div class="flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 rounded-xl border border-gray-200 transition-all duration-200">
                        <div class="flex-1 min-w-0 pr-4">
                            <p class="font-semibold text-gray-900 text-base mb-1">Desktop Notifications</p>
                            <p class="text-sm text-gray-600">Show browser notifications</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                            <input type="checkbox" class="sr-only peer" checked aria-label="Toggle desktop notifications">
                            <div class="w-12 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        </label>
                    </div>

                    <!-- Auto-refresh Dashboard -->
                    <div class="flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 rounded-xl border border-gray-200 transition-all duration-200">
                        <div class="flex-1 min-w-0 pr-4">
                            <p class="font-semibold text-gray-900 text-base mb-1">Auto-refresh Dashboard</p>
                            <p class="text-sm text-gray-600">Automatically update every 30 seconds</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                            <input type="checkbox" class="sr-only peer" checked aria-label="Toggle auto-refresh">
                            <div class="w-12 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="flex justify-end space-x-3">
                <button type="button" class="px-6 py-3 text-gray-700 bg-white border border-gray-300 rounded-xl font-semibold hover:bg-gray-50 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-3 bg-emerald-600 text-white rounded-xl font-semibold hover:bg-emerald-700 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 shadow-sm">
                    Save Changes
                </button>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- System Status -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                    <h3 class="text-lg font-semibold text-gray-900">System Status</h3>
                </div>
                <div class="p-5 space-y-4">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm text-gray-700 font-medium">ANPR Engine</span>
                        <span class="px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-semibold rounded-full border border-emerald-200 flex items-center">
                            <span class="w-2 h-2 bg-emerald-600 rounded-full mr-2" aria-hidden="true"></span>
                            Running
                        </span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm text-gray-700 font-medium">Database</span>
                        <span class="px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-semibold rounded-full border border-emerald-200 flex items-center">
                            <span class="w-2 h-2 bg-emerald-600 rounded-full mr-2" aria-hidden="true"></span>
                            Connected
                        </span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm text-gray-700 font-medium">Last Sync</span>
                        <span class="text-sm font-semibold text-gray-900">{{ now()->format('H:i:s') }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                    <h3 class="text-lg font-semibold text-gray-900">Quick Links</h3>
                </div>
                <div class="p-2">
                    <a href="{{ route('anpr.user-management.profile') }}" class="flex items-center px-4 py-3 rounded-xl hover:bg-gray-50 transition-colors duration-200 group">
                        <i class="fas fa-user w-5 text-gray-500 group-hover:text-emerald-600 transition-colors" aria-hidden="true"></i>
                        <span class="ml-3 text-sm font-medium text-gray-700 group-hover:text-gray-900">My Profile</span>
                    </a>
                    <a href="{{ route('anpr.dashboard') }}" class="flex items-center px-4 py-3 rounded-xl hover:bg-gray-50 transition-colors duration-200 group">
                        <i class="fas fa-tachometer-alt w-5 text-gray-500 group-hover:text-emerald-600 transition-colors" aria-hidden="true"></i>
                        <span class="ml-3 text-sm font-medium text-gray-700 group-hover:text-gray-900">Dashboard</span>
                    </a>
                    <a href="{{ route('anpr.alerts') }}" class="flex items-center px-4 py-3 rounded-xl hover:bg-gray-50 transition-colors duration-200 group">
                        <i class="fas fa-exclamation-triangle w-5 text-gray-500 group-hover:text-emerald-600 transition-colors" aria-hidden="true"></i>
                        <span class="ml-3 text-sm font-medium text-gray-700 group-hover:text-gray-900">View Alerts</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
