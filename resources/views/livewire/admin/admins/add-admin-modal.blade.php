<div>
    <!-- Modal -->
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="bg-white rounded-lg w-full max-w-2xl max-h-[90vh] flex flex-col shadow-xl">

            <!-- Fixed Header -->
            <div class="flex items-center justify-between p-6 border-b">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Add New Admin</h2>
                    <p class="text-sm text-gray-500 mt-1">Create a new administrator account</p>
                </div>
                <button
                    type="button"
                    class="text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-all duration-150 rounded-full p-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                    wire:click="closeModal"
                >
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Scrollable Content -->
            <form wire:submit="createAdmin" class="flex-1 overflow-y-auto">
                <div class="p-6 space-y-6">

                    <!-- Basic Information Section -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Basic Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- First Name -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    First Name <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    wire:model="first_name"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    placeholder="John"
                                >
                                @error('first_name')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Middle Name -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Middle Name
                                </label>
                                <input
                                    type="text"
                                    wire:model="middle_name"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    placeholder="Optional"
                                >
                            </div>

                            <!-- Last Name -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Last Name <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    wire:model="last_name"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    placeholder="Doe"
                                >
                                @error('last_name')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Suffix -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Suffix
                                </label>
                                <input
                                    type="text"
                                    wire:model="suffix"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    placeholder="Jr., Sr., III"
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information Section -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Contact Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="email"
                                    wire:model="email"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    placeholder="admin@example.com"
                                >
                                @error('email')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone Number -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Phone Number
                                </label>
                                <input
                                    type="text"
                                    wire:model="phone_number"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    placeholder="09123456789"
                                >
                                @error('phone_number')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Employment Information Section -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Employment Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- CLSU ID -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    CLSU ID / Employee ID
                                </label>
                                <input
                                    type="text"
                                    wire:model="clsu_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    placeholder="Employee ID"
                                >
                                @error('clsu_id')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Role -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Role <span class="text-red-500">*</span>
                                </label>
                                <select
                                    wire:model="role"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                >
                                    @foreach($availableRoles as $roleOption)
                                        <option value="{{ $roleOption['value'] }}">{{ $roleOption['label'] }}</option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Role Descriptions -->
                    <div class="p-4 bg-blue-50 rounded-lg border border-blue-100">
                        <h4 class="text-sm font-medium text-blue-800 mb-2">Role Descriptions</h4>
                        <ul class="text-xs text-blue-700 space-y-1">
                            <li><strong>Super Admin:</strong> Full system access with all permissions</li>
                            <li><strong>Admin Editor:</strong> Full CRUD on users, applicants, vehicles, and gate passes</li>
                            <li><strong>Admin Viewer:</strong> View-only access to all resources</li>
                            <li><strong>Encoder:</strong> Data entry for applicants, vehicles, and gate passes</li>
                            <li><strong>Security:</strong> Checkpoint operations, camera feed, and blacklist management</li>
                            <li><strong>Maintenance:</strong> Maintenance management and reports</li>
                        </ul>
                    </div>

                    <!-- Password Notice -->
                    <div class="p-4 bg-amber-50 rounded-lg border border-amber-100">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <h4 class="text-sm font-medium text-amber-800">Password Information</h4>
                                <p class="text-xs text-amber-700 mt-1">
                                    A temporary password will be automatically generated. The admin will be required to change their password upon first login.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fixed Footer -->
                <div class="flex items-center justify-end gap-3 p-6 border-t bg-gray-50">
                    <button
                        type="button"
                        wire:click="closeModal"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed"
                    >
                        <span wire:loading.remove wire:target="createAdmin" class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Create Admin
                        </span>
                        <span wire:loading wire:target="createAdmin" class="flex items-center gap-2">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Creating...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
