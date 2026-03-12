@extends('layouts.app-layout')
@section('main-content')

@php
    $admin = \App\Models\User::with(['details', 'roles'])->find($id);
    $canEdit = auth()->user()->hasAnyRole(['super_admin', 'admin_editor']);
    $currentRole = $admin?->roles->first()?->name ?? 'admin_viewer';
    $roleBadge = ucwords(str_replace('_', ' ', $currentRole));
@endphp

<div class="flex-1 md:ml-64 p-6 pt-20">
    <div class="container mx-auto py-6 px-4 max-w-8xl">

        <!-- Breadcrumb -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.admins') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-green-600">
                        <i class="fas fa-home mr-2"></i>
                        Administrators
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                        <span class="text-sm font-medium text-gray-500">Admin Details</span>
                    </div>
                </li>
            </ol>
        </nav>

        @if($admin)
        <!-- Profile Header -->
        <div class="mb-5 bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                    <!-- Profile Section -->
                    <div class="flex items-start gap-4 min-w-0 flex-1">
                        <!-- Avatar -->
                        <div class="relative flex-shrink-0">
                            <div class="h-20 w-20 rounded-full bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center text-green-700 text-xl font-semibold ring-2 ring-gray-100">
                                <span>{{ strtoupper(substr($admin->details?->first_name ?? $admin->email, 0, 1)) }}{{ strtoupper(substr($admin->details?->last_name ?? '', 0, 1)) }}</span>
                            </div>
                            @if($admin->is_active)
                            <div class="absolute -bottom-0.5 -right-0.5">
                                <span class="relative flex h-4 w-4">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-4 w-4 bg-green-500 ring-2 ring-white"></span>
                                </span>
                            </div>
                            @endif
                        </div>

                        <!-- Profile Information -->
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <h1 class="text-2xl font-semibold text-gray-900 truncate">{{ $admin->details?->full_name ?? 'Admin User' }}</h1>
                            </div>

                            <!-- Metadata Row -->
                            <div class="flex flex-wrap items-center gap-2 mt-2">
                                <!-- Role Badge -->
                                <x-ui.badge :label="$roleBadge" />

                                <!-- Status Badge -->
                                <x-ui.badge :label="$admin->is_active ? 'Active' : 'Inactive'" />

                                <!-- CLSU ID Badge -->
                                @if($admin->details?->clsu_id)
                                <span class="inline-flex items-center gap-1.5 bg-gray-50 text-gray-700 text-xs px-2.5 py-1 rounded-md font-medium border border-gray-200">
                                    <svg class="h-3.5 w-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                    </svg>
                                    {{ $admin->details->clsu_id }}
                                </span>
                                @endif

                                <!-- Email -->
                                <span class="inline-flex items-center gap-1.5 text-xs text-gray-500">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    {{ $admin->email }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <a href="{{ route('admin.admins') }}"
                           class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back
                        </a>
                        @if($canEdit)
                        <button
                            onclick="Livewire.dispatch('openEditAdminModal', { id: '{{ $admin->id }}' })"
                            class="inline-flex items-center px-3 py-1.5 border border-green-600 text-sm font-medium rounded-md text-green-600 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Main Information -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Personal Information Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden transition-all duration-300 hover:shadow-md">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-800">Personal Information</h2>
                        @if($canEdit)
                        <button
                            onclick="Livewire.dispatch('openEditAdminModal', { id: '{{ $admin->id }}' })"
                            class="inline-flex items-center px-3 py-1.5 border border-green-600 text-sm font-medium rounded-md text-green-600 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </button>
                        @endif
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-sm font-medium text-gray-500 mb-1 block">First Name</label>
                                <p class="text-sm text-gray-900 py-1 px-0.5">{{ $admin->details?->first_name ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500 mb-1 block">Last Name</label>
                                <p class="text-sm text-gray-900 py-1 px-0.5">{{ $admin->details?->last_name ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500 mb-1 block">Middle Name</label>
                                <p class="text-sm text-gray-900 py-1 px-0.5">{{ $admin->details?->middle_name ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500 mb-1 block">Suffix</label>
                                <p class="text-sm text-gray-900 py-1 px-0.5">{{ $admin->details?->suffix ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden transition-all duration-300 hover:shadow-md">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-800">Contact Information</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-sm font-medium text-gray-500 mb-1 block">Email Address</label>
                                <p class="text-sm text-gray-900 py-1 px-0.5">{{ $admin->email }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500 mb-1 block">CLSU ID</label>
                                <p class="text-sm text-gray-900 py-1 px-0.5">{{ $admin->details?->clsu_id ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500 mb-1 block">Phone Number</label>
                                <p class="text-sm text-gray-900 py-1 px-0.5">{{ $admin->details?->phone_number ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Column - Status & Activity -->
            <div class="space-y-6">

                <!-- Account Status Card -->
                <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden transition-all duration-300 hover:shadow-lg">
                    <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-white to-gray-50">
                        <h2 class="text-xl font-bold text-gray-800">Account Status</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <!-- Status -->
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-500">Status</span>
                            <x-ui.badge :label="$admin->is_active ? 'Active' : 'Inactive'" />
                        </div>

                        <!-- Role -->
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-500">Role</span>
                            <x-ui.badge :label="$roleBadge" />
                        </div>

                        <!-- Password Setup -->
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-500">Password</span>
                            @if($admin->must_change_password ?? false)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100/80 text-yellow-800">
                                Pending Setup
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100/80 text-green-800">
                                Configured
                            </span>
                            @endif
                        </div>

                        <!-- View Only Indicator -->
                        @if(auth()->user()->hasRole('admin_viewer'))
                        <div class="pt-3 mt-2 border-t border-gray-100">
                            <span class="inline-flex items-center gap-1.5 text-xs text-gray-500">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                View Only Access
                            </span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Activity Log Card -->
                <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden transition-all duration-300 hover:shadow-lg">
                    <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-white to-gray-50">
                        <h2 class="text-xl font-bold text-gray-800">Activity</h2>
                    </div>
                    <div class="p-6">
                        <div class="relative">
                            <!-- Timeline line -->
                            <div class="absolute left-4 top-6 bottom-0 w-px bg-gray-200"></div>

                            <div class="space-y-6">
                                <!-- Created -->
                                <div class="relative flex gap-4">
                                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green-100 flex items-center justify-center z-10">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Account Created</p>
                                        <p class="text-xs text-gray-500">{{ $admin->created_at->format('M d, Y \\a\\t h:i A') }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $admin->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>

                                <!-- Last Updated -->
                                @if($admin->updated_at && !$admin->updated_at->eq($admin->created_at))
                                <div class="relative flex gap-4">
                                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center z-10">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Last Updated</p>
                                        <p class="text-xs text-gray-500">{{ $admin->updated_at->format('M d, Y \\a\\t h:i A') }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $admin->updated_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        @else
        <!-- Admin Not Found -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Admin Not Found</h3>
            <p class="text-gray-500 mb-4">The requested admin account could not be found.</p>
            <a href="{{ route('admin.admins') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                Back to Admin List
            </a>
        </div>
        @endif

        {{-- Edit Admin Modal --}}
        @if($canEdit)
            @livewire('admin.admins.edit-admin-modal')
        @endif
    </div>
</div>

<x-footer.footer></x-footer.footer>
@endsection
