<div class="flex-1 md:ml-64 p-6 pt-24">
    <div class="max-w-4xl mx-auto">
        @if($user)
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Account Settings</h1>
            <p class="text-gray-600 mt-1">Manage your account preferences and security settings</p>
        </div>

        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Sidebar Navigation -->
            <div class="lg:w-64 flex-shrink-0">
                <nav class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 border-b border-gray-200 bg-gray-50">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center text-green-700 font-semibold">
                                {{ strtoupper(substr($user->email, 0, 2)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $user->details?->full_name ?? 'User' }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                            </div>
                        </div>
                    </div>
                    <ul class="p-2">
                        <li>
                            <button wire:click="setTab('profile')"
                                class="w-full flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ $activeTab === 'profile' ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Profile
                            </button>
                        </li>
                        <li>
                            <button wire:click="setTab('security')"
                                class="w-full flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ $activeTab === 'security' ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Security
                            </button>
                        </li>
                        <li>
                            <button wire:click="setTab('sessions')"
                                class="w-full flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ $activeTab === 'sessions' ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Sessions
                            </button>
                        </li>
                        <li>
                            <button wire:click="setTab('danger')"
                                class="w-full flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ $activeTab === 'danger' ? 'bg-red-50 text-red-700' : 'text-gray-700 hover:bg-gray-50' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                Danger Zone
                            </button>
                        </li>
                    </ul>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="flex-1">
                <!-- Profile Tab -->
                @if($activeTab === 'profile')
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Profile Information</h3>
                        <p class="text-sm text-gray-500 mt-1">Update your email address and profile details</p>
                    </div>
                    <div class="p-6 space-y-6">
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
                                type="submit"
                                wire:loading.attr="disabled"
                                class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors disabled:opacity-50">
                                <span wire:loading.remove wire:target="updatePassword">Update Password</span>
                                <span wire:loading wire:target="updatePassword">Updating...</span>
                            </button>
                        </div>
                    </form>
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
                            If necessary, you may log out of all of your other browser sessions across all of your devices.
                            Enter your password to confirm.
                        </p>
                        <div class="space-y-4">
                            <div>
                                <label for="sessionPassword" class="block text-sm font-medium text-gray-700 mb-1">Your Password</label>
                                <input
                                    type="password"
                                    id="sessionPassword"
                                    wire:model="currentPassword"
                                    class="w-full max-w-sm px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                @error('currentPassword') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <button
                                wire:click="logoutOtherSessions"
                                wire:loading.attr="disabled"
                                class="px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-900 transition-colors disabled:opacity-50">
                                Log Out Other Browser Sessions
                            </button>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Danger Zone Tab -->
                @if($activeTab === 'danger')
                <div class="space-y-4" x-data="{
                    confirmDeactivation() {
                        Swal.fire({
                            title: 'Deactivate Your Account?',
                            text: 'You will be logged out and your account will be disabled.',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#f59e0b',
                            cancelButtonColor: '#6b7280',
                            confirmButtonText: 'Yes, deactivate',
                            cancelButtonText: 'Cancel'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $wire.requestDeactivation();
                            }
                        });
                    },
                    confirmDeletion() {
                        Swal.fire({
                            title: 'Delete Your Account?',
                            html: '<p class=\'text-gray-600 mb-3\'>This action cannot be undone. All your data will be permanently deleted.</p><p class=\'text-sm text-red-600 font-medium\'>Type <strong>DELETE</strong> to confirm:</p>',
                            input: 'text',
                            inputPlaceholder: 'Type DELETE',
                            icon: 'error',
                            showCancelButton: true,
                            confirmButtonColor: '#dc2626',
                            cancelButtonColor: '#6b7280',
                            confirmButtonText: 'Delete permanently',
                            cancelButtonText: 'Cancel',
                            preConfirm: (value) => {
                                if (value !== 'DELETE') {
                                    Swal.showValidationMessage('Please type DELETE to confirm');
                                    return false;
                                }
                                return true;
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $wire.requestDeletion();
                            }
                        });
                    }
                }">
                    <!-- Deactivate Account -->
                    <div class="bg-white rounded-xl shadow-sm border border-red-200 overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Deactivate Account</h3>
                                    <p class="text-sm text-gray-500 mt-1">
                                        Temporarily disable your account. You can contact an administrator to reactivate it later.
                                    </p>
                                </div>
                                <button
                                    @click="confirmDeactivation()"
                                    class="px-4 py-2 text-sm font-medium text-amber-700 bg-amber-100 rounded-lg hover:bg-amber-200 transition-colors whitespace-nowrap">
                                    Deactivate
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Account -->
                    <div class="bg-white rounded-xl shadow-sm border border-red-200 overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Delete Account</h3>
                                    <p class="text-sm text-gray-500 mt-1">
                                        Permanently delete your account and all associated data. This action cannot be undone.
                                    </p>
                                </div>
                                <button
                                    @click="confirmDeletion()"
                                    class="px-4 py-2 text-sm font-medium text-red-700 bg-red-100 rounded-lg hover:bg-red-200 transition-colors whitespace-nowrap">
                                    Delete Account
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @else
        <!-- Not Logged In State -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Not Logged In</h3>
            <p class="text-sm text-gray-500">Please log in to manage your account settings.</p>
        </div>
        @endif

        <!-- Loading Overlay -->
        <div wire:loading.flex wire:target="requestDeactivation,requestDeletion,updatePassword,updateEmail"
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
