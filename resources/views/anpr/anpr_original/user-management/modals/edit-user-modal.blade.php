<div id="edit-user-modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 hidden transition-all duration-300 ease-out" role="dialog" aria-labelledby="edit-user-modal-title">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-4 sm:mx-6 max-h-[95vh] overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="edit-user-modal-content">
        <!-- Enhanced Header -->
        <div class="relative bg-gradient-to-r from-blue-50 via-indigo-50 to-blue-50 border-b border-gray-200 px-6 py-5">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-100/20 to-indigo-100/20"></div>
            <div class="relative flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center shadow-lg">
                            <i class="fas fa-user-edit text-blue-600 text-lg" aria-hidden="true"></i>
                        </div>
                        <div class="absolute -top-1 -right-1 w-4 h-4 bg-blue-500 rounded-full flex items-center justify-center">
                            <span class="text-xs text-white font-bold">✏</span>
                        </div>
                    </div>
                    <div>
                        <h3 id="edit-user-modal-title" class="text-xl font-bold text-gray-900">Edit User</h3>
                        <p class="text-sm text-gray-600 mt-1">Update user information and permissions</p>
                    </div>
                </div>
                <button onclick="closeEditUserModal()" class="group p-2 rounded-xl hover:bg-gray-100 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" aria-label="Close modal">
                    <i class="fas fa-times text-gray-400 group-hover:text-gray-600 transition-colors" aria-hidden="true"></i>
                </button>
            </div>
        </div>

        <!-- Enhanced Content -->
        <div class="overflow-y-auto p-6 max-h-[calc(95vh-140px)] scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
            <form id="edit-user-form" class="animate-fade-in space-y-6">
                <input type="hidden" id="edit-user-id" name="user_id">
                
                                 <!-- Personal Information Section -->
                 <div>
                     <h4 class="text-sm font-bold text-gray-800 mb-4 uppercase tracking-wider flex items-center bg-gradient-to-r from-blue-50 to-indigo-50 px-4 py-2 rounded-lg border-l-4 border-blue-500">
                         <i class="fas fa-user text-blue-600 mr-3"></i>
                         Personal Information
                     </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                 <div class="relative">
                             <label for="edit-full-name" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                 <i class="fas fa-user text-blue-500 mr-2"></i>
                                 Full Name <span class="text-red-500 ml-1">*</span>
                             </label>
                             <div class="relative">
                                 <i class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 text-blue-500" aria-hidden="true"></i>
                                 <input 
                                     type="text" 
                                     id="edit-full-name" 
                                     name="full_name"
                                     required
                                     class="pl-10 w-full border-2 border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 invalid:border-red-500 invalid:ring-red-500 py-2.5 px-3 text-gray-700 transition-all duration-200 bg-white input-focus-effect"
                                     placeholder="Enter full name"
                                 >
                             </div>
                             <div class="text-red-500 text-xs mt-1 hidden" id="edit-full-name-error"></div>
                         </div>
                         
                         <div class="relative">
                             <label for="edit-email" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                 <i class="fas fa-envelope text-blue-500 mr-2"></i>
                                 Email Address <span class="text-red-500 ml-1">*</span>
                             </label>
                             <div class="relative">
                                 <i class="fas fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-blue-500" aria-hidden="true"></i>
                                 <input 
                                     type="email" 
                                     id="edit-email" 
                                     name="email"
                                     required
                                     class="pl-10 w-full border-2 border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 invalid:border-red-500 invalid:ring-red-500 py-2.5 px-3 text-gray-700 transition-all duration-200 bg-white input-focus-effect"
                                     placeholder="Enter email address"
                                 >
                             </div>
                             <div class="text-red-500 text-xs mt-1 hidden" id="edit-email-error"></div>
                         </div>
                         
                         <div class="relative">
                             <label for="edit-phone" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                 <i class="fas fa-phone text-blue-500 mr-2"></i>
                                 Phone Number
                             </label>
                             <div class="relative">
                                 <i class="fas fa-phone absolute left-3 top-1/2 -translate-y-1/2 text-blue-500" aria-hidden="true"></i>
                                 <input 
                                     type="tel" 
                                     id="edit-phone" 
                                     name="phone"
                                     class="pl-10 w-full border-2 border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 py-2.5 px-3 text-gray-700 transition-all duration-200 bg-white input-focus-effect"
                                     placeholder="Enter phone number"
                                 >
                             </div>
                         </div>
                         
                         <div class="relative">
                             <label for="edit-department" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                 <i class="fas fa-building text-blue-500 mr-2"></i>
                                 Department
                             </label>
                             <div class="relative">
                                 <i class="fas fa-building absolute left-3 top-1/2 -translate-y-1/2 text-blue-500" aria-hidden="true"></i>
                                 <input 
                                     type="text" 
                                     id="edit-department" 
                                     name="department"
                                     class="pl-10 w-full border-2 border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 py-2.5 px-3 text-gray-700 transition-all duration-200 bg-white input-focus-effect"
                                     placeholder="Enter department"
                                 >
                             </div>
                         </div>
                    </div>
                </div>

                                 <!-- Account Settings Section -->
                 <div>
                     <h4 class="text-sm font-bold text-gray-800 mb-4 uppercase tracking-wider flex items-center bg-gradient-to-r from-indigo-50 to-purple-50 px-4 py-2 rounded-lg border-l-4 border-indigo-500">
                         <i class="fas fa-cog text-indigo-600 mr-3"></i>
                         Account Settings
                     </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                 <div class="relative">
                             <label for="edit-role" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                 <i class="fas fa-user-shield text-indigo-500 mr-2"></i>
                                 Role <span class="text-red-500 ml-1">*</span>
                             </label>
                             <div class="relative">
                                 <i class="fas fa-user-shield absolute left-3 top-1/2 -translate-y-1/2 text-indigo-500" aria-hidden="true"></i>
                                 <select 
                                     id="edit-role" 
                                     name="role"
                                     required
                                     class="pl-10 w-full border-2 border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 py-2.5 px-3 text-gray-700 transition-all duration-200 bg-white appearance-none cursor-pointer enhanced-select input-focus-effect"
                                 >
                                     <option value="" class="text-gray-400">Select a role</option>
                                     <option value="admin" class="text-gray-700">👑 Administrator</option>
                                     <option value="operator" class="text-gray-700">⚙️ Operator</option>
                                     <option value="viewer" class="text-gray-700">👁️ Viewer</option>
                                 </select>
                                 <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" aria-hidden="true"></i>
                             </div>
                             <div class="text-red-500 text-xs mt-1 hidden" id="edit-role-error"></div>
                         </div>
                         
                         <div class="relative">
                             <label for="edit-status" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                 <i class="fas fa-toggle-on text-indigo-500 mr-2"></i>
                                 Status <span class="text-red-500 ml-1">*</span>
                             </label>
                             <div class="relative">
                                 <i class="fas fa-toggle-on absolute left-3 top-1/2 -translate-y-1/2 text-indigo-500" aria-hidden="true"></i>
                                 <select 
                                     id="edit-status" 
                                     name="status"
                                     required
                                     class="pl-10 w-full border-2 border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 py-2.5 px-3 text-gray-700 transition-all duration-200 bg-white appearance-none cursor-pointer enhanced-select input-focus-effect"
                                 >
                                     <option value="" class="text-gray-400">Select status</option>
                                     <option value="active" class="text-gray-700">🟢 Active</option>
                                     <option value="inactive" class="text-gray-700">🔴 Inactive</option>
                                     <option value="pending" class="text-gray-700">🟡 Pending</option>
                                 </select>
                                 <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" aria-hidden="true"></i>
                             </div>
                             <div class="text-red-500 text-xs mt-1 hidden" id="edit-status-error"></div>
                         </div>
                    </div>
                </div>

                                 <!-- Password Management Section -->
                 <div>
                     <h4 class="text-sm font-bold text-gray-800 mb-4 uppercase tracking-wider flex items-center bg-gradient-to-r from-amber-50 to-orange-50 px-4 py-2 rounded-lg border-l-4 border-amber-500">
                         <i class="fas fa-key text-amber-600 mr-3"></i>
                         Password Management
                     </h4>
                                         <div class="space-y-4">
                         <div class="flex items-center p-4 bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl border border-amber-200 shadow-sm hover:shadow-md transition-all duration-200">
                             <input 
                                 type="checkbox" 
                                 id="reset-password" 
                                 name="reset_password"
                                 class="custom-checkbox"
                             >
                             <label for="reset-password" class="ml-4 block text-sm font-semibold text-amber-800 flex items-center">
                                 <i class="fas fa-key text-amber-600 mr-2"></i>
                                 Reset password and send email to user
                             </label>
                         </div>
                                                 <div id="password-fields" class="hidden space-y-4 p-5 bg-gradient-to-br from-amber-50 via-orange-50 to-yellow-50 rounded-xl border border-amber-200 shadow-inner">
                             <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                 <div class="relative">
                                     <label for="new-password" class="block text-sm font-semibold text-amber-800 mb-2 flex items-center">
                                         <i class="fas fa-lock text-amber-600 mr-2"></i>
                                         New Password <span class="text-red-500 ml-1">*</span>
                                     </label>
                                     <div class="relative">
                                         <i class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-amber-500" aria-hidden="true"></i>
                                         <input 
                                             type="password" 
                                             id="new-password" 
                                             name="new_password"
                                             class="pl-10 w-full border-2 border-amber-200 rounded-lg shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 py-2.5 px-3 text-gray-700 transition-all duration-200 bg-white input-focus-effect"
                                             placeholder="Enter new password"
                                         >
                                     </div>
                                 </div>
                                 <div class="relative">
                                     <label for="confirm-password" class="block text-sm font-semibold text-amber-800 mb-2 flex items-center">
                                         <i class="fas fa-lock text-amber-600 mr-2"></i>
                                         Confirm Password <span class="text-red-500 ml-1">*</span>
                                     </label>
                                     <div class="relative">
                                         <i class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-amber-500" aria-hidden="true"></i>
                                         <input 
                                             type="password" 
                                             id="confirm-password" 
                                     name="confirm_password"
                                     class="pl-10 w-full border-2 border-amber-200 rounded-lg shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 py-2.5 px-3 text-gray-700 transition-all duration-200 bg-white input-focus-effect"
                                     placeholder="Confirm new password"
                                 >
                                     </div>
                                 </div>
                             </div>
                             <div class="text-sm text-amber-700 bg-white/80 p-3 rounded-lg border border-amber-200 shadow-sm">
                                 <i class="fas fa-info-circle text-amber-600 mr-2"></i>
                                 <span class="font-medium">Password Requirements:</span> Must be at least 8 characters long and contain a mix of letters, numbers, and symbols.
                             </div>
                         </div>
                    </div>
                </div>
            </form>
        </div>

                 <!-- Enhanced Footer -->
         <div class="flex items-center justify-between gap-3 p-6 border-t border-gray-200 bg-gradient-to-r from-gray-50 to-blue-50">
             <div class="text-sm text-gray-600 bg-white/80 px-3 py-2 rounded-lg border border-gray-200 shadow-sm">
                 <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                 Fields marked with <span class="text-red-500 font-semibold">*</span> are required
             </div>
             <div class="flex items-center gap-3">
                 <button 
                     type="button"
                     onclick="closeEditUserModal()"
                     class="px-6 py-2.5 text-gray-700 bg-white border-2 border-gray-200 rounded-lg hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200 font-medium"
                 >
                     <i class="fas fa-times mr-2"></i>
                     Cancel
                 </button>
                 <button 
                     type="submit"
                     form="edit-user-form"
                     class="px-8 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center gap-2 font-semibold"
                     id="edit-user-submit"
                 >
                     <i class="fas fa-save" aria-hidden="true"></i>
                     <span>Save Changes</span>
                 </button>
             </div>
         </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px) scale(0.98); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    .animate-fade-in {
        animation: fadeIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    /* Custom checkbox styles */
    .custom-checkbox {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        width: 20px;
        height: 20px;
        border: 2px solid #d1d5db;
        border-radius: 6px;
        background: white;
        cursor: pointer;
        position: relative;
        transition: all 0.2s ease;
    }
    
    .custom-checkbox:checked {
        background: linear-gradient(135deg, #f59e0b, #f97316);
        border-color: #f59e0b;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
    }
    
    .custom-checkbox:checked::after {
        content: '✓';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 12px;
        font-weight: bold;
    }
    
    .custom-checkbox:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.2);
    }
    
    .custom-checkbox:hover:not(:checked) {
        border-color: #f59e0b;
        background: #fef3c7;
    }
    
    /* Enhanced input focus effects */
    .input-focus-effect:focus {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
    }
    
    /* Enhanced select styling */
    .enhanced-select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
    }
    
    /* Smooth transitions for all interactive elements */
    * {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
</style>

<script>

// Edit user modal functionality
function openEditUserModal(userId = null) {
    const modal = document.getElementById('edit-user-modal');
    const modalContent = document.getElementById('edit-user-modal-content');
    
    if (modal && modalContent) {
        // Set user ID for editing
        modal.setAttribute('data-user-id', userId);
        
        // Load user data for display
        loadUserData(userId);
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Trigger animation
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
        
        // Focus on first input
        const firstInput = modal.querySelector('input');
        if (firstInput) {
            setTimeout(() => firstInput.focus(), 300);
        }
    }
}

function closeEditUserModal() {
    const modal = document.getElementById('edit-user-modal');
    const modalContent = document.getElementById('edit-user-modal-content');
    
    if (modal && modalContent) {
        // Reverse animation
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
            
            // Reset form
            const form = document.getElementById('edit-user-form');
            if (form) {
                form.reset();
                clearEditErrors();
                hidePasswordFields();
            }
        }, 300);
    }
}

