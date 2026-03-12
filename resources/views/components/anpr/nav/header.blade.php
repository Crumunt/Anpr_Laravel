@props([
'pageTitle' => 'Vehicle Monitoring Dashboard',
'searchPlaceholder' => 'Search vehicles, plates...',
'searchAriaLabel' => 'Search vehicles',
'notificationCount' => 3,
'userInitials' => null,
'userName' => null,
'systemStatus' => 'Operational',
'systemStatusColor' => 'emerald-600',
'currentDate' => null,
'currentTime' => null
])

@php
$userInitials = $userInitials ?? (auth()->user()->name_initial ?? strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)));
$userName = $userName ?? auth()->user()->name ?? 'User';
@endphp

<!-- Top Navigation Bar - Sticky -->
<div class="sticky top-0 z-30 bg-white">
    <!-- Main Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="flex items-center justify-between px-4 md:px-6 py-4">
            <!-- Mobile Menu Toggle & Page Title -->
            <div class="flex items-center space-x-3">
                <button id="menu-toggle" onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100" aria-label="Toggle menu">
                    <i class="fas fa-bars text-xl" aria-hidden="true"></i>
                </button>

                <div>
                    <h1 class="text-lg md:text-xl font-bold text-[#006300]">{{ $pageTitle }}</h1>
                </div>
            </div>

            <!-- Right Side -->
            <div class="flex items-center space-x-2 md:space-x-4">
                <!-- Search (Desktop Only) -->
                <div class="relative hidden lg:block">
                    <input
                        type="text"
                        id="search-input"
                        placeholder="{{ $searchPlaceholder }}"
                        class="py-2 pl-9 pr-4 w-48 xl:w-64 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-400 text-sm"
                        aria-label="{{ $searchAriaLabel }}">
                    <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-sm" aria-hidden="true"></i>
                </div>

                <!-- Status Indicator -->
                @php
                $statusColorClass = 'text-emerald-600';
                $statusBgClass = 'bg-emerald-50';
                $statusDotClass = 'bg-emerald-500';

                if (in_array($systemStatusColor ?? 'green-600', ['emerald-600', 'green-600'])) {
                    $statusColorClass = 'text-emerald-600';
                    $statusBgClass = 'bg-emerald-50';
                    $statusDotClass = 'bg-emerald-500';
                } elseif (($systemStatusColor ?? '') === 'red-600') {
                    $statusColorClass = 'text-red-600';
                    $statusBgClass = 'bg-red-50';
                    $statusDotClass = 'bg-red-500';
                } elseif (in_array($systemStatusColor ?? '', ['amber-600', 'yellow-600'])) {
                    $statusColorClass = 'text-amber-600';
                    $statusBgClass = 'bg-amber-50';
                    $statusDotClass = 'bg-amber-500';
                } elseif (($systemStatusColor ?? '') === 'blue-600') {
                    $statusColorClass = 'text-blue-600';
                    $statusBgClass = 'bg-blue-50';
                    $statusDotClass = 'bg-blue-500';
                }

                $systemStatusText = is_array($systemStatus ?? 'Online') ? json_encode($systemStatus) : (string)($systemStatus ?? 'Online');
                @endphp

                <div class="hidden sm:flex items-center space-x-2 {{ $statusBgClass }} px-3 py-1.5 rounded-full">
                    <span class="w-2 h-2 {{ $statusDotClass }} rounded-full animate-pulse"></span>
                    <span class="text-xs md:text-sm {{ $statusColorClass }} font-medium">{{ $systemStatusText }}</span>
                </div>

                <!-- Divider -->
                <div class="h-8 w-0.5 bg-gray-200 hidden sm:block"></div>

                <!-- Notifications -->
                <div class="relative">
                    <button class="relative p-2 text-gray-600 hover:text-gray-900 rounded-full hover:bg-gray-100 transition-colors" aria-label="{{ $notificationCount ?? 0 }} notifications">
                        <i class="fas fa-bell" aria-hidden="true"></i>
                        @if(($notificationCount ?? 0) > 0)
                        <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">
                            {{ $notificationCount }}
                        </span>
                        @endif
                    </button>
                </div>

                <!-- User Menu -->
                <div class="relative" id="userDropdown">
                    <button onclick="toggleUserMenu()" class="flex items-center focus:outline-none hover:bg-gray-50 rounded-lg px-2 py-1.5 transition-colors" aria-label="User menu">
                        <div class="w-8 h-8 md:w-9 md:h-9 rounded-full bg-green-100 text-[#006300] flex items-center justify-center font-medium text-sm" aria-label="User profile">
                            {{ $userInitials ?? 'U' }}
                        </div>
                        <i class="fas fa-chevron-down ml-2 text-gray-500 text-xs hidden sm:block" aria-hidden="true"></i>
                    </button>

                    <div id="userMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                <i class="fas fa-sign-out-alt mr-2 text-gray-400"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Date & Time Banner -->
    <div class="bg-gray-50 border-b border-gray-200">
        <div class="flex items-center justify-between px-4 md:px-6 py-2.5">
            <div class="text-sm text-gray-500 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span id="current-date">{{ $currentDate ?? now()->format('l, F d, Y') }}</span>
                <span class="mx-3 text-gray-300 hidden sm:inline">|</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-400 hidden sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span id="current-time" class="hidden sm:inline">{{ $currentTime ?? now()->format('h:i:s A') }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Spacing between header and main content -->

<script>
    // Sidebar toggle for mobile
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');

        if (sidebar) sidebar.classList.toggle('open');
        if (overlay) overlay.classList.toggle('show');

        if (sidebar && sidebar.classList.contains('open')) {
            const firstLink = sidebar.querySelector('a');
            if (firstLink) firstLink.focus();
        }
    }

    // Header date/time update
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

        const dateElement = document.getElementById('current-date');
        const timeElement = document.getElementById('current-time');

        if (dateElement) {
            dateElement.textContent = now.toLocaleDateString('en-US', dateOptions);
        }
        if (timeElement) {
            timeElement.textContent = now.toLocaleTimeString('en-US', timeOptions);
        }
    }

    // User menu toggle
    function toggleUserMenu() {
        const menu = document.getElementById('userMenu');
        if (menu) {
            menu.classList.toggle('hidden');
        }
    }

    // Close user menu when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('userMenu');
        const userDropdown = document.getElementById('userDropdown');

        if (dropdown && userDropdown && !userDropdown.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    });

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateDateTime();
        setInterval(updateDateTime, 1000);
    });
</script>
