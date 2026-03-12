@extends('layouts.app-layout')
@section('main-content')

<div class="flex-1 md:ml-64 p-4 sm:p-6 pt-20 sm:pt-24 bg-gray-50 min-h-screen">
    <div class="max-w-5xl mx-auto">
        <!-- Breadcrumb -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 flex-wrap">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.admins') }}" class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-green-600 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Administrators
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500">Profile</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Admin Details Card -->
        @php
            $admin = \App\Models\User::with(['details', 'roles'])->find($id);
            $roleColors = [
                'super_admin' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'ring' => 'ring-purple-500/30', 'gradient' => 'from-purple-500 to-purple-600'],
                'admin_editor' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'ring' => 'ring-blue-500/30', 'gradient' => 'from-blue-500 to-blue-600'],
                'admin_viewer' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'ring' => 'ring-gray-500/30', 'gradient' => 'from-gray-500 to-gray-600'],
            ];
            $currentRole = $admin?->roles->first()?->name ?? 'admin_viewer';
            $roleStyle = $roleColors[$currentRole] ?? $roleColors['admin_viewer'];
        @endphp

        @if($admin)
        <div class="space-y-6">
            <!-- Profile Header Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden transition-all duration-300 hover:shadow-md">
                <!-- Gradient Banner -->
                <div class="h-32 sm:h-40 bg-gradient-to-br {{ $roleStyle['gradient'] }} relative">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="absolute bottom-0 left-0 right-0 h-20 bg-gradient-to-t from-black/20 to-transparent"></div>
                </div>

                <!-- Profile Info -->
                <div class="relative px-4 sm:px-8 pb-6">
                    <!-- Avatar -->
                    <div class="absolute -top-12 sm:-top-16 left-4 sm:left-8">
                        <div class="h-24 w-24 sm:h-32 sm:w-32 rounded-2xl bg-white shadow-lg flex items-center justify-center text-3xl sm:text-4xl font-bold {{ $roleStyle['text'] }} {{ $roleStyle['bg'] }} ring-4 ring-white">
                            {{ strtoupper(substr($admin->details?->first_name ?? $admin->email, 0, 1)) }}{{ strtoupper(substr($admin->details?->last_name ?? '', 0, 1)) }}
                        </div>
                    </div>

                    <!-- Name and Actions -->
                    <div class="pt-14 sm:pt-20 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                        <div class="space-y-2">
                            <div class="flex flex-wrap items-center gap-3">
                                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                                    {{ $admin->details?->full_name ?? 'Admin User' }}
                                </h1>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $roleStyle['bg'] }} {{ $roleStyle['text'] }} ring-1 {{ $roleStyle['ring'] }}">
                                    @if($currentRole === 'super_admin')
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M9.243 3.03a1 1 0 01.727 1.213L9.53 6h2.94l.56-2.243a1 1 0 111.94.486L14.53 6H17a1 1 0 110 2h-2.97l-1 4H15a1 1 0 110 2h-2.47l-.56 2.242a1 1 0 11-1.94-.485L10.47 14H7.53l-.56 2.242a1 1 0 11-1.94-.485L5.47 14H3a1 1 0 110-2h2.97l1-4H5a1 1 0 110-2h2.47l.56-2.243a1 1 0 011.213-.727zM9.03 8l-1 4h2.938l1-4H9.031z" clip-rule="evenodd" />
                                    </svg>
                                    @endif
                                    {{ ucwords(str_replace('_', ' ', $currentRole)) }}
                                </span>
                            </div>
                            <p class="text-gray-500 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                {{ $admin->email }}
                            </p>
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('admin.admins') }}"
                               class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Back to List
                            </a>
                            @if(auth()->user()->hasAnyRole(['super_admin', 'admin_editor']))
                            <button
                                onclick="Livewire.dispatch('openEditAdminModal', { id: '{{ $admin->id }}' })"
                                class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-green-600 to-green-500 rounded-xl hover:from-green-700 hover:to-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-sm hover:shadow transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit Profile
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Details Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Account Information -->
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Account Information
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- First Name -->
                            <div class="space-y-1">
                                <label class="flex items-center gap-1.5 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    First Name
                                </label>
                                <p class="text-gray-900 font-medium">{{ $admin->details?->first_name ?? '-' }}</p>
                            </div>

                            <!-- Last Name -->
                            <div class="space-y-1">
                                <label class="flex items-center gap-1.5 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    Last Name
                                </label>
                                <p class="text-gray-900 font-medium">{{ $admin->details?->last_name ?? '-' }}</p>
                            </div>

                            <!-- Email -->
                            <div class="space-y-1">
                                <label class="flex items-center gap-1.5 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    Email Address
                                </label>
                                <p class="text-gray-900 font-medium break-all">{{ $admin->email }}</p>
                            </div>

                            <!-- CLSU ID -->
                            <div class="space-y-1">
                                <label class="flex items-center gap-1.5 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                    </svg>
                                    CLSU ID
                                </label>
                                <p class="text-gray-900 font-medium">{{ $admin->details?->clsu_id ?? '-' }}</p>
                            </div>

                            <!-- Phone Number -->
                            <div class="space-y-1">
                                <label class="flex items-center gap-1.5 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    Phone Number
                                </label>
                                <p class="text-gray-900 font-medium">{{ $admin->details?->phone_number ?? '-' }}</p>
                            </div>

                            <!-- Middle Name -->
                            <div class="space-y-1">
                                <label class="flex items-center gap-1.5 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    Middle Name
                                </label>
                                <p class="text-gray-900 font-medium">{{ $admin->details?->middle_name ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status & Meta Card -->
                <div class="space-y-6">
                    <!-- Account Status -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                Status
                            </h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <!-- Active Status -->
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Account Status</span>
                                @if($admin->is_active)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 ring-1 ring-green-500/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                    Active
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 ring-1 ring-red-500/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                    Inactive
                                </span>
                                @endif
                            </div>

                            <!-- Password Status -->
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Password Setup</span>
                                @if($admin->must_change_password ?? false)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 ring-1 ring-amber-500/20">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    Pending
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 ring-1 ring-green-500/20">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    Complete
                                </span>
                                @endif
                            </div>

                            <!-- View Only Indicator -->
                            @if(auth()->user()->hasRole('admin_viewer'))
                            <div class="pt-3 mt-3 border-t border-gray-100">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-gray-100 text-gray-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    View Only Access
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Account Timeline -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Timeline
                            </h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Account Created</p>
                                    <p class="text-xs text-gray-500">{{ $admin->created_at->format('F d, Y') }}</p>
                                    <p class="text-xs text-gray-400">{{ $admin->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            @if($admin->updated_at && $admin->updated_at->ne($admin->created_at))
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Last Updated</p>
                                    <p class="text-xs text-gray-500">{{ $admin->updated_at->format('F d, Y') }}</p>
                                    <p class="text-xs text-gray-400">{{ $admin->updated_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- Admin Not Found State -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 sm:p-12 text-center">
            <div class="max-w-md mx-auto">
                <div class="w-20 h-20 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Administrator Not Found</h3>
                <p class="text-gray-500 mb-6">The requested administrator account could not be found. It may have been deleted or the link may be incorrect.</p>
                <a href="{{ route('admin.admins') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-green-600 to-green-500 rounded-xl hover:from-green-700 hover:to-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-sm hover:shadow transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Admin List
                </a>
            </div>
        </div>
        @endif

        {{-- Edit Admin Modal - only load if user can manage admins --}}
        @if(auth()->user()->hasAnyRole(['super_admin', 'admin_editor']))
            @livewire('admin.admins.edit-admin-modal')
        @endif
    </div>
</div>

<x-footer.footer></x-footer.footer>
@endsection
