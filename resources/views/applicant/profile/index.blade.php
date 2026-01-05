@extends('layouts.applicant')

@section('title', 'My Profile')

@section('content')
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">My Profile</h1>
        <p class="text-gray-500 mt-1">Manage your account settings and preferences.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Profile Information Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800">Profile Information</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center space-x-6 mb-6">
                        <div class="w-20 h-20 bg-emerald-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                            {{ $user->name_initial ?? 'U' }}
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-gray-900">
                                {{ $user->details?->first_name }} {{ $user->details?->middle_name ?? '' }} {{ $user->details?->last_name }}
                            </h4>
                            <p class="text-gray-500">{{ $user->email }}</p>
                            @if($user->details?->clsu_id)
                                <p class="text-sm text-emerald-600 font-medium mt-1">
                                    <i class="fas fa-id-badge mr-1"></i> {{ $user->details->clsu_id }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-500 mb-1">First Name</p>
                            <p class="font-medium text-gray-900">{{ $user->details?->first_name ?? 'N/A' }}</p>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-500 mb-1">Last Name</p>
                            <p class="font-medium text-gray-900">{{ $user->details?->last_name ?? 'N/A' }}</p>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-500 mb-1">Email Address</p>
                            <p class="font-medium text-gray-900">{{ $user->email }}</p>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-500 mb-1">Phone Number</p>
                            <p class="font-medium text-gray-900">{{ $user->phone_number ?? 'N/A' }}</p>
                        </div>
                        @if($user->details?->license_number)
                            <div class="p-3 bg-gray-50 rounded-lg md:col-span-2">
                                <p class="text-xs text-gray-500 mb-1">Driver's License</p>
                                <p class="font-medium text-gray-900">{{ $user->details->license_number }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Gate Pass Statistics -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800">Gate Pass Statistics</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <p class="text-3xl font-bold text-blue-600">{{ $gatePassStats['total'] }}</p>
                            <p class="text-sm text-gray-500">Total Vehicles</p>
                        </div>
                        <div class="text-center p-4 bg-emerald-50 rounded-lg">
                            <p class="text-3xl font-bold text-emerald-600">{{ $gatePassStats['active'] }}</p>
                            <p class="text-sm text-gray-500">Active</p>
                        </div>
                        <div class="text-center p-4 bg-amber-50 rounded-lg">
                            <p class="text-3xl font-bold text-amber-600">{{ $gatePassStats['pending'] }}</p>
                            <p class="text-sm text-gray-500">Pending</p>
                        </div>
                        <div class="text-center p-4 bg-red-50 rounded-lg">
                            <p class="text-3xl font-bold text-red-600">{{ $gatePassStats['expired'] }}</p>
                            <p class="text-sm text-gray-500">Expired</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Change Password Form -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800">Change Password</h3>
                </div>
                <form action="{{ route('applicant.profile.password.update') }}" method="POST" class="p-6">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                            <input type="password" name="current_password" id="current_password" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                        </div>
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                            <input type="password" name="password" id="password" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                        </div>
                        <div class="pt-2">
                            <button type="submit" class="px-6 py-2 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 transition-colors">
                                Update Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Account Status -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800">Account Status</h3>
                </div>
                <div class="p-4 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Status</span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $accountStatus['status_class'] }}">
                            {{ $accountStatus['status'] }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Email Verified</span>
                        @if($accountStatus['email_verified'])
                            <span class="text-emerald-600"><i class="fas fa-check-circle"></i></span>
                        @else
                            <span class="text-red-500"><i class="fas fa-times-circle"></i></span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Member Since</span>
                        <span class="text-sm font-medium text-gray-900">{{ $accountStatus['created_at']->format('M d, Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Last Activity</span>
                        <span class="text-sm font-medium text-gray-900">{{ $accountStatus['last_activity']?->format('M d, Y') ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Usage Statistics -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800">Usage Statistics</h3>
                </div>
                <div class="p-4 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Total Logins</span>
                        <span class="text-sm font-medium text-gray-900">{{ $usageStats['total_logins'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Total Actions</span>
                        <span class="text-sm font-medium text-gray-900">{{ $usageStats['total_actions'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Actions (30 days)</span>
                        <span class="text-sm font-medium text-gray-900">{{ $usageStats['last_30_days_actions'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Most Used Feature</span>
                        <span class="text-sm font-medium text-gray-900">{{ $usageStats['most_used_feature'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800">Quick Links</h3>
                </div>
                <div class="p-4 space-y-2">
                    <a href="{{ route('applicant.vehicles') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-car w-5 text-gray-500"></i>
                        <span class="ml-3 text-sm font-medium text-gray-700">My Vehicles</span>
                    </a>
                    <a href="{{ route('applicant.activity-log') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-history w-5 text-gray-500"></i>
                        <span class="ml-3 text-sm font-medium text-gray-700">Activity Log</span>
                    </a>
                    <a href="{{ route('gate-pass.gate-pass-applicant-form') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-plus w-5 text-gray-500"></i>
                        <span class="ml-3 text-sm font-medium text-gray-700">Register Vehicle</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
