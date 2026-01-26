@props([
    'search' => '',
    'roleFilter' => 'all',
    'statusFilter' => 'all'
])

<div class="bg-gradient-to-r from-gray-50 to-white px-4 md:px-6 py-4 border-b flex flex-wrap items-center justify-between gap-3">
    <div class="flex items-center">
        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center clsu-text mr-3">
            <i class="fas fa-users" aria-hidden="true"></i>
        </div>
        <div>
            <h2 class="font-semibold text-gray-800 text-lg">User Management</h2>
            <p class="text-xs text-gray-500">Manage system users and permissions</p>
        </div>
    </div>
    <div class="flex flex-wrap items-center gap-3">
        <div class="relative">
            <input 
                type="text" 
                id="user-search"
                placeholder="Search users by name, email, or role..." 
                value="{{ $search }}"
                class="py-1.5 pl-8 pr-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500"
                aria-label="Search users"
            >
            <i class="fas fa-search absolute left-3 top-2 text-gray-400 text-xs" aria-hidden="true"></i>
        </div>
        
        <select id="role-filter" class="text-sm border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 py-1.5 pl-3 pr-8 bg-white" aria-label="Filter by role">
            <option value="all" {{ $roleFilter === 'all' ? 'selected' : '' }}>All Roles</option>
            <option value="admin" {{ $roleFilter === 'admin' ? 'selected' : '' }}>Administrators</option>
            <option value="operator" {{ $roleFilter === 'operator' ? 'selected' : '' }}>Operators</option>
            <option value="viewer" {{ $roleFilter === 'viewer' ? 'selected' : '' }}>Viewers</option>
        </select>

        <select id="status-filter" class="text-sm border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 py-1.5 pl-3 pr-8 bg-white" aria-label="Filter by status">
            <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>All Status</option>
            <option value="active" {{ $statusFilter === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ $statusFilter === 'inactive' ? 'selected' : '' }}>Inactive</option>
            <option value="pending" {{ $statusFilter === 'pending' ? 'selected' : '' }}>Pending</option>
        </select>

        <div class="relative inline-block">
            <button id="user-filter-dropdown-btn" class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm hover:bg-gray-50 flex items-center bg-white transition-colors" aria-expanded="false" aria-haspopup="true">
                <i class="fas fa-filter mr-1.5" aria-hidden="true"></i> 
                <span>Advanced Filter</span>
                <span id="user-filter-badge" class="ml-1 bg-green-500 text-white text-xs rounded-full px-1.5 py-0.5 hidden">0</span>
                <i class="fas fa-chevron-down ml-1 text-gray-400 text-xs" aria-hidden="true"></i>
            </button>
            <!-- Advanced Filter dropdown -->
            <div id="user-filter-dropdown" class="absolute right-0 mt-1 w-80 bg-white rounded-lg shadow-xl hidden z-10 border border-gray-200" role="menu">
                <div class="p-4 max-h-96 overflow-y-auto">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-sliders-h mr-2 text-green-500" aria-hidden="true"></i>
                            User Filters
                        </h4>
                        <button id="close-user-filter-btn" class="text-gray-400 hover:text-gray-600 p-1 rounded">
                            <i class="fas fa-times" aria-hidden="true"></i>
                        </button>
                    </div>
                    
                    <!-- Department Filter -->
                    <div class="mb-6">
                        <h5 class="font-medium text-sm text-gray-700 mb-4 flex items-center">
                            <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center mr-3">
                                <i class="fas fa-building text-blue-600 text-sm" aria-hidden="true"></i>
                            </div>
                            Department
                        </h5>
                        <div class="space-y-3">
                            <label class="flex items-center p-3 rounded-lg hover:bg-blue-50 cursor-pointer transition-all duration-200 border border-transparent hover:border-blue-200">
                                <input type="checkbox" class="rounded text-blue-600 focus:ring-blue-500 h-4 w-4" data-filter="department" data-value="IT">
                                <div class="ml-3 flex-1">
                                    <span class="text-sm font-medium text-gray-700">IT Department</span>
                                    <p class="text-xs text-gray-500">Information Technology</p>
                                </div>
                            </label>
                            <label class="flex items-center p-3 rounded-lg hover:bg-blue-50 cursor-pointer transition-all duration-200 border border-transparent hover:border-blue-200">
                                <input type="checkbox" class="rounded text-blue-600 focus:ring-blue-500 h-4 w-4" data-filter="department" data-value="Security">
                                <div class="ml-3 flex-1">
                                    <span class="text-sm font-medium text-gray-700">Security</span>
                                    <p class="text-xs text-gray-500">Campus Security</p>
                                </div>
                            </label>
                            <label class="flex items-center p-3 rounded-lg hover:bg-blue-50 cursor-pointer transition-all duration-200 border border-transparent hover:border-blue-200">
                                <input type="checkbox" class="rounded text-blue-600 focus:ring-blue-500 h-4 w-4" data-filter="department" data-value="Admin">
                                <div class="ml-3 flex-1">
                                    <span class="text-sm font-medium text-gray-700">Administration</span>
                                    <p class="text-xs text-gray-500">General Administration</p>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Last Login Filter -->
                    <div class="mb-6">
                        <h5 class="font-medium text-sm text-gray-700 mb-4 flex items-center">
                            <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center mr-3">
                                <i class="fas fa-clock text-purple-600 text-sm" aria-hidden="true"></i>
                            </div>
                            Last Login
                        </h5>
                        <div class="space-y-3">
                            <label class="flex items-center p-3 rounded-lg hover:bg-purple-50 cursor-pointer transition-all duration-200 border border-transparent hover:border-purple-200">
                                <input type="checkbox" class="rounded text-purple-600 focus:ring-purple-500 h-4 w-4" data-filter="last-login" data-value="today">
                                <div class="ml-3 flex-1">
                                    <span class="text-sm font-medium text-gray-700">Today</span>
                                    <p class="text-xs text-gray-500">Active today</p>
                                </div>
                            </label>
                            <label class="flex items-center p-3 rounded-lg hover:bg-purple-50 cursor-pointer transition-all duration-200 border border-transparent hover:border-purple-200">
                                <input type="checkbox" class="rounded text-purple-600 focus:ring-purple-500 h-4 w-4" data-filter="last-login" data-value="week">
                                <div class="ml-3 flex-1">
                                    <span class="text-sm font-medium text-gray-700">This Week</span>
                                    <p class="text-xs text-gray-500">Active this week</p>
                                </div>
                            </label>
                            <label class="flex items-center p-3 rounded-lg hover:bg-purple-50 cursor-pointer transition-all duration-200 border border-transparent hover:border-purple-200">
                                <input type="checkbox" class="rounded text-purple-600 focus:ring-purple-500 h-4 w-4" data-filter="last-login" data-value="month">
                                <div class="ml-3 flex-1">
                                    <span class="text-sm font-medium text-gray-700">This Month</span>
                                    <p class="text-xs text-gray-500">Active this month</p>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                        <button id="reset-user-filters-btn" class="px-4 py-2.5 text-sm text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-all duration-200 flex items-center font-medium">
                            <i class="fas fa-undo mr-2" aria-hidden="true"></i>
                            Reset All
                        </button>
                        <button id="apply-user-filters-btn" class="px-5 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 font-medium flex items-center shadow-lg hover:shadow-xl">
                            <i class="fas fa-check mr-2" aria-hidden="true"></i>
                            Apply Filters
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <button 
            onclick="openAddUserModal()"
            class="px-5 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 font-medium flex items-center shadow-lg hover:shadow-xl"
            aria-label="Add new user"
        >
            <i class="fas fa-plus mr-2" aria-hidden="true"></i>
            <span>Add User</span>
        </button>
    </div>
</div>

<!-- scripts moved to public/js/anpr/user-management.js -->
