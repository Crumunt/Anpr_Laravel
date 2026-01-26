@props(['user' => null])

<div id="view-user-modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 hidden transition-all duration-300 ease-out" role="dialog" aria-labelledby="view-user-modal-title">
    <div class="bg-white rounded-2xl shadow-2xl max-w-5xl w-full mx-4 sm:mx-6 max-h-[95vh] overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="view-user-modal-content">
        <!-- Enhanced Header with Live Status -->
        <div class="relative bg-gradient-to-r from-green-50 via-emerald-50 to-green-50 border-b border-gray-200 px-6 py-5">
            <div class="absolute inset-0 bg-gradient-to-r from-green-100/20 to-emerald-100/20"></div>
            <div class="relative flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center shadow-lg">
                            <i class="fas fa-user text-green-600 text-lg" aria-hidden="true"></i>
                        </div>
                        <div class="absolute -top-1 -right-1 w-4 h-4 bg-green-500 rounded-full flex items-center justify-center">
                            <span class="text-xs text-white font-bold">i</span>
                        </div>
                        <!-- Live indicator -->
                        <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-500 rounded-full border-2 border-white animate-pulse"></div>
                    </div>
                    <div>
                        <h3 id="view-user-modal-title" class="text-xl font-bold text-gray-900 flex items-center">
                            User Details
                            @if($user)
                                <span class="ml-2 text-sm font-normal text-gray-500">#{{ $user['id'] }}</span>
                                <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 animate-pulse">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1"></span>
                                    ACTIVE
                                </span>
                            @endif
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">Comprehensive user information and account details</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <!-- Real-time clock -->
                    <div class="hidden sm:flex items-center space-x-2 bg-white/80 backdrop-blur-sm rounded-lg px-3 py-2 border border-gray-200">
                        <i class="fas fa-clock text-gray-400 text-sm"></i>
                        <span class="text-sm font-medium text-gray-700" id="user-live-clock"></span>
                    </div>
                    <button onclick="closeViewUserModal()" class="group p-2 rounded-xl hover:bg-gray-100 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2" aria-label="Close modal">
                        <i class="fas fa-times text-gray-400 group-hover:text-gray-600 transition-colors" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Enhanced Content -->
        <div class="overflow-y-auto p-6 max-h-[calc(95vh-140px)] scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
            <div class="animate-fade-in space-y-6">
                <!-- Enhanced User Status Banner -->
                <div class="bg-gradient-to-r from-green-50 via-emerald-50 to-green-50 border border-green-200 rounded-xl p-4 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-green-100/20 to-emerald-100/20"></div>
                    <div class="relative flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                                <span class="text-sm font-medium text-green-800">Active User</span>
                            </div>
                            <span class="text-gray-400">|</span>
                            <span class="text-sm text-gray-600">Member since {{ $user ? $user['createdAt'] : 'N/A' }}</span>
                            <span class="text-gray-400">|</span>
                            <span class="text-sm text-gray-600">Last login: <span id="user-last-login" class="font-semibold">{{ $user ? $user['lastLogin'] : 'Never' }}</span></span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200 animate-pulse">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-1.5"></span>
                                {{ $user ? ucfirst($user['role']) : 'User' }} Role
                            </span>
                            <!-- Response time indicator -->
                            <div class="flex items-center space-x-1 bg-green-100 rounded-full px-2 py-1">
                                <i class="fas fa-check-circle text-green-600 text-xs"></i>
                                <span class="text-xs font-medium text-green-800">Verified</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left Column - User Information -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Enhanced User Information Card -->
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-lg transition-shadow duration-300">
                            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                                <h4 class="text-lg font-semibold text-gray-900 flex items-center justify-between">
                                    <div class="flex items-center">
                                        <i class="fas fa-user text-blue-500 mr-2"></i>
                                        Personal Information
                                    </div>
                                    <button class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center space-x-1 transition-colors">
                                        <i class="fas fa-edit"></i>
                                        <span>Edit</span>
                                    </button>
                                </h4>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- User Avatar -->
                                    <div class="relative group">
                                        <div class="w-32 h-32 mx-auto rounded-full bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center shadow-lg border-4 border-white">
                                            <i class="fas fa-user text-4xl text-green-600" aria-hidden="true"></i>
                                        </div>
                                        <div class="absolute inset-0 rounded-full bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-200 flex items-center justify-center">
                                            <i class="fas fa-camera text-white text-xl opacity-0 group-hover:opacity-100 transition-opacity duration-200"></i>
                                        </div>
                                    </div>
                                    
                                    <!-- User Details -->
                                    <div class="space-y-4">
                                        <div>
                                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Full Name</label>
                                            <p class="text-lg font-semibold text-gray-900">{{ $user ? $user['name'] : 'John Doe' }}</p>
                                        </div>
                                        <div>
                                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Email Address</label>
                                            <p class="text-sm text-gray-700">{{ $user ? $user['email'] : 'john.doe@example.com' }}</p>
                                        </div>
                                        <div>
                                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Phone Number</label>
                                            <p class="text-sm text-gray-700">{{ $user ? ($user['phone'] ?? '+1 (555) 123-4567') : '+1 (555) 123-4567' }}</p>
                                        </div>
                                        <div>
                                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Department</label>
                                            <p class="text-sm text-gray-700">{{ $user ? ($user['department'] ?? 'IT Department') : 'IT Department' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Account Activity Card -->
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                            <div class="bg-gradient-to-r from-purple-50 to-purple-100 px-6 py-4 border-b border-gray-200">
                                <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                                    <i class="fas fa-chart-line text-purple-500 mr-2"></i>
                                    Account Activity
                                </h4>
                            </div>
                            <div class="p-6">
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-sign-in-alt text-green-600"></i>
                                            </div>
                                            <div class="ml-4">
                                                <p class="text-sm font-medium text-gray-900">Last Login</p>
                                                <p class="text-xs text-gray-500">{{ $user ? $user['lastLogin'] : 'Never logged in' }}</p>
                                            </div>
                                        </div>
                                        <span class="text-xs text-gray-400">2 hours ago</span>
                                    </div>
                                    
                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-key text-blue-600"></i>
                                            </div>
                                            <div class="ml-4">
                                                <p class="text-sm font-medium text-gray-900">Password Changed</p>
                                                <p class="text-xs text-gray-500">Last changed 30 days ago</p>
                                            </div>
                                        </div>
                                        <span class="text-xs text-gray-400">30 days ago</span>
                                    </div>
                                    
                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-user-edit text-purple-600"></i>
                                            </div>
                                            <div class="ml-4">
                                                <p class="text-sm font-medium text-gray-900">Profile Updated</p>
                                                <p class="text-xs text-gray-500">Information last updated</p>
                                            </div>
                                        </div>
                                        <span class="text-xs text-gray-400">1 week ago</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Quick Actions & Stats -->
                    <div class="space-y-6">
                        <!-- Quick Actions Card -->
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                            <div class="bg-gradient-to-r from-amber-50 to-orange-50 px-6 py-4 border-b border-gray-200">
                                <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                                    <i class="fas fa-bolt text-amber-500 mr-2"></i>
                                    Quick Actions
                                </h4>
                            </div>
                            <div class="p-4 space-y-2">
                                <button onclick="quickEditUser()" class="w-full flex items-center justify-between p-3 text-left hover:bg-blue-50 rounded-lg transition-colors duration-200 group">
                                    <div class="flex items-center">
                                        <i class="fas fa-edit text-blue-600 mr-3 group-hover:text-blue-700"></i>
                                        <span class="text-sm font-medium text-gray-700 group-hover:text-blue-800">Edit User</span>
                                    </div>
                                    <i class="fas fa-chevron-right text-gray-400 text-xs group-hover:text-blue-500"></i>
                                </button>
                                
                                <button onclick="quickResetPassword()" class="w-full flex items-center justify-between p-3 text-left hover:bg-green-50 rounded-lg transition-colors duration-200 group">
                                    <div class="flex items-center">
                                        <i class="fas fa-key text-green-600 mr-3 group-hover:text-green-700"></i>
                                        <span class="text-sm font-medium text-gray-700 group-hover:text-green-800">Reset Password</span>
                                    </div>
                                    <i class="fas fa-chevron-right text-gray-400 text-xs group-hover:text-green-500"></i>
                                </button>
                                
                                <button onclick="quickSuspendUser()" class="w-full flex items-center justify-between p-3 text-left hover:bg-amber-50 rounded-lg transition-colors duration-200 group">
                                    <div class="flex items-center">
                                        <i class="fas fa-ban text-amber-600 mr-3 group-hover:text-amber-700"></i>
                                        <span class="text-sm font-medium text-gray-700 group-hover:text-amber-800">Suspend Account</span>
                                    </div>
                                    <i class="fas fa-chevron-right text-gray-400 text-xs group-hover:text-amber-500"></i>
                                </button>
                                
                                <button onclick="quickDeleteUser()" class="w-full flex items-center justify-between p-3 text-left hover:bg-red-50 rounded-lg transition-colors duration-200 group">
                                    <div class="flex items-center">
                                        <i class="fas fa-trash text-red-600 mr-3 group-hover:text-red-700"></i>
                                        <span class="text-sm font-medium text-red-700 group-hover:text-red-800">Delete User</span>
                                    </div>
                                    <i class="fas fa-chevron-right text-red-400 text-xs group-hover:text-red-500"></i>
                                </button>
                            </div>
                        </div>

                        <!-- User Statistics Card -->
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 px-6 py-4 border-b border-gray-200">
                                <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                                    <i class="fas fa-chart-bar text-indigo-500 mr-2"></i>
                                    User Statistics
                                </h4>
                            </div>
                            <div class="p-4 space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Login Sessions</span>
                                    <span class="text-sm font-semibold text-gray-900">156</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Days Active</span>
                                    <span class="text-sm font-semibold text-gray-900">89</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Actions Performed</span>
                                    <span class="text-sm font-semibold text-gray-900">1,247</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Account Age</span>
                                    <span class="text-sm font-semibold text-gray-900">2.5 years</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Enhanced modal animations matching flagged details modal
function openViewUserModal(userId = null) {
    const modal = document.getElementById('view-user-modal');
    const modalContent = document.getElementById('view-user-modal-content');
    
    if (modal && modalContent) {
        // Set user ID for viewing
        modal.setAttribute('data-user-id', userId);
        
        // Load user data for display
        loadViewUserData(userId);
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Trigger animation
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
        
        // Start live clock
        startLiveClock();
    }
}

function closeViewUserModal() {
    const modal = document.getElementById('view-user-modal');
    const modalContent = document.getElementById('view-user-modal-content');
    
    if (modal && modalContent) {
        // Reverse animation
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }, 300);
    }
}

