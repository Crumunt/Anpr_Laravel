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
                        <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center font-bold text-xl shadow-lg ring-4 ring-emerald-100">
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
                    <li>
                        <button wire:click="setTab('vehicles')"
                            role="tab"
                            aria-selected="{{ $activeTab === 'vehicles' ? 'true' : 'false' }}"
                            aria-controls="vehicles-panel"
                            class="w-full flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 {{ $activeTab === 'vehicles' ? 'bg-gradient-to-r from-emerald-50 to-emerald-100/50 text-emerald-700 shadow-sm border border-emerald-200/50' : 'text-gray-700 hover:bg-gray-50' }}">
                            <i class="fas fa-car w-5 text-center"></i>
                            <span>Vehicles</span>
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
                <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Profile Information</h3>
                        <p class="text-sm text-gray-500 mt-0.5">Update your personal information</p>
                    </div>
                    @if(!$isEditingProfile)
                    <button
                        wire:click="toggleEditProfile"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-emerald-700 bg-emerald-50 rounded-xl hover:bg-emerald-100 transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                        <i class="fas fa-edit"></i>
                        Edit Profile
                    </button>
                    @endif
                </div>
                <div class="p-6 lg:p-8 space-y-8">
                    @if($isEditingProfile)
                    <!-- Edit Mode -->
                    <form wire:submit="updateProfile" class="space-y-8">
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
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors @error('firstName') border-red-500 @enderror"
                                        placeholder="Enter first name">
                                    @error('firstName') <p class="mt-1 text-sm text-red-600 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="middleName" class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                                    <input
                                        type="text"
                                        id="middleName"
                                        wire:model="middleName"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors"
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
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors @error('lastName') border-red-500 @enderror"
                                        placeholder="Enter last name">
                                    @error('lastName') <p class="mt-1 text-sm text-red-600 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="suffix" class="block text-sm font-medium text-gray-700 mb-1">Suffix</label>
                                    <select
                                        id="suffix"
                                        wire:model="suffix"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors bg-white">
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
                        <div class="space-y-4 pt-6 border-t border-gray-200">
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
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors @error('phoneNumber') border-red-500 @enderror"
                                        placeholder="e.g., 09123456789">
                                    @error('phoneNumber') <p class="mt-1 text-sm text-red-600 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="licenseNumber" class="block text-sm font-medium text-gray-700 mb-1">Driver's License Number</label>
                                    <input
                                        type="text"
                                        id="licenseNumber"
                                        wire:model="licenseNumber"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors"
                                        placeholder="Enter license number">
                                </div>
                            </div>
                        </div>

                        <!-- Address Section -->
                        <div class="space-y-4 pt-6 border-t border-gray-200">
                            <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wide flex items-center gap-2">
                                <i class="fas fa-map-marker-alt text-gray-400"></i>
                                Address
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="region" class="block text-sm font-medium text-gray-700 mb-1">Region</label>
                                    <select
                                        id="region"
                                        wire:model.live="region"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors bg-white">
                                        <option value="">Select Region</option>
                                        @foreach($this->regions as $reg)
                                        <option value="{{ $reg['value'] }}">{{ $reg['label'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="province" class="block text-sm font-medium text-gray-700 mb-1">Province</label>
                                    <select
                                        id="province"
                                        wire:model.live="province"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors bg-white disabled:bg-gray-100 disabled:cursor-not-allowed"
                                        {{ empty($region) ? 'disabled' : '' }}>
                                        <option value="">Select Province</option>
                                        @foreach($this->provinces as $prov)
                                        <option value="{{ $prov['value'] }}">{{ $prov['label'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="municipality" class="block text-sm font-medium text-gray-700 mb-1">Municipality/City</label>
                                    <select
                                        id="municipality"
                                        wire:model.live="municipality"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors bg-white disabled:bg-gray-100 disabled:cursor-not-allowed"
                                        {{ empty($province) ? 'disabled' : '' }}>
                                        <option value="">Select Municipality/City</option>
                                        @foreach($this->municipalities as $muni)
                                        <option value="{{ $muni['value'] }}">{{ $muni['label'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="barangay" class="block text-sm font-medium text-gray-700 mb-1">Barangay</label>
                                    <select
                                        id="barangay"
                                        wire:model="barangay"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors bg-white disabled:bg-gray-100 disabled:cursor-not-allowed"
                                        {{ empty($municipality) ? 'disabled' : '' }}>
                                        <option value="">Select Barangay</option>
                                        @foreach($this->barangays as $brgy)
                                        <option value="{{ $brgy['value'] }}">{{ $brgy['label'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="zipCode" class="block text-sm font-medium text-gray-700 mb-1">ZIP Code</label>
                                    <input
                                        type="text"
                                        id="zipCode"
                                        wire:model="zipCode"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors"
                                        placeholder="Enter ZIP code"
                                        maxlength="10">
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                            <button
                                type="button"
                                wire:click="toggleEditProfile"
                                class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                Cancel
                            </button>
                            <button
                                type="submit"
                                wire:loading.attr="disabled"
                                class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-emerald-600 to-emerald-700 rounded-xl hover:from-emerald-700 hover:to-emerald-800 shadow-md hover:shadow-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
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
                    <!-- Read-only fields (CLSU ID, Account Type) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6 pb-8 border-b border-gray-200">
                        <div class="p-4 bg-gradient-to-br from-gray-50 to-gray-100/50 rounded-xl border border-gray-200">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">CLSU ID</label>
                            <p class="text-base font-semibold text-gray-900 font-mono">{{ $user->details->clsu_id ?? 'Not assigned' }}</p>
                            <p class="text-xs text-gray-500 mt-1">Assigned by administrator</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-gray-50 to-gray-100/50 rounded-xl border border-gray-200">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Account Type</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                Applicant
                            </span>
                        </div>
                    </div>

                    <!-- Personal Information -->
                    <div class="space-y-4">
                        <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wide flex items-center gap-2">
                            <i class="fas fa-user text-gray-400"></i>
                            Personal Information
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-4 bg-gradient-to-br from-gray-50 to-gray-100/50 rounded-xl border border-gray-200">
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Full Name</label>
                                <p class="text-base font-semibold text-gray-900">{{ $user->details->full_name ?? 'Not set' }}</p>
                            </div>
                            <div class="p-4 bg-gradient-to-br from-gray-50 to-gray-100/50 rounded-xl border border-gray-200">
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Phone Number</label>
                                <p class="text-base font-semibold text-gray-900">{{ $user->details->phone_number ?? 'Not set' }}</p>
                            </div>
                            @if($user->details?->license_number)
                            <div class="p-4 bg-gradient-to-br from-gray-50 to-gray-100/50 rounded-xl border border-gray-200">
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Driver's License</label>
                                <p class="text-base font-semibold text-gray-900">{{ $user->details->license_number }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Address Section -->
                    @if($user->details?->region || $user->details?->province)
                    <div class="space-y-4 pt-6 border-t border-gray-200">
                        <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wide flex items-center gap-2">
                            <i class="fas fa-map-marker-alt text-gray-400"></i>
                            Address
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($user->details?->region)
                            <div class="p-4 bg-gradient-to-br from-gray-50 to-gray-100/50 rounded-xl border border-gray-200">
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Region</label>
                                <p class="text-base font-semibold text-gray-900">{{ $user->details->region_name ?? 'Not set' }}</p>
                            </div>
                            @endif
                            @if($user->details?->province)
                            <div class="p-4 bg-gradient-to-br from-gray-50 to-gray-100/50 rounded-xl border border-gray-200">
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Province</label>
                                <p class="text-base font-semibold text-gray-900">{{ $user->details->province }}</p>
                            </div>
                            @endif
                            @if($user->details?->municipality)
                            <div class="p-4 bg-gradient-to-br from-gray-50 to-gray-100/50 rounded-xl border border-gray-200">
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Municipality/City</label>
                                <p class="text-base font-semibold text-gray-900">{{ $user->details->municipality }}</p>
                            </div>
                            @endif
                            @if($user->details?->barangay)
                            <div class="p-4 bg-gradient-to-br from-gray-50 to-gray-100/50 rounded-xl border border-gray-200">
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Barangay</label>
                                <p class="text-base font-semibold text-gray-900">{{ $user->details->barangay }}</p>
                            </div>
                            @endif
                            @if($user->details?->zip_code)
                            <div class="p-4 bg-gradient-to-br from-gray-50 to-gray-100/50 rounded-xl border border-gray-200">
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">ZIP Code</label>
                                <p class="text-base font-semibold text-gray-900">{{ $user->details->zip_code }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Work Information -->
                    @if($user->details?->college_unit_department || $user->details?->position)
                    <div class="space-y-4 pt-6 border-t border-gray-200">
                        <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wide flex items-center gap-2">
                            <i class="fas fa-briefcase text-gray-400"></i>
                            Work Information
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($user->details?->college_unit_department)
                            <div class="p-4 bg-gradient-to-br from-gray-50 to-gray-100/50 rounded-xl border border-gray-200">
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">College/Unit/Department</label>
                                <p class="text-base font-semibold text-gray-900">{{ $user->details->college_unit_department }}</p>
                            </div>
                            @endif
                            @if($user->details?->position)
                            <div class="p-4 bg-gradient-to-br from-gray-50 to-gray-100/50 rounded-xl border border-gray-200">
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Position</label>
                                <p class="text-base font-semibold text-gray-900">{{ $user->details->position }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Account Email -->
                    <div class="space-y-4 pt-6 border-t border-gray-200">
                        <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wide flex items-center gap-2">
                            <i class="fas fa-envelope text-gray-400"></i>
                            Account Email
                        </h4>
                        <div class="p-4 bg-gradient-to-br from-gray-50 to-gray-100/50 rounded-xl border border-gray-200">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Email Address</label>
                            <p class="text-base font-semibold text-gray-900">{{ $user->email }}</p>
                            <p class="text-xs text-gray-500 mt-1">This is your login email address</p>
                        </div>
                    </div>
                    @endif
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

    <!-- Loading Overlay -->
    <div wire:loading.flex wire:target="updateProfile,updatePassword"
        class="fixed inset-0 bg-black bg-opacity-25 items-center justify-center z-50" style="display: none;">
        <div class="bg-white rounded-xl p-4 flex items-center gap-3 shadow-xl">
            <svg class="animate-spin h-5 w-5 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-sm font-medium text-gray-700">Processing...</span>
        </div>
    </div>
</div>
