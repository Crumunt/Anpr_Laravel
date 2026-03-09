@extends('layouts.applicant')

@section('title', 'My Profile')

@section('content')
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6" aria-label="Breadcrumb">
        <a href="{{ route('applicant.dashboard') }}" class="hover:text-green-700 transition-colors">Dashboard</a>
        <i class="fas fa-chevron-right text-[10px] text-gray-300"></i>
        <span class="text-gray-900 font-medium">My Profile</span>
    </nav>

    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">My Profile</h1>
        <p class="text-gray-500 mt-1">Manage your account settings and review your information.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Profile Card -->
            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-user-circle text-gray-400 text-sm"></i>
                        Profile Information
                    </h3>
                </div>
                <div class="p-6">
                    <!-- Profile Header -->
                    <div class="flex items-center gap-5 mb-6 pb-6 border-b border-gray-100">
                        <div class="w-16 h-16 bg-[#1a5c1f] rounded-2xl flex items-center justify-center text-white text-2xl font-bold shadow-sm">
                            {{ $user->name_initial ?? 'U' }}
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-gray-900">
                                {{ $user->details?->first_name }} {{ $user->details?->middle_name ?? '' }} {{ $user->details?->last_name }}
                            </h4>
                            <p class="text-gray-500 text-sm mt-0.5">{{ $user->email }}</p>
                            @if($user->details?->clsu_id)
                                <span class="inline-flex items-center gap-1.5 mt-1.5 text-xs font-semibold text-green-700 bg-green-50 px-2.5 py-0.5 rounded-lg border border-green-100">
                                    <i class="fas fa-id-badge text-[10px]"></i> {{ $user->details->clsu_id }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Profile Details Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="flex items-center gap-3 p-3.5 bg-gray-50/80 rounded-xl">
                            <div class="w-9 h-9 bg-white rounded-lg flex items-center justify-center border border-gray-100 flex-shrink-0">
                                <i class="fas fa-user text-gray-400 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide">First Name</p>
                                <p class="font-semibold text-gray-900 text-sm">{{ $user->details?->first_name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-3.5 bg-gray-50/80 rounded-xl">
                            <div class="w-9 h-9 bg-white rounded-lg flex items-center justify-center border border-gray-100 flex-shrink-0">
                                <i class="fas fa-user text-gray-400 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Last Name</p>
                                <p class="font-semibold text-gray-900 text-sm">{{ $user->details?->last_name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-3.5 bg-gray-50/80 rounded-xl">
                            <div class="w-9 h-9 bg-white rounded-lg flex items-center justify-center border border-gray-100 flex-shrink-0">
                                <i class="fas fa-envelope text-gray-400 text-xs"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Email Address</p>
                                <p class="font-semibold text-gray-900 text-sm truncate">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-3.5 bg-gray-50/80 rounded-xl">
                            <div class="w-9 h-9 bg-white rounded-lg flex items-center justify-center border border-gray-100 flex-shrink-0">
                                <i class="fas fa-phone text-gray-400 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Phone Number</p>
                                <p class="font-semibold text-gray-900 text-sm">{{ $user->phone_number ?? 'N/A' }}</p>
                            </div>
                        </div>
                        @if($user->details?->license_number)
                            <div class="flex items-center gap-3 p-3.5 bg-gray-50/80 rounded-xl sm:col-span-2">
                                <div class="w-9 h-9 bg-white rounded-lg flex items-center justify-center border border-gray-100 flex-shrink-0">
                                    <i class="fas fa-id-card text-gray-400 text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Driver's License</p>
                                    <p class="font-semibold text-gray-900 text-sm font-mono">{{ $user->details->license_number }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Gate Pass Statistics -->
            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-chart-bar text-gray-400 text-sm"></i>
                        Gate Pass Statistics
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div class="text-center p-4 bg-blue-50/80 rounded-xl border border-blue-100 relative overflow-hidden">
                            <div class="absolute top-0 left-0 right-0 h-1 bg-blue-500"></div>
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-car text-blue-600 text-xs"></i>
                            </div>
                            <p class="text-2xl font-extrabold text-blue-700 tabular-nums">{{ $gatePassStats['total'] }}</p>
                            <p class="text-xs font-medium text-gray-500 mt-1">Total</p>
                        </div>
                        <div class="text-center p-4 bg-green-50/80 rounded-xl border border-green-100 relative overflow-hidden">
                            <div class="absolute top-0 left-0 right-0 h-1 bg-green-500"></div>
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-circle-check text-green-600 text-xs"></i>
                            </div>
                            <p class="text-2xl font-extrabold text-green-700 tabular-nums">{{ $gatePassStats['active'] }}</p>
                            <p class="text-xs font-medium text-gray-500 mt-1">Active</p>
                        </div>
                        <div class="text-center p-4 bg-amber-50/80 rounded-xl border border-amber-100 relative overflow-hidden">
                            <div class="absolute top-0 left-0 right-0 h-1 bg-amber-500"></div>
                            <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-hourglass-half text-amber-600 text-xs"></i>
                            </div>
                            <p class="text-2xl font-extrabold text-amber-700 tabular-nums">{{ $gatePassStats['pending'] }}</p>
                            <p class="text-xs font-medium text-gray-500 mt-1">Pending</p>
                        </div>
                        <div class="text-center p-4 bg-red-50/80 rounded-xl border border-red-100 relative overflow-hidden">
                            <div class="absolute top-0 left-0 right-0 h-1 bg-red-500"></div>
                            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-clock text-red-600 text-xs"></i>
                            </div>
                            <p class="text-2xl font-extrabold text-red-700 tabular-nums">{{ $gatePassStats['expired'] }}</p>
                            <p class="text-xs font-medium text-gray-500 mt-1">Expired</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Change Password -->
            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-lock text-gray-400 text-sm"></i>
                        Change Password
                    </h3>
                </div>
                <form action="{{ route('applicant.profile.password.update') }}" method="POST" class="p-6">
                    @csrf
                    <div class="space-y-4 max-w-lg">
                        <div>
                            <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-1.5">Current Password</label>
                            <input type="password" name="current_password" id="current_password" required
                                   class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm transition-colors">
                        </div>
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">New Password</label>
                            <input type="password" name="password" id="password" required
                                   class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm transition-colors">
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1.5">Confirm New Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                   class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm transition-colors">
                        </div>
                        <div class="pt-2">
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#1a5c1f] text-white text-sm font-semibold rounded-xl shadow-sm hover:bg-green-800 transition-colors focus-ring">
                                <i class="fas fa-check text-xs"></i>
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
            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-base font-bold text-gray-900">Account Status</h3>
                </div>
                <div class="p-4 space-y-3">
                    <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50/80">
                        <span class="text-sm text-gray-500 font-medium">Status</span>
                        <span class="px-2.5 py-0.5 rounded-lg text-xs font-semibold {{ $accountStatus['status_class'] }}">
                            {{ $accountStatus['status'] }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50/80">
                        <span class="text-sm text-gray-500 font-medium">Email Verified</span>
                        @if($accountStatus['email_verified'])
                            <span class="inline-flex items-center gap-1 text-green-600 text-xs font-semibold">
                                <i class="fas fa-check-circle"></i> Verified
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 text-red-500 text-xs font-semibold">
                                <i class="fas fa-times-circle"></i> Not Verified
                            </span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50/80">
                        <span class="text-sm text-gray-500 font-medium">Member Since</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $accountStatus['created_at']->format('M d, Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50/80">
                        <span class="text-sm text-gray-500 font-medium">Last Activity</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $accountStatus['last_activity']?->format('M d, Y') ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Usage Statistics -->
            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-base font-bold text-gray-900">Usage Statistics</h3>
                </div>
                <div class="p-4 space-y-3">
                    <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50/80">
                        <span class="text-sm text-gray-500 font-medium">Total Logins</span>
                        <span class="text-sm font-bold text-gray-900 tabular-nums">{{ $usageStats['total_logins'] }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50/80">
                        <span class="text-sm text-gray-500 font-medium">Total Actions</span>
                        <span class="text-sm font-bold text-gray-900 tabular-nums">{{ $usageStats['total_actions'] }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50/80">
                        <span class="text-sm text-gray-500 font-medium">Actions (30 days)</span>
                        <span class="text-sm font-bold text-gray-900 tabular-nums">{{ $usageStats['last_30_days_actions'] }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50/80">
                        <span class="text-sm text-gray-500 font-medium">Most Used</span>
                        <span class="text-sm font-bold text-gray-900">{{ $usageStats['most_used_feature'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-base font-bold text-gray-900">Quick Links</h3>
                </div>
                <div class="p-3 space-y-1">
                    <a href="{{ route('applicant.vehicles') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors group">
                        <div class="w-9 h-9 bg-gray-100 rounded-lg flex items-center justify-center group-hover:bg-green-50 transition-colors">
                            <i class="fas fa-car text-gray-500 group-hover:text-green-600 text-sm transition-colors"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900 transition-colors">My Vehicles</span>
                    </a>
                    <a href="{{ route('applicant.activity-log') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors group">
                        <div class="w-9 h-9 bg-gray-100 rounded-lg flex items-center justify-center group-hover:bg-green-50 transition-colors">
                            <i class="fas fa-clock-rotate-left text-gray-500 group-hover:text-green-600 text-sm transition-colors"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900 transition-colors">Activity Log</span>
                    </a>
                    <a href="{{ route('gate-pass.gate-pass-applicant-form') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors group">
                        <div class="w-9 h-9 bg-gray-100 rounded-lg flex items-center justify-center group-hover:bg-green-50 transition-colors">
                            <i class="fas fa-plus text-gray-500 group-hover:text-green-600 text-sm transition-colors"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900 transition-colors">Register Vehicle</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
