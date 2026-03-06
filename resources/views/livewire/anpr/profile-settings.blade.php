<div>
    @if($user)
    <!-- Page Header is handled by layout -->

    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Sidebar Navigation -->
        <div class="lg:w-64 flex-shrink-0">
            <nav class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden sticky top-24">
                <!-- User Profile Summary -->
                <div class="p-4 border-b border-gray-200 bg-gradient-to-br from-green-50 to-white">
                    <div class="flex items-center gap-3">
                        <div class="h-12 w-12 rounded-full bg-gradient-to-br from-green-500 to-green-700 flex items-center justify-center text-white font-semibold text-lg shadow-sm">
                            {{ strtoupper(substr($displayName, 0, 2)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $displayName }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                            <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $user->hasRole('security_admin') ? 'Security Admin' : 'Security Personnel' }}
                            </span>
                        </div>
                    </div>
                </div>

                <ul class="p-2">
                    <li>
                        <button wire:click="setTab('profile')"
                            class="w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ $activeTab === 'profile' ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }}">
                            <i class="fas fa-user w-5"></i>
                            Profile
                        </button>
                    </li>
                    <li>
                        <button wire:click="setTab('security')"
                            class="w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ $activeTab === 'security' ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }}">
                            <i class="fas fa-lock w-5"></i>
                            Security
                        </button>
                    </li>
                </ul>

                <!-- Account Info -->
                <div class="p-4 border-t border-gray-200 bg-gray-50">
                    <p class="text-xs text-gray-500">Member since {{ $memberSince }}</p>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 space-y-6">
            <!-- Profile Tab -->
            @if($activeTab === 'profile')
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Profile Information</h3>
                    <p class="text-sm text-gray-500 mt-1">Update your email address and view your profile details</p>
                </div>
                <div class="p-6 space-y-6">
                    <!-- User Details (Read-only) -->
                    @if($user->details)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pb-6 border-b border-gray-200">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Full Name</label>
                            <p class="text-sm text-gray-900">{{ $user->details->full_name ?? 'Not set' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">CLSU ID</label>
                            <p class="text-sm text-gray-900">{{ $user->details->clsu_id ?? 'Not set' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Phone Number</label>
                            <p class="text-sm text-gray-900">{{ $user->details->phone_number ?? 'Not set' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Role</label>
                            <p class="text-sm text-gray-900">{{ $user->hasRole('security_admin') ? 'Security Administrator' : 'Security Personnel' }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <div class="flex gap-3">
                            <input
                                type="email"
                                id="email"
                                wire:model="email"
                                class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            @if($email !== $currentEmail)
                            <button
                                wire:click="updateEmail"
                                wire:loading.attr="disabled"
                                class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors disabled:opacity-50">
                                <span wire:loading.remove wire:target="updateEmail">Save</span>
                                <span wire:loading wire:target="updateEmail">Saving...</span>
                            </button>
                            @endif
                        </div>
                        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
            @endif

            <!-- Security Tab -->
            @if($activeTab === 'security')
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Change Password</h3>
                    <p class="text-sm text-gray-500 mt-1">Ensure your account is using a secure password</p>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label for="currentPassword" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                        <input
                            type="password"
                            id="currentPassword"
                            wire:model="currentPassword"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('currentPassword') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="newPassword" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                        <input
                            type="password"
                            id="newPassword"
                            wire:model="newPassword"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('newPassword') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="newPasswordConfirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                        <input
                            type="password"
                            id="newPasswordConfirmation"
                            wire:model="newPasswordConfirmation"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('newPasswordConfirmation') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="pt-4">
                        <button
                            wire:click="updatePassword"
                            wire:loading.attr="disabled"
                            class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors disabled:opacity-50">
                            <span wire:loading.remove wire:target="updatePassword">Update Password</span>
                            <span wire:loading wire:target="updatePassword">Updating...</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Password Tips -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                <div class="flex gap-3">
                    <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                    <div>
                        <h4 class="text-sm font-medium text-blue-900">Password Requirements</h4>
                        <ul class="mt-2 text-sm text-blue-700 space-y-1">
                            <li>• At least 8 characters long</li>
                            <li>• Include a mix of letters and numbers</li>
                            <li>• Avoid using personal information</li>
                        </ul>
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
