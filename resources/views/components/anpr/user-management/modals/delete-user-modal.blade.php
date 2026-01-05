<div id="delete-user-modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 hidden transition-all duration-300 ease-out" role="dialog" aria-labelledby="delete-user-modal-title">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 sm:mx-6 transform transition-all duration-300 scale-95 opacity-0" id="delete-user-modal-content">
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
        <div class="relative bg-gradient-to-r from-red-50 via-pink-50 to-red-50 border-b border-gray-200 px-6 py-5">
            <div class="absolute inset-0 bg-gradient-to-r from-red-100/20 to-pink-100/20"></div>
            <div class="relative flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-100 to-red-200 flex items-center justify-center shadow-lg">
                            <i class="fas fa-exclamation-triangle text-red-600 text-lg" aria-hidden="true"></i>
                        </div>
                        <div class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full flex items-center justify-center">
                            <span class="text-xs text-white font-bold">!</span>
                        </div>
                    </div>
                    <div>
                        <h3 id="delete-user-modal-title" class="text-xl font-bold text-gray-900">Delete User</h3>
                        <p class="text-sm text-gray-600 mt-1">This action cannot be undone</p>
                    </div>
                </div>
                <button onclick="closeDeleteUserModal()" class="group p-2 rounded-xl hover:bg-gray-100 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2" aria-label="Close modal">
                    <i class="fas fa-times text-gray-400 group-hover:text-gray-600 transition-colors" aria-hidden="true"></i>
                </button>
            </div>
        </div>

        <!-- Enhanced Content -->
        <div class="p-6 animate-fade-in">
            <!-- Warning Icon -->
            <div class="flex justify-center mb-6">
                <div class="w-20 h-20 rounded-full bg-red-100 flex items-center justify-center">
                    <i class="fas fa-user-times text-red-600 text-3xl" aria-hidden="true"></i>
                </div>
            </div>

            <!-- Warning Message -->
            <div class="text-center mb-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-2">Are you sure you want to delete this user?</h4>
                <p class="text-sm text-gray-600 mb-4">
                    This will permanently remove the user account and all associated data from the system.
                </p>
                
                <!-- User Info Card -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 mb-6">
                    <div class="flex items-center justify-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                            <i class="fas fa-user text-green-600" aria-hidden="true"></i>
                        </div>
                        <div class="text-left">
                            <p class="text-sm font-medium text-gray-900" id="delete-user-name">John Doe</p>
                            <p class="text-xs text-gray-500" id="delete-user-email">john.doe@example.com</p>
                            <div class="flex items-center space-x-2 mt-1">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800" id="delete-user-role">
                                    Administrator
                                </span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800" id="delete-user-status">
                                    Active
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Impact Warning -->
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-amber-600 mt-0.5 mr-3" aria-hidden="true"></i>
                        <div class="text-left">
                            <h5 class="text-sm font-medium text-amber-800 mb-1">What will be deleted:</h5>
                            <ul class="text-xs text-amber-700 space-y-1">
                                <li>• User account and login credentials</li>
                                <li>• All user activity logs and history</li>
                                <li>• User permissions and role assignments</li>
                                <li>• Associated profile information</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Confirmation Input -->
            <div class="mb-6">
                <label for="delete-confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                    Type <span class="font-mono text-red-600">DELETE</span> to confirm
                </label>
                <input 
                    type="text" 
                    id="delete-confirmation" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-center font-mono text-sm"
                    placeholder="Type DELETE to confirm"
                    autocomplete="off"
                >
                <p class="text-xs text-gray-500 mt-1 text-center">This action requires explicit confirmation</p>
            </div>

            <!-- Action Buttons -->
            <div class="flex space-x-3">
                <button 
                    type="button" 
                    onclick="closeDeleteUserModal()" 
                    class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200"
                >
                    Cancel
                </button>
                <button 
                    type="button" 
                    id="confirm-delete-btn"
                    onclick="confirmDeleteUser()"
                    disabled
                    class="flex-1 px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-red-600 to-pink-600 rounded-lg hover:from-red-700 hover:to-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none"
                >
                    <i class="fas fa-trash mr-2" aria-hidden="true"></i>
                    Delete User
                </button>
            </div>
        </div>
    </div>
</div>

<!-- scripts moved to public/js/anpr/user-management.js -->
