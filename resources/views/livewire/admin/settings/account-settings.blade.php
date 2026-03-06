<div class="flex-1 md:ml-64 p-6 pt-24">
    <div class="max-w-4xl mx-auto">
        @if($user)
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">My Account</h1>
            <p class="text-gray-600 mt-1">Manage your account preferences and security settings</p>
        </div>

        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Sidebar Navigation -->
            <div class="lg:w-64 flex-shrink-0">
                <nav class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden sticky top-24">
                    <!-- User Profile Summary -->
                    <div class="p-4 border-b border-gray-200 bg-gradient-to-br from-green-50 to-white">
                        <div class="flex items-center gap-3">
                            <div class="h-12 w-12 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white font-semibold text-lg shadow-sm">
                                {{ strtoupper(substr($displayName, 0, 2)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ $displayName }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                                <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ $this->getRoleDisplayName() }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <ul class="p-2">
                        <li>
                            <button wire:click="setTab('profile')"
                                class="w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ $activeTab === 'profile' ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Profile
                            </button>
                        </li>
                        <li>
                            <button wire:click="setTab('security')"
                                class="w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ $activeTab === 'security' ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Security
                            </button>
                        </li>
                        <li>
                            <button wire:click="setTab('sessions')"
                                class="w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ $activeTab === 'sessions' ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Sessions
                            </button>
                        </li>
                        <li>
                            <button wire:click="setTab('danger')"
                                class="w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ $activeTab === 'danger' ? 'bg-red-50 text-red-700' : 'text-gray-700 hover:bg-gray-50' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                Danger Zone
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
                        <p class="text-sm text-gray-500 mt-1">Update your email address</p>
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
                                <label class="block text-sm font-medium text-gray-500 mb-1">Role</label>
                                <p class="text-sm text-gray-900">{{ $this->getRoleDisplayName() }}</p>
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
                        <p class="text-sm text-gray-500 mt-1">Ensure your account is using a strong password</p>
                    </div>
                    <form wire:submit="updatePassword" class="p-6 space-y-4">
                        <div>
                            <label for="currentPassword" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                            <input
                                type="password"
                                id="currentPassword"
                                wire:model="currentPassword"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                placeholder="Enter your current password">
                            @error('currentPassword') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="newPassword" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                            <input
                                type="password"
                                id="newPassword"
                                wire:model="newPassword"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                placeholder="Enter a new password">
                            @error('newPassword') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="newPasswordConfirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                            <input
                                type="password"
                                id="newPasswordConfirmation"
                                wire:model="newPasswordConfirmation"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                placeholder="Confirm your new password">
                            @error('newPasswordConfirmation') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="pt-2">
                            <button
                                type="submit"
                                wire:loading.attr="disabled"
                                class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors disabled:opacity-50">
                                <span wire:loading.remove wire:target="updatePassword">Update Password</span>
                                <span wire:loading wire:target="updatePassword">Updating...</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Password Requirements -->
                <div class="bg-blue-50 rounded-xl border border-blue-200 p-4">
                    <h4 class="text-sm font-medium text-blue-800 mb-2">Password Requirements</h4>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li class="flex items-center gap-2">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Minimum 8 characters
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Mix of letters and numbers recommended
                        </li>
                    </ul>
                </div>
                @endif

                <!-- Sessions Tab -->
                @if($activeTab === 'sessions')
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Browser Sessions</h3>
                        <p class="text-sm text-gray-500 mt-1">Manage and log out your active sessions on other browsers and devices</p>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-gray-600 mb-4">
                            If necessary, you may log out of all your other browser sessions across all of your devices. Some of your recent sessions are listed below; however, this list may not be exhaustive. If you feel your account has been compromised, you should also update your password.
                        </p>

                        <!-- Current Session -->
                        <div class="flex items-center gap-4 p-4 bg-green-50 rounded-lg border border-green-200 mb-4">
                            <div class="h-10 w-10 rounded-lg bg-green-100 flex items-center justify-center">
                                <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">This device</p>
                                <p class="text-xs text-gray-500">Current session</p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Active
                            </span>
                        </div>

                        <!-- Logout Other Sessions -->
                        <div class="border-t border-gray-200 pt-4">
                            <label for="sessionPassword" class="block text-sm font-medium text-gray-700 mb-2">
                                Confirm your password to log out other sessions
                            </label>
                            <div class="flex gap-3">
                                <input
                                    type="password"
                                    id="sessionPassword"
                                    wire:model="currentPassword"
                                    placeholder="Enter your password"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <button
                                    wire:click="logoutOtherSessions"
                                    wire:loading.attr="disabled"
                                    class="px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-900 transition-colors disabled:opacity-50">
                                    <span wire:loading.remove wire:target="logoutOtherSessions">Log Out Other Sessions</span>
                                    <span wire:loading wire:target="logoutOtherSessions">Processing...</span>
                                </button>
                            </div>
                            @error('currentPassword') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
                @endif

                <!-- Danger Zone Tab -->
                @if($activeTab === 'danger')
                <div class="bg-white rounded-xl shadow-sm border border-red-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-red-200 bg-red-50">
                        <h3 class="text-lg font-semibold text-red-900">Danger Zone</h3>
                        <p class="text-sm text-red-600 mt-1">Irreversible and destructive actions</p>
                    </div>
                    <div class="p-6 space-y-6">
                        <!-- Deactivate Account -->
                        <div class="flex items-start justify-between gap-4 pb-6 border-b border-gray-200">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">Deactivate Account</h4>
                                <p class="text-sm text-gray-500 mt-1">
                                    Temporarily disable your account. You won't be able to log in until an administrator reactivates it.
                                </p>
                            </div>
                            <button
                                onclick="Swal.fire({
                                    title: 'Deactivate Account?',
                                    text: 'You will be logged out and won\'t be able to log in until an administrator reactivates your account.',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#dc2626',
                                    cancelButtonColor: '#6b7280',
                                    confirmButtonText: 'Yes, deactivate'
                                }).then((result) => { if (result.isConfirmed) { @this.requestDeactivation() } })"
                                class="flex-shrink-0 px-4 py-2 text-sm font-medium text-amber-700 bg-amber-100 rounded-lg hover:bg-amber-200 transition-colors">
                                Deactivate
                            </button>
                        </div>

                        <!-- Delete Account -->
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">Delete Account</h4>
                                <p class="text-sm text-gray-500 mt-1">
                                    Permanently delete your account and all associated data. This action cannot be undone.
                                </p>
                            </div>
                            <button
                                onclick="Swal.fire({
                                    title: 'Delete Account?',
                                    text: 'This will permanently delete your account and all data. This action cannot be undone!',
                                    icon: 'error',
                                    showCancelButton: true,
                                    confirmButtonColor: '#dc2626',
                                    cancelButtonColor: '#6b7280',
                                    confirmButtonText: 'Yes, delete forever'
                                }).then((result) => { if (result.isConfirmed) { @this.requestDeletion() } })"
                                class="flex-shrink-0 px-4 py-2 text-sm font-medium text-red-700 bg-red-100 rounded-lg hover:bg-red-200 transition-colors">
                                Delete Account
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Warning Notice -->
                <div class="bg-amber-50 rounded-xl border border-amber-200 p-4">
                    <div class="flex items-start gap-3">
                        <svg class="h-5 w-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div>
                            <h4 class="text-sm font-medium text-amber-800">Important Notice</h4>
                            <p class="text-sm text-amber-700 mt-1">
                                Actions in the danger zone are permanent and cannot be reversed. Make sure you understand the consequences before proceeding.
                            </p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @else
        <!-- No User Found -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <h3 class="mt-2 text-lg font-medium text-gray-900">Session Expired</h3>
            <p class="mt-1 text-sm text-gray-500">Please log in again to access your account settings.</p>
            <a href="{{ route('login') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                Go to Login
            </a>
        </div>
        @endif

        <!-- Loading Overlay -->
        <div wire:loading.flex wire:target="updateEmail,updatePassword,requestDeactivation,requestDeletion,logoutOtherSessions"
            class="fixed inset-0 bg-black bg-opacity-25 items-center justify-center z-50" style="display: none;">
            <div class="bg-white rounded-lg p-4 flex items-center gap-3 shadow-xl">
                <svg class="animate-spin h-5 w-5 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-sm font-medium text-gray-700">Processing...</span>
            </div>
        </div>
    </div>
</div>
