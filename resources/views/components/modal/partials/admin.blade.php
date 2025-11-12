<div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
            <input type="text" name="first_name" x-model="formData.first_name"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.first_name}" required>
            <p x-show="errors.first_name" x-text="errors.first_name" class="mt-1 text-sm text-red-600"></p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
            <input type="text" name="last_name" x-model="formData.last_name"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.last_name}" required>
            <p x-show="errors.last_name" x-text="errors.last_name" class="mt-1 text-sm text-red-600"></p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
            <input type="email" name="email" x-model="formData.email"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.email}" required>
            <p x-show="errors.email" x-text="errors.email" class="mt-1 text-sm text-red-600"></p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
            <input type="tel" name="phone" x-model="formData.phone"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.phone}" required>
            <p x-show="errors.phone" x-text="errors.phone" class="mt-1 text-sm text-red-600"></p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
            <select name="role" x-model="formData.role"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.role}" required>
                <option value="">Select role</option>
                <option value="super_admin">Super Admin</option>
                <option value="admin">Admin</option>
                <option value="moderator">Moderator</option>
            </select>
            <p x-show="errors.role" x-text="errors.role" class="mt-1 text-sm text-red-600"></p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select name="status" x-model="formData.status"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.status}" required>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
            <p x-show="errors.status" x-text="errors.status" class="mt-1 text-sm text-red-600"></p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input type="password" name="password" x-model="formData.password"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.password}" required>
            <p x-show="errors.password" x-text="errors.password" class="mt-1 text-sm text-red-600"></p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
            <input type="password" name="password_confirmation" x-model="formData.password_confirmation"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                :class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.password_confirmation}"
                required>
            <p x-show="errors.password_confirmation" x-text="errors.password_confirmation"
                class="mt-1 text-sm text-red-600"></p>
        </div>
        <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Permissions</label>
            <div class="grid grid-cols-2 gap-2 mt-2">
                <div class="flex items-center">
                    <input type="checkbox" id="{{ $id }}_perm_applicants" name="permissions[]" value="applicants"
                        x-model="formData.permissions"
                        class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                    <label for="{{ $id }}_perm_applicants" class="ml-2 block text-sm text-gray-700">Manage
                        Applicants</label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" id="{{ $id }}_perm_vehicles" name="permissions[]" value="vehicles"
                        x-model="formData.permissions"
                        class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                    <label for="{{ $id }}_perm_vehicles" class="ml-2 block text-sm text-gray-700">Manage
                        Vehicles</label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" id="{{ $id }}_perm_rfid" name="permissions[]" value="rfid"
                        x-model="formData.permissions"
                        class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                    <label for="{{ $id }}_perm_rfid" class="ml-2 block text-sm text-gray-700">Manage RFID</label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" id="{{ $id }}_perm_admins" name="permissions[]" value="admins"
                        x-model="formData.permissions"
                        class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                    <label for="{{ $id }}_perm_admins" class="ml-2 block text-sm text-gray-700">Manage
                        Administrators</label>
                </div>
            </div>
            <p x-show="errors.permissions" x-text="errors.permissions" class="mt-1 text-sm text-red-600"></p>
        </div>
    </div>
</div>

<div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end space-x-3">
    <button type="button"
        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
        data-cancel-modal="{{ $id }}">
        Cancel
    </button>

    <button type="button"
        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed"
        @click="submitForm" :disabled="isSubmitting">
        <span x-show="!isSubmitting">Save</span>
        <span x-show="isSubmitting" class="flex items-center">
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            Processing...
        </span>
    </button>
</div>