function loadViewUserData(userId) {
    // Simulate loading user data
    const userData = {
        id: userId,
        name: 'John Doe',
        email: 'john.doe@example.com',
        role: 'Administrator',
        status: 'Active',
        department: 'IT Department',
        phone: '+1 (555) 123-4567',
        createdAt: 'June 15, 2023',
        lastLogin: '2 hours ago'
    };
    
    // Update display elements if they exist
    const nameElements = document.querySelectorAll('#view-user-modal [id*="user-name"], #view-user-modal [id*="user-email"]');
    nameElements.forEach(element => {
        if (element.id.includes('name')) {
            element.textContent = userData.name;
        } else if (element.id.includes('email')) {
            element.textContent = userData.email;
        }
    });
}

function startLiveClock() {
    const clockElement = document.getElementById('user-live-clock');
    if (clockElement) {
        const updateClock = () => {
            const now = new Date();
            clockElement.textContent = now.toLocaleTimeString('en-US', { 
                hour12: true, 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit' 
            });
        };
        
        updateClock();
        setInterval(updateClock, 1000);
    }
}

// Quick action functions
function quickEditUser() {
    const modal = document.getElementById('view-user-modal');
    const userId = modal.getAttribute('data-user-id');
    
    // Close view modal
    closeViewUserModal();
    
    // Open edit modal
    setTimeout(() => {
        openEditUserModal(userId);
    }, 300);
}