function clearEditErrors() {
    const errorElements = document.querySelectorAll('[id^="edit-"][id$="-error"]');
    errorElements.forEach(element => {
        element.classList.add('hidden');
        element.textContent = '';
    });
}

function showEditError(fieldId, message) {
    const errorElement = document.getElementById('edit-' + fieldId + '-error');
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.classList.remove('hidden');
    }
}

function hidePasswordFields() {
    const passwordFields = document.getElementById('password-fields');
    if (passwordFields) {
        passwordFields.classList.add('hidden');
    }
}

function showPasswordFields() {
    const passwordFields = document.getElementById('password-fields');
    if (passwordFields) {
        passwordFields.classList.remove('hidden');
    }
}

// Load user data for editing
function loadUserData(userId) {
    // Find the user row in the table
    const userRow = document.querySelector(`#users-table tr[data-user-id="${userId}"]`);
    
    if (userRow) {
        // Extract data from the table row
        const userData = {
            id: userId,
            name: userRow.querySelector('[data-name]')?.getAttribute('data-name') || '',
            email: userRow.querySelector('[data-email]')?.getAttribute('data-email') || '',
            role: userRow.querySelector('[data-role]')?.getAttribute('data-role') || '',
            department: userRow.querySelector('[data-department]')?.getAttribute('data-department') || '',
            phone: userRow.querySelector('[data-phone]')?.getAttribute('data-phone') || '',
            status: userRow.querySelector('[data-status]')?.getAttribute('data-status') || ''
        };
        
        // Populate form fields
        document.getElementById('edit-user-id').value = userData.id;
        document.getElementById('edit-full-name').value = userData.name;
        document.getElementById('edit-email').value = userData.email;
        document.getElementById('edit-role').value = userData.role;
        document.getElementById('edit-department').value = userData.department;
        document.getElementById('edit-phone').value = userData.phone;
        document.getElementById('edit-status').value = userData.status;
    } else {
        // Fallback to sample data if row not found
        const userData = {
            id: userId,
            name: 'John Doe',
            email: 'john.doe@clsu.edu.ph',
            role: 'admin',
            department: 'IT Department',
            phone: '+63 912 345 6789',
            status: 'active'
        };
        
        // Populate form fields
        document.getElementById('edit-user-id').value = userData.id;
        document.getElementById('edit-full-name').value = userData.name;
        document.getElementById('edit-email').value = userData.email;
        document.getElementById('edit-role').value = userData.role;
        document.getElementById('edit-department').value = userData.department;
        document.getElementById('edit-phone').value = userData.phone;
        document.getElementById('edit-status').value = userData.status;
    }
}

