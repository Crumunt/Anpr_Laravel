<div>
    @if($user)
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">My Profile</h1>
        <p class="text-gray-600 mt-1">Manage your account settings and security preferences</p>
    </div>

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
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Profile Information</h3>
                        <p class="text-sm text-gray-500 mt-0.5">Update your personal information</p>
                    </div>
                    @if(!$isEditingProfile)
                    <button
                        wire:click="toggleEditProfile"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-green-700 bg-green-50 rounded-lg hover:bg-green-100 transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                        <i class="fas fa-edit"></i>
                        Edit Profile
                    </button>
                    @endif
                </div>
                <div class="p-6 space-y-6">
                    @if($isEditingProfile)
                    <!-- Edit Mode -->
                    <form wire:submit="updateProfile" class="space-y-6">
                        <!-- Personal Information Section -->
                        <div class="space-y-4">
                            <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wide flex items-center gap-2">
                                <i class="fas fa-user text-gray-400"></i>
                                Personal Information
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="firstName" class="block text-sm font-medium text-gray-700 mb-1">
                                        First Name <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        id="firstName"
                                        wire:model="firstName"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors @error('firstName') border-red-500 @enderror"
                                        placeholder="Enter first name">
                                    @error('firstName') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="middleName" class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                                    <input
                                        type="text"
                                        id="middleName"
                                        wire:model="middleName"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors"
                                        placeholder="Enter middle name">
                                </div>
                                <div>
                                    <label for="lastName" class="block text-sm font-medium text-gray-700 mb-1">
                                        Last Name <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        id="lastName"
                                        wire:model="lastName"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors @error('lastName') border-red-500 @enderror"
                                        placeholder="Enter last name">
                                    @error('lastName') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="suffix" class="block text-sm font-medium text-gray-700 mb-1">Suffix</label>
                                    <select
                                        id="suffix"
                                        wire:model="suffix"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors bg-white">
                                        <option value="">None</option>
                                        <option value="Jr.">Jr.</option>
                                        <option value="Sr.">Sr.</option>
                                        <option value="II">II</option>
                                        <option value="III">III</option>
                                        <option value="IV">IV</option>
                                        <option value="V">V</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information Section -->
                        <div class="space-y-4 pt-4 border-t border-gray-200">
                            <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wide flex items-center gap-2">
                                <i class="fas fa-phone text-gray-400"></i>
                                Contact Information
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="phoneNumber" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                    <input
                                        type="tel"
                                        id="phoneNumber"
                                        wire:model="phoneNumber"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors @error('phoneNumber') border-red-500 @enderror"
                                        placeholder="e.g., 09123456789">
                                    @error('phoneNumber') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                            <button
                                type="button"
                                wire:click="toggleEditProfile"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                Cancel
                            </button>
                            <button
                                type="submit"
                                wire:loading.attr="disabled"
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span wire:loading.remove wire:target="updateProfile">
                                    <i class="fas fa-save"></i>
                                </span>
                                <span wire:loading wire:target="updateProfile">
                                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </span>
                                <span wire:loading.remove wire:target="updateProfile">Save Changes</span>
                                <span wire:loading wire:target="updateProfile">Saving...</span>
                            </button>
                        </div>
                    </form>
                    @else
                    <!-- View Mode -->
                    <!-- Read-only fields (CLSU ID, Role) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pb-6 border-b border-gray-200">
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">CLSU ID</label>
                            <p class="text-sm font-semibold text-gray-900 font-mono">{{ $user->details->clsu_id ?? 'Not set' }}</p>
                            <p class="text-xs text-gray-500 mt-1">Assigned by administrator</p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Role</label>
                            <p class="text-sm font-semibold text-gray-900">{{ $user->hasRole('security_admin') ? 'Security Administrator' : 'Security Personnel' }}</p>
                            <p class="text-xs text-gray-500 mt-1">Contact an administrator to change</p>
                        </div>
                    </div>

                    <!-- Personal Information -->
                    <div class="space-y-4">
                        <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wide">Personal Information</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Full Name</label>
                                <p class="text-sm text-gray-900">{{ $user->details?->full_name ?? 'Not set' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Phone Number</label>
                                <p class="text-sm text-gray-900">{{ $user->details?->phone_number ?? 'Not set' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Account Email -->
                    <div class="space-y-4 pt-4 border-t border-gray-200">
                        <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wide">Account Email</h4>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Email Address</label>
                            <p class="text-sm text-gray-900">{{ $user->email }}</p>
                            <p class="text-xs text-gray-500 mt-1">This is your login email address</p>
                        </div>
                    </div>
                    @endif
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
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('currentPassword') border-red-500 @enderror">
                        @error('currentPassword') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="newPassword" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                        <input
                            type="password"
                            id="newPassword"
                            wire:model="newPassword"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('newPassword') border-red-500 @enderror">
                        @error('newPassword') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="newPasswordConfirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                        <input
                            type="password"
                            id="newPasswordConfirmation"
                            wire:model="newPasswordConfirmation"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('newPasswordConfirmation') border-red-500 @enderror">
                        @error('newPasswordConfirmation') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="pt-4">
                        <button
                            wire:click="updatePassword"
                            wire:loading.attr="disabled"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors disabled:opacity-50">
                            <span wire:loading.remove wire:target="updatePassword">Update Password</span>
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

            <!-- Password Tips -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                <div class="flex gap-3">
                    <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                    <div>
                        <h4 class="text-sm font-medium text-blue-900">Password Requirements</h4>
                        <ul class="mt-2 text-sm text-blue-700 space-y-1">
                            <li class="flex items-center gap-2">
                                <i class="fas fa-check text-blue-500 text-xs"></i>
                                At least 8 characters long
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-check text-blue-500 text-xs"></i>
                                Include a mix of letters and numbers
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-check text-blue-500 text-xs"></i>
                                Avoid using personal information
                            </li>
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

    <!-- Loading Overlay -->
    <div wire:loading.flex wire:target="updateProfile,updatePassword"
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
