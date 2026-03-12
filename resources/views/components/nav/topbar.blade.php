@php
$user = auth()->user();
$userName = $user ? ($user->full_name ?? ($user->first_name . ' ' . $user->last_name)) : 'Guest User';
$userEmail = $user ? $user->email : 'guest@example.com';
$userRole = $user && $user->roles ? ($user->roles->first()?->name ?? 'User') : 'User';
$userInitials = $user
? strtoupper(substr($user->first_name ?? '', 0, 1) . substr($user->last_name ?? '', 0, 1))
: 'GU';
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
                <div class="relative flex" id="userDropdown">
                    <div class="h-9 w-9 rounded-full bg-green-100 flex items-center justify-center">
                        <span class="text-green-700 font-bold text-sm">{{ $userInitials }}</span>
                    </div>
                    <div class="hidden md:block text-left">
                        <p class="text-sm font-medium text-gray-700">{{ $userName }}</p>
                        <p class="text-xs text-gray-500">{{ ucfirst($userRole) }}</p>
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

    // Toggle user menu
    function toggleUserMenu() {
        const menu = document.getElementById('userMenu');
        menu.classList.toggle('hidden');
    }

    // Initialize and set interval for date/time
    document.addEventListener('DOMContentLoaded', function() {
        updateDateTime();
        setInterval(updateDateTime, 1000);

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('userMenu');
            const userDropdown = document.getElementById('userDropdown');

            if (dropdown && userDropdown && !userDropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });
    });
</script>
