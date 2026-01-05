@props([
    'totalUsers' => 156,
    'activeUsers' => 142,
    'pendingUsers' => 8,
    'adminUsers' => 12
])

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
    <!-- Total Users Card -->
    <div class="glass-card p-6 relative overflow-hidden">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Users</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalUsers) }}</h3>
            </div>
            <div class="p-3 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg, #e6f7ef, #d1f0e2);">
                <i class="fas fa-users text-xl clsu-text" aria-hidden="true"></i>
            </div>
        </div>
        <div class="flex items-center mt-5">
            <span class="text-green-600 text-sm font-medium flex items-center">
                <i class="fas fa-arrow-up mr-1" aria-hidden="true"></i> 12%
            </span>
            <span class="text-gray-500 text-sm ml-2">from last month</span>
        </div>
        <div class="w-full bg-gray-100 rounded-full h-1.5 mt-4 overflow-hidden" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100">
            <div class="clsu-bg h-1.5 rounded-full progress-bar" style="width: 85%"></div>
        </div>
    </div>

    <!-- Active Users Card -->
    <div class="glass-card p-6 relative overflow-hidden">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Active Users</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($activeUsers) }}</h3>
            </div>
            <div class="p-3 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg, #dcfce7, #bbf7d0);">
                <i class="fas fa-user-check text-xl text-green-600" aria-hidden="true"></i>
            </div>
        </div>
        <div class="flex items-center mt-5">
            <span class="text-green-600 text-sm font-medium flex items-center">
                <i class="fas fa-arrow-up mr-1" aria-hidden="true"></i> 8%
            </span>
            <span class="text-gray-500 text-sm ml-2">from last month</span>
        </div>
        <div class="w-full bg-gray-100 rounded-full h-1.5 mt-4 overflow-hidden" role="progressbar" aria-valuenow="91" aria-valuemin="0" aria-valuemax="100">
            <div class="bg-green-500 h-1.5 rounded-full progress-bar" style="width: 91%"></div>
        </div>
    </div>

    <!-- Pending Users Card -->
    <div class="glass-card p-6 relative overflow-hidden">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Pending Approval</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($pendingUsers) }}</h3>
            </div>
            <div class="p-3 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg, #fef3c7, #fde68a);">
                <i class="fas fa-user-clock text-xl text-amber-600" aria-hidden="true"></i>
            </div>
        </div>
        <div class="flex items-center mt-5">
            <span class="text-amber-600 text-sm font-medium flex items-center">
                <i class="fas fa-clock mr-1" aria-hidden="true"></i> Awaiting
            </span>
            <span class="text-gray-500 text-sm ml-2">review</span>
        </div>
        <div class="w-full bg-gray-100 rounded-full h-1.5 mt-4 overflow-hidden" role="progressbar" aria-valuenow="5" aria-valuemin="0" aria-valuemax="100">
            <div class="bg-amber-500 h-1.5 rounded-full progress-bar" style="width: 5%"></div>
        </div>
    </div>

    <!-- Admin Users Card -->
    <div class="glass-card p-6 relative overflow-hidden">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Administrators</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($adminUsers) }}</h3>
            </div>
            <div class="p-3 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg, #e6e6ff, #ccccff);">
                <i class="fas fa-user-shield text-xl text-indigo-600" aria-hidden="true"></i>
            </div>
        </div>
        <div class="flex items-center mt-5">
            <span class="text-indigo-600 text-sm font-medium flex items-center">
                <i class="fas fa-shield-alt mr-1" aria-hidden="true"></i> System
            </span>
            <span class="text-gray-500 text-sm ml-2">admins</span>
        </div>
        <div class="w-full bg-gray-100 rounded-full h-1.5 mt-4 overflow-hidden" role="progressbar" aria-valuenow="8" aria-valuemin="0" aria-valuemax="100">
            <div class="bg-indigo-500 h-1.5 rounded-full progress-bar" style="width: 8%"></div>
        </div>
    </div>
</div>
