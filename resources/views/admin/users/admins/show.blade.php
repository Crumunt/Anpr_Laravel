@extends('layouts.app-layout')
@section('main-content')

<div class="flex-1 md:ml-64 p-6 pt-24">
    <div class="max-w-4xl mx-auto">
        <!-- Breadcrumb -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.admins') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-green-600">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                        Admins
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Admin Details</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Admin Details Card -->
        @php
            $admin = \App\Models\User::with(['details', 'roles'])->find($id);
        @endphp

        @if($admin)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                <div class="flex items-center gap-4">
                    <div class="h-16 w-16 rounded-full bg-gradient-to-br from-purple-100 to-purple-200 flex items-center justify-center text-purple-700 text-xl font-semibold">
                        {{ strtoupper(substr($admin->details?->first_name ?? $admin->email, 0, 1)) }}{{ strtoupper(substr($admin->details?->last_name ?? '', 0, 1)) }}
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $admin->details?->full_name ?? 'Admin User' }}</h1>
                        <p class="text-gray-600">{{ $admin->email }}</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 mt-2 rounded-full text-xs font-medium {{ $admin->roles->first()?->name === 'super_admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ ucwords(str_replace('_', ' ', $admin->roles->first()?->name ?? 'Admin')) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Details -->
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Email Address</label>
                        <p class="text-gray-900">{{ $admin->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">CLSU ID</label>
                        <p class="text-gray-900">{{ $admin->details?->clsu_id ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Phone Number</label>
                        <p class="text-gray-900">{{ $admin->details?->phone_number ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Account Created</label>
                        <p class="text-gray-900">{{ $admin->created_at->format('F d, Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $admin->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $admin->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="p-6 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                <a href="{{ route('admin.admins') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Back to List
                </a>
                <button class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                    Edit Admin
                </button>
            </div>
        </div>
        @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Admin Not Found</h3>
            <p class="text-gray-500 mb-4">The requested admin account could not be found.</p>
            <a href="{{ route('admin.admins') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                Back to Admin List
            </a>
        </div>
        @endif
    </div>
</div>

<x-footer.footer></x-footer.footer>
@endsection
