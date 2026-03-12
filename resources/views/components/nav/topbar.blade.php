@php
$user = auth()->user();
$userName = $user ? ($user->full_name ?? ($user->first_name . ' ' . $user->last_name)) : 'Guest User';
$userEmail = $user ? $user->email : 'guest@example.com';
$rawRole = $user && $user->roles ? ($user->roles->first()?->name ?? 'user') : 'user';

// Format role names for display
$roleLabels = [
    'super_admin' => 'Super Admin',
    'admin_editor' => 'Admin Editor',
    'admin_viewer' => 'Admin Viewer',
    'encoder' => 'Encoder',
    'maintenance' => 'Maintenance',
    'security' => 'Security',
    'security_admin' => 'Security Admin',
    'applicant' => 'Applicant',
    'user' => 'User',
];
$userRole = $roleLabels[$rawRole] ?? ucwords(str_replace('_', ' ', $rawRole));

$userInitials = $user
    ? strtoupper(substr($user->first_name ?? '', 0, 1) . substr($user->last_name ?? '', 0, 1))
    : 'GU';

// Role badge colors
$roleBadgeColors = [
    'super_admin' => 'bg-purple-100 text-purple-700',
    'admin_editor' => 'bg-blue-100 text-blue-700',
    'admin_viewer' => 'bg-gray-100 text-gray-700',
    'encoder' => 'bg-teal-100 text-teal-700',
    'maintenance' => 'bg-orange-100 text-orange-700',
    'security' => 'bg-red-100 text-red-700',
    'security_admin' => 'bg-red-100 text-red-700',
];
$badgeColor = $roleBadgeColors[$rawRole] ?? 'bg-gray-100 text-gray-600';
@endphp
<div>
    <div class="flex-1 ml-0 md:ml-64 smooth-transition">
        <!-- Navbar -->
        <header class="bg-white h-16 border-b border-green-200 px-4 md:px-8 flex items-center justify-between fixed top-0 left-0 right-0 z-[100] shadow-md w-full md:left-64 md:w-[calc(100%-16rem)]">
            <div class="flex items-center">
                <button id="menuBtn" class="mr-4 text-[#006300] md:hidden" onclick="toggleSidebar()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <h1 class="text-xl font-bold text-[#006300] hidden md:block">{{ $pageTitle ?? 'CLSU Dashboard' }}</h1>
                <h1 class="text-lg font-bold text-[#006300] md:hidden">{{ $pageTitle ?? 'Dashboard' }}</h1>
            </div>

            <div class="flex items-center space-x-4">
                <!-- Search Bar (Livewire Component) -->
                <livewire:admin.global-search />

                <!-- User Dropdown -->
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button
                        @click="open = !open"
                        class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-50 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                    >
                        <div class="h-9 w-9 rounded-full bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center shadow-sm">
                            <span class="text-white font-semibold text-sm">{{ $userInitials }}</span>
                        </div>
                        <div class="hidden md:block text-left">
                            <p class="text-sm font-medium text-gray-800 leading-tight">{{ $userName }}</p>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $badgeColor }}">
                                {{ $userRole }}
                            </span>
                        </div>
                        <svg class="hidden md:block h-4 w-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div
                        x-show="open"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-64 rounded-xl bg-white shadow-lg ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 z-50"
                        style="display: none;"
                    >
                        <!-- User Info Header -->
                        <div class="px-4 py-3">
                            <div class="flex items-center space-x-3">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center">
                                    <span class="text-white font-semibold text-sm">{{ $userInitials }}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $userName }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ $userEmail }}</p>
                                </div>
                            </div>
                            <div class="mt-2">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $badgeColor }}">
                                    <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    {{ $userRole }}
                                </span>
                            </div>
                        </div>

                        <!-- Menu Items -->
                        <div class="py-1">
                            <a href="{{ route('admin.account') }}" class="group flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                My Account
                            </a>
                            @if(auth()->user()?->hasRole('super_admin'))
                            <a href="{{ route('admin.settings') }}" class="group flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                System Settings
                            </a>
                            @endif
                        </div>

                        <!-- Logout -->
                        <div class="py-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="group flex w-full items-center px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <svg class="mr-3 h-5 w-5 text-red-400 group-hover:text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Date & Time Banner -->
        <div class="bg-gray-50 border-b border-gray-200 fixed top-16 left-0 right-0 z-20 md:left-64 md:w-[calc(100%-16rem)]">
            <div class="flex items-center justify-between px-4 md:px-8 py-3">
                <div class="text-sm text-gray-500 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span id="current-date">Loading date...</span>
                    <span class="mx-3 text-gray-300">|</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span id="current-time">Loading time...</span>
                </div>
            </div>
        </div>

        <!-- Main content area with proper top padding to account for fixed elements -->
        <main class="pt-2 px-4 md:px-8 pb-8">
            {{ $slot ?? '' }}
        </main>
    </div>
</div>

<script>
    // Update date and time
    function updateDateTime() {
        const now = new Date();
        const dateOptions = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        const timeOptions = {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true
        };

        document.getElementById('current-date').textContent = now.toLocaleDateString('en-US', dateOptions);
        document.getElementById('current-time').textContent = now.toLocaleTimeString('en-US', timeOptions);
    }

    // Initialize and set interval for date/time
    document.addEventListener('DOMContentLoaded', function() {
        updateDateTime();
        setInterval(updateDateTime, 1000);
    });
</script>
