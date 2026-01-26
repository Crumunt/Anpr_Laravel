<div>
    @if($user)
    <!-- Enhanced Page Header -->
    <div class="mb-8 lg:mb-10">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 tracking-tight mb-2">My Profile</h1>
        <p class="text-base text-gray-600 leading-relaxed">Manage your account settings and preferences</p>
    </div>

    <div class="flex flex-col lg:flex-row gap-6 lg:gap-8">
        <!-- Enhanced Sidebar Navigation -->
        <div class="lg:w-72 flex-shrink-0">
            <nav class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden sticky top-24" aria-label="Profile navigation">
                <!-- User Profile Summary -->
                <div class="p-5 border-b border-gray-200 bg-gradient-to-br from-emerald-50 via-emerald-50/50 to-white">
                    <div class="flex items-center gap-4">
                        <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white font-bold text-xl shadow-lg ring-4 ring-emerald-100">
                            {{ auth()->user()->name_initial ?? 'U' }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-bold text-gray-900 truncate mb-1">{{ $displayName }}</p>
                            <p class="text-xs text-gray-600 truncate mb-2">{{ $user->email }}</p>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                Applicant
                            </span>
                        </div>
                    </div>
                </div>

                <ul class="p-2 space-y-1" role="tablist">
                    <li>
                        <button wire:click="setTab('profile')"
                            role="tab"
                            aria-selected="{{ $activeTab === 'profile' ? 'true' : 'false' }}"
                            aria-controls="profile-panel"
                            class="w-full flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 {{ $activeTab === 'profile' ? 'bg-gradient-to-r from-emerald-50 to-emerald-100/50 text-emerald-700 shadow-sm border border-emerald-200/50' : 'text-gray-700 hover:bg-gray-50' }}">
                            <i class="fas fa-user w-5 text-center"></i>
                            <span>Profile</span>
                        </button>
                    </li>
                    <li>
                        <button wire:click="setTab('security')"
                            role="tab"
                            aria-selected="{{ $activeTab === 'security' ? 'true' : 'false' }}"
                            aria-controls="security-panel"
                            class="w-full flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 {{ $activeTab === 'security' ? 'bg-gradient-to-r from-emerald-50 to-emerald-100/50 text-emerald-700 shadow-sm border border-emerald-200/50' : 'text-gray-700 hover:bg-gray-50' }}">
                            <i class="fas fa-lock w-5 text-center"></i>
                            <span>Security</span>
                        </button>
                    </li>
                </ul>

                <!-- Account Info -->
                <div class="p-4 border-t border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                    <p class="text-xs font-medium text-gray-500">
                        <i class="fas fa-calendar-alt mr-1.5"></i>
                        Member since {{ $memberSince }}
                    </p>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 space-y-6">
            <!-- Profile Tab -->
            @if($activeTab === 'profile')
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden" role="tabpanel" id="profile-panel" aria-labelledby="profile-tab">
                <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                    <h3 class="text-xl font-bold text-gray-900">Profile Information</h3>
                    <p class="text-sm text-gray-600 mt-1.5">Update your email address and view your profile details</p>
                </div>
                <div class="p-6 lg:p-8 space-y-8">
                    <!-- User Details (Read-only) -->
                    @if($user->details)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6 pb-8 border-b border-gray-200">
                        <div class="p-4 bg-gradient-to-br from-gray-50 to-gray-100/50 rounded-xl border border-gray-200">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Full Name</label>
                            <p class="text-base font-semibold text-gray-900">{{ $user->details->full_name ?? 'Not set' }}</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-gray-50 to-gray-100/50 rounded-xl border border-gray-200">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">CLSU ID</label>
                            <p class="text-base font-semibold text-gray-900 font-mono">{{ $user->details->clsu_id ?? 'Not set' }}</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-gray-50 to-gray-100/50 rounded-xl border border-gray-200">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Phone Number</label>
                            <p class="text-base font-semibold text-gray-900">{{ $user->details->phone_number ?? 'Not set' }}</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-gray-50 to-gray-100/50 rounded-xl border border-gray-200">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Account Type</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                Applicant
                            </span>
                        </div>
                    </div>
                    @endif

                    <!-- Email Address with enhanced form design -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-900 mb-2">
                            Email Address
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <div class="flex-1 relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                                <input
                                    type="email"
                                    id="email"
                                    wire:model.blur="email"
                                    autocomplete="email"
                                    class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 @error('email') border-red-500 focus:ring-red-500 @enderror"
                                    placeholder="your.email@example.com">
                            </div>
                            @if($email !== $currentEmail)
                            <button
                                wire:click="updateEmail"
                                wire:loading.attr="disabled"
                                class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white text-sm font-semibold rounded-xl hover:from-emerald-700 hover:to-emerald-800 shadow-md hover:shadow-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                <span wire:loading.remove wire:target="updateEmail">
                                    <i class="fas fa-save mr-1.5"></i>Save Changes
                                </span>
                                <span wire:loading wire:target="updateEmail" class="flex items-center gap-2">
                                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Saving...
                                </span>
                            </button>
                            @endif
                        </div>
                        @error('email') 
                            <p class="mt-2 flex items-center gap-2 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p> 
                        @enderror

                        <!-- Email Verification Status -->
                        <div class="mt-4 p-4 rounded-xl border {{ $emailVerified ? 'bg-emerald-50 border-emerald-200' : 'bg-amber-50 border-amber-200' }}">
                            <div class="flex items-center gap-3">
                                @if($emailVerified)
                                    <div class="flex-shrink-0 w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-check-circle text-emerald-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-emerald-900">Email Verified</p>
                                        <p class="text-xs text-emerald-700 mt-0.5">Your email address has been verified</p>
                                    </div>
                                @else
                                    <div class="flex-shrink-0 w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-exclamation-triangle text-amber-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-amber-900">Email Not Verified</p>
                                        <p class="text-xs text-amber-700 mt-0.5 mb-2">Please verify your email address to ensure you receive important notifications</p>
                                        <button
                                            wire:click="sendVerificationEmail"
                                            wire:loading.attr="disabled"
                                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-emerald-700 bg-emerald-100 rounded-lg hover:bg-emerald-200 transition-colors disabled:opacity-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                            <span wire:loading.remove wire:target="sendVerificationEmail">
                                                <i class="fas fa-paper-plane mr-1"></i>Send Verification Email
                                            </span>
                                            <span wire:loading wire:target="sendVerificationEmail" class="flex items-center gap-2">
                                                <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                Sending...
                                            </span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Security Tab -->
            @if($activeTab === 'security')
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden" role="tabpanel" id="security-panel" aria-labelledby="security-tab">
                <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                    <h3 class="text-xl font-bold text-gray-900">Change Password</h3>
                    <p class="text-sm text-gray-600 mt-1.5">Ensure your account is using a secure password</p>
                </div>
                <div class="p-6 lg:p-8 space-y-6">
                    <div>
                        <label for="currentPassword" class="block text-sm font-semibold text-gray-900 mb-2">
                            Current Password
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input
                                type="password"
                                id="currentPassword"
                                wire:model.blur="currentPassword"
                                autocomplete="current-password"
                                class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 @error('currentPassword') border-red-500 focus:ring-red-500 @enderror"
                                placeholder="Enter your current password">
                        </div>
                        @error('currentPassword') 
                            <p class="mt-2 flex items-center gap-2 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p> 
                        @enderror
                    </div>

                    <div>
                        <label for="newPassword" class="block text-sm font-semibold text-gray-900 mb-2">
                            New Password
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-key text-gray-400"></i>
                            </div>
                            <input
                                type="password"
                                id="newPassword"
                                wire:model.blur="newPassword"
                                autocomplete="new-password"
                                class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 @error('newPassword') border-red-500 focus:ring-red-500 @enderror"
                                placeholder="Enter your new password">
                        </div>
                        @error('newPassword') 
                            <p class="mt-2 flex items-center gap-2 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p> 
                        @enderror
                    </div>

                    <div>
                        <label for="newPasswordConfirmation" class="block text-sm font-semibold text-gray-900 mb-2">
                            Confirm New Password
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-check-circle text-gray-400"></i>
                            </div>
                            <input
                                type="password"
                                id="newPasswordConfirmation"
                                wire:model.blur="newPasswordConfirmation"
                                autocomplete="new-password"
                                class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 @error('newPasswordConfirmation') border-red-500 focus:ring-red-500 @enderror"
                                placeholder="Confirm your new password">
                        </div>
                        @error('newPasswordConfirmation') 
                            <p class="mt-2 flex items-center gap-2 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p> 
                        @enderror
                    </div>

                    <div class="pt-4 border-t border-gray-200">
                        <button
                            wire:click="updatePassword"
                            wire:loading.attr="disabled"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white text-sm font-semibold rounded-xl hover:from-emerald-700 hover:to-emerald-800 shadow-md hover:shadow-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                            <span wire:loading.remove wire:target="updatePassword">
                                <i class="fas fa-save mr-1.5"></i>Update Password
                            </span>
                            <span wire:loading wire:target="updatePassword" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Updating...
                            </span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Enhanced Password Tips -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl p-5 shadow-sm">
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-info-circle text-blue-600"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-bold text-blue-900 mb-2">Password Requirements</h4>
                        <ul class="space-y-2 text-sm text-blue-800">
                            <li class="flex items-start gap-2">
                                <i class="fas fa-check-circle text-blue-600 mt-0.5 text-xs"></i>
                                <span>At least 8 characters long</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-check-circle text-blue-600 mt-0.5 text-xs"></i>
                                <span>Include a mix of uppercase and lowercase letters</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-check-circle text-blue-600 mt-0.5 text-xs"></i>
                                <span>Include numbers and special characters</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-check-circle text-blue-600 mt-0.5 text-xs"></i>
                                <span>Avoid using personal information or common words</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <!-- Vehicles Tab -->
            @if($activeTab === 'vehicles')
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">My Vehicles</h3>
                        <p class="text-sm text-gray-500 mt-1">Overview of your registered vehicles</p>
                    </div>
                    <a href="{{ route('applicant.vehicles') }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="p-6">
                    <!-- Vehicle Stats -->
                    <div class="grid grid-cols-3 gap-4 mb-6">
                        <div class="bg-gray-50 rounded-lg p-4 text-center">
                            <p class="text-2xl font-bold text-gray-900">{{ $vehicleStats['total'] }}</p>
                            <p class="text-sm text-gray-500">Total</p>
                        </div>
                        <div class="bg-emerald-50 rounded-lg p-4 text-center">
                            <p class="text-2xl font-bold text-emerald-600">{{ $vehicleStats['active'] }}</p>
                            <p class="text-sm text-gray-500">Active</p>
                        </div>
                        <div class="bg-amber-50 rounded-lg p-4 text-center">
                            <p class="text-2xl font-bold text-amber-600">{{ $vehicleStats['pending'] }}</p>
                            <p class="text-sm text-gray-500">Pending</p>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="flex gap-3">
                        <a href="{{ route('gate-pass.gate-pass-applicant-form') }}" class="flex-1 flex items-center justify-center gap-2 px-4 py-3 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition-colors">
                            <i class="fas fa-plus"></i>
                            Register New Vehicle
                        </a>
                        <a href="{{ route('applicant.vehicles') }}" class="flex-1 flex items-center justify-center gap-2 px-4 py-3 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="fas fa-list"></i>
                            View All Vehicles
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    @else
    <div class="text-center py-12">
        <p class="text-gray-500">Please login to view your profile.</p>
    </div>
    @endif
</div>