// Form submission
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('edit-user-form');
    const submitBtn = document.getElementById('edit-user-submit');
    const resetPasswordCheckbox = document.getElementById('reset-password');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Clear previous errors
            clearEditErrors();
            
            // Get form data
            const formData = new FormData(form);
            const data = Object.fromEntries(formData);
            
            // Basic validation
            let hasErrors = false;
            
            if (!data.full_name.trim()) {
                showEditError('full-name', 'Full name is required');
                hasErrors = true;
            }
            
            if (!data.email.trim()) {
                showEditError('email', 'Email is required');
                hasErrors = true;
            } else if (!isValidEmail(data.email)) {
                showEditError('email', 'Please enter a valid email address');
                hasErrors = true;
            }
            
            if (!data.role) {
                showEditError('role', 'Role is required');
                hasErrors = true;
            }
            
            if (!data.status) {
                showEditError('status', 'Status is required');
                hasErrors = true;
            }
            
            // Password validation if reset is checked
            if (data.reset_password) {
                if (!data.new_password) {
                    showEditError('new-password', 'New password is required');
                    hasErrors = true;
                } else if (data.new_password.length < 8) {
                    showEditError('new-password', 'Password must be at least 8 characters');
                    hasErrors = true;
                }
                
                if (data.new_password !== data.confirm_password) {
                    showEditError('confirm-password', 'Passwords do not match');
                    hasErrors = true;
                }
            }
            
            if (hasErrors) {
                return;
            }
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
            
            // Submit form
            updateUser(data);
        });
    }
    
    // Password reset checkbox handler
    if (resetPasswordCheckbox) {
        resetPasswordCheckbox.addEventListener('change', function() {
            if (this.checked) {
                showPasswordFields();
            } else {
                hidePasswordFields();
            }
        });
    }
});

