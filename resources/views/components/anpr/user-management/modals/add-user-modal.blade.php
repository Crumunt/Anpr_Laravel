@props([
    'departments' => ['IT Department', 'Security', 'Administration', 'Faculty', 'Student Services'],
    'roles' => [
        ['value' => 'admin', 'label' => 'Administrator', 'description' => 'Full system access'],
        ['value' => 'operator', 'label' => 'Operator', 'description' => 'Limited operations'],
        ['value' => 'viewer', 'label' => 'Viewer', 'description' => 'Read-only access'],
    ],
    'statuses' => [
        ['value' => 'active', 'label' => 'Active', 'description' => 'User can access the system'],
        ['value' => 'inactive', 'label' => 'Inactive', 'description' => 'User access is suspended'],
        ['value' => 'pending', 'label' => 'Pending', 'description' => 'Awaiting approval'],
    ]
])

<div id="add-user-modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 hidden transition-all duration-300 ease-out" role="dialog" aria-labelledby="add-user-modal-title">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-4 sm:mx-6 max-h-[95vh] overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="add-user-modal-content">
        <style>
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(20px) scale(0.98); }
                to { opacity: 1; transform: translateY(0) scale(1); }
            }
            .animate-fade-in {
                animation: fadeIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            }
        </style>
        
        <!-- Enhanced Header -->
        <div class="relative bg-gradient-to-r from-green-50 via-emerald-50 to-green-50 border-b border-gray-200 px-6 py-5">
            <div class="absolute inset-0 bg-gradient-to-r from-green-100/20 to-emerald-100/20"></div>
            <div class="relative flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center shadow-lg">
                            <i class="fas fa-user-plus text-green-600 text-lg" aria-hidden="true"></i>
                        </div>
                        <div class="absolute -top-1 -right-1 w-4 h-4 bg-green-500 rounded-full flex items-center justify-center">
                            <span class="text-xs text-white font-bold">+</span>
                        </div>
                    </div>
                    <div>
                        <h3 id="add-user-modal-title" class="text-xl font-bold text-gray-900">Add New User</h3>
                        <p class="text-sm text-gray-600 mt-1">Create a new user account with appropriate permissions</p>
                    </div>
                </div>
                <button onclick="closeAddUserModal()" class="group p-2 rounded-xl hover:bg-gray-100 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2" aria-label="Close modal">
                    <i class="fas fa-times text-gray-400 group-hover:text-gray-600 transition-colors" aria-hidden="true"></i>
                </button>
            </div>
        </div>

        <!-- Enhanced Content -->
        <div class="overflow-y-auto p-6 max-h-[calc(95vh-140px)] scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
            <form id="add-user-form" class="animate-fade-in space-y-6">
                <!-- Personal Information Section -->
                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-4 uppercase tracking-wide flex items-center">
                        <i class="fas fa-user text-green-500 mr-2"></i>
                        Personal Information
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="relative">
                            <label for="full-name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                            <div class="relative">
                                <i class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" aria-hidden="true"></i>
                                <input type="text" id="full-name" name="full_name" required class="pl-10 w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 invalid:border-red-500 invalid:ring-red-500 py-2 px-3 text-gray-700 transition-colors duration-200" placeholder="Enter full name" aria-describedby="full-name-error">
                            </div>
                            <div class="text-red-500 text-xs mt-1 hidden" id="full-name-error"></div>
                        </div>
                        <div class="relative">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <div class="relative">
                                <i class="fas fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" aria-hidden="true"></i>
                                <input type="email" id="email" name="email" required class="pl-10 w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 invalid:border-red-500 invalid:ring-red-500 py-2 px-3 text-gray-700 transition-colors duration-200" placeholder="Enter email address" aria-describedby="email-error">
                            </div>
                            <div class="text-red-500 text-xs mt-1 hidden" id="email-error"></div>
                        </div>
                        <div class="relative">
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <div class="relative">
                                <i class="fas fa-phone absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" aria-hidden="true"></i>
                                <input type="tel" id="phone" name="phone" class="pl-10 w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 py-2 px-3 text-gray-700 transition-colors duration-200" placeholder="Enter phone number">
                            </div>
                        </div>
                        <div class="relative">
                            <label for="department" class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                            <div class="relative">
                                <i class="fas fa-building absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" aria-hidden="true"></i>
                                <select id="department" name="department" class="pl-10 w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 py-2 px-3 text-gray-700 transition-colors duration-200">
                                    <option value="">Select department</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept }}">{{ $dept }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Settings Section -->
                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-4 uppercase tracking-wide flex items-center">
                        <i class="fas fa-shield-alt text-blue-500 mr-2"></i>
                        Account Settings
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="relative">
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                            <div class="relative">
                                <i class="fas fa-user-tag absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" aria-hidden="true"></i>
                                <select id="role" name="role" required class="pl-10 w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 invalid:border-red-500 invalid:ring-red-500 py-2 px-3 text-gray-700 transition-colors duration-200" aria-describedby="role-error">
                                    <option value="">Select a role</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role['value'] }}" data-description="{{ $role['description'] }}">{{ $role['label'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="text-red-500 text-xs mt-1 hidden" id="role-error"></div>
                            <div id="role-description" class="text-xs text-gray-500 mt-1 hidden"></div>
                        </div>
                        <div class="relative">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <div class="relative">
                                <i class="fas fa-toggle-on absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" aria-hidden="true"></i>
                                <select id="status" name="status" required class="pl-10 w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 invalid:border-red-500 invalid:ring-red-500 py-2 px-3 text-gray-700 transition-colors duration-200" aria-describedby="status-error">
                                    <option value="">Select status</option>
                                    @foreach($statuses as $status)
                                        <option value="{{ $status['value'] }}" data-description="{{ $status['description'] }}">{{ $status['label'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="text-red-500 text-xs mt-1 hidden" id="status-error"></div>
                            <div id="status-description" class="text-xs text-gray-500 mt-1 hidden"></div>
                        </div>
                    </div>
                </div>

                <!-- Additional Options Section -->
                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-4 uppercase tracking-wide flex items-center">
                        <i class="fas fa-cog text-purple-500 mr-2"></i>
                        Additional Options
                    </h4>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <input type="checkbox" id="send-welcome-email" name="send_welcome_email" class="mt-1 h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                            <div class="flex-1">
                                <label for="send-welcome-email" class="text-sm font-medium text-gray-700">Send Welcome Email</label>
                                <p class="text-xs text-gray-500 mt-1">Send a welcome email with login credentials to the new user</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <input type="checkbox" id="require-password-change" name="require_password_change" class="mt-1 h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                            <div class="flex-1">
                                <label for="require-password-change" class="text-sm font-medium text-gray-700">Require Password Change</label>
                                <p class="text-xs text-gray-500 mt-1">User must change password on first login</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <button type="button" onclick="closeAddUserModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-gradient-to-r from-green-600 to-emerald-600 rounded-lg hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i class="fas fa-plus mr-2"></i>
                        Add User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- scripts moved to public/js/anpr/user-management.js -->