function quickResetPassword() {
    const modal = document.getElementById('view-user-modal');
    const userId = modal.getAttribute('data-user-id');
    
    // Show confirmation dialog
    if (confirm('Are you sure you want to reset the password for this user? A new password will be sent to their email.')) {
        // Simulate password reset
        showToast('Password Reset', 'Password reset email has been sent to the user.', 'success');
        
        // Close view modal
        closeViewUserModal();
    }
}

function quickSuspendUser() {
    const modal = document.getElementById('view-user-modal');
    const userId = modal.getAttribute('data-user-id');
    
    // Show confirmation dialog
    if (confirm('Are you sure you want to suspend this user account? They will not be able to access the system.')) {
        // Simulate account suspension
        showToast('Account Suspended', 'User account has been suspended successfully.', 'warning');
        
        // Close view modal
        closeViewUserModal();
    }
}

function quickDeleteUser() {
    const modal = document.getElementById('view-user-modal');
    const userId = modal.getAttribute('data-user-id');
    
    // Close view modal
    closeViewUserModal();
    
    // Open delete modal
    setTimeout(() => {
        openDeleteUserModal(userId);
    }, 300);
}

// Toast notification function for quick actions
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

// Close modal on outside click
document.addEventListener('click', function(e) {
    const modal = document.getElementById('view-user-modal');
    const modalContent = document.getElementById('view-user-modal-content');
    
    if (e.target === modal) {
        closeViewUserModal();
    }
});

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeViewUserModal();
    }
});
</script>