function updateUser(data) {
    // Simulate API call
    setTimeout(() => {
        // Reset button
        const submitBtn = document.getElementById('edit-user-submit');
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-save"></i> Save Changes';
        
        // Update the table row with new data
        const userId = data.user_id;
        const userRow = document.querySelector(`#users-table tr[data-user-id="${userId}"]`);
        
        if (userRow) {
            // Update data attributes
            if (userRow.querySelector('[data-name]')) {
                userRow.querySelector('[data-name]').setAttribute('data-name', data.full_name);
            }
            if (userRow.querySelector('[data-email]')) {
                userRow.querySelector('[data-email]').setAttribute('data-email', data.email);
            }
            if (userRow.querySelector('[data-role]')) {
                userRow.querySelector('[data-role]').setAttribute('data-role', data.role);
            }
            if (userRow.querySelector('[data-department]')) {
                userRow.querySelector('[data-department]').setAttribute('data-department', data.department);
            }
            if (userRow.querySelector('[data-phone]')) {
                userRow.querySelector('[data-phone]').setAttribute('data-phone', data.phone);
            }
            if (userRow.querySelector('[data-status]')) {
                userRow.querySelector('[data-status]').setAttribute('data-status', data.status);
            }
            
            // Update visible text in the table
            const nameCell = userRow.querySelector('td:nth-child(1) .text-sm.font-medium.text-gray-900');
            if (nameCell) {
                nameCell.textContent = data.full_name;
            }
            
            const emailCell = userRow.querySelector('td:nth-child(1) .text-xs.text-gray-500');
            if (emailCell) {
                emailCell.textContent = data.email;
            }
            
            const roleCell = userRow.querySelector('td:nth-child(2) .text-sm.font-medium.text-gray-900');
            if (roleCell) {
                roleCell.textContent = data.role === 'admin' ? 'Administrator' : 
                                     data.role === 'operator' ? 'Operator' : 'Viewer';
            }
            
            const departmentCell = userRow.querySelector('td:nth-child(3) .text-sm.font-medium.text-gray-900');
            if (departmentCell) {
                departmentCell.textContent = data.department || 'IT Department';
            }
            
            const statusCell = userRow.querySelector('td:nth-child(4) .status-badge');
            if (statusCell) {
                statusCell.textContent = data.status.charAt(0).toUpperCase() + data.status.slice(1);
                statusCell.className = `status-badge px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${
                    data.status === 'active' ? 'bg-green-100 text-green-800' :
                    data.status === 'inactive' ? 'bg-red-100 text-red-800' :
                    'bg-yellow-100 text-yellow-800'
                }`;
            }
        }
        
        // Close modal
        closeEditUserModal();
        
        // Show success message
        showToast('User Updated', 'User information has been updated successfully!', 'success');
    }, 1000);
}

// Toast notification function
function showToast(title, message, type = 'info', duration = 5000) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
        type === 'success' ? 'bg-green-500 text-white' : 
        type === 'error' ? 'bg-red-500 text-white' : 
        type === 'warning' ? 'bg-amber-500 text-white' :
        'bg-blue-500 text-white'
    }`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${type === 'success' ? 'fa-check' : type === 'error' ? 'fa-exclamation-triangle' : type === 'warning' ? 'fa-exclamation-circle' : 'fa-info-circle'} mr-2"></i>
            <div>
                <div class="font-semibold">${title}</div>
                <div class="text-sm opacity-90">${message}</div>
            </div>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Remove after duration
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, duration);
}

// Email validation function
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Notification function for compatibility
function showNotification(message, type = 'info') {
    showToast('Notification', message, type);
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('edit-user-modal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeEditUserModal();
            }
        });
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('edit-user-modal');
        if (modal && !modal.classList.contains('hidden')) {
            closeEditUserModal();
        }
    }
});
</script>
