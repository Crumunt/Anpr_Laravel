@extends('layouts.app-layout')
@section('title', 'Dashboard')
@section('main-content')
    <div class="flex-1 md:ml-64 p-6 pt-24">
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-sm text-gray-500 mt-1">Welcome back! Here's an overview of your system.</p>
        </div>

        <!-- Stats Cards -->
        @livewire('admin.dashboard.dashboard-stat-cards')

        <!-- Application Breakdown & Charts -->
        @livewire('admin.dashboard.application-breakdown')

        <!-- Recent Activity Section -->
        @livewire('admin.dashboard.recent-activity')

        <!-- Quick Actions -->
        <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b border-gray-100 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-800">Quick Actions</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <a href="{{ route('admin.applicant') }}" class="flex flex-col items-center p-4 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition-colors group">
                        <div class="p-3 bg-emerald-100 rounded-full mb-3 group-hover:bg-emerald-200 transition-colors">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Manage Applicants</span>
                    </a>

                    <a href="{{ route('admin.admins') }}" class="flex flex-col items-center p-4 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition-colors group">
                        <div class="p-3 bg-emerald-100 rounded-full mb-3 group-hover:bg-emerald-200 transition-colors">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Manage Admins</span>
                    </a>

                    <a href="{{ route('admin.settings') }}" class="flex flex-col items-center p-4 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition-colors group">
                        <div class="p-3 bg-emerald-100 rounded-full mb-3 group-hover:bg-emerald-200 transition-colors">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Settings</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <x-footer.footer></x-footer.footer>
@endsection
