<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    @hasSection('title')
        <title>@yield('title') - CLSU ANPR System</title>
    @else
        <title>CLSU ANPR System</title>
    @endif

    <!-- FONT -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ANPR Theme Styles */
        :root {
            --anpr-primary: #006300;
            --anpr-primary-light: #068406;
            --anpr-accent: #f3c423;
            --anpr-dark: #003300;
        }

        .anpr-sidebar {
            background-color: var(--anpr-primary);
        }

        .anpr-sidebar-item {
            border-left: 3px solid transparent;
            transition: all 0.3s;
        }

        .anpr-sidebar-item.active {
            border-left-color: var(--anpr-accent);
            background-color: var(--anpr-primary-light);
        }

        .anpr-sidebar-item:hover {
            background-color: var(--anpr-primary-light);
            transform: translateX(3px);
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .anpr-sidebar {
                position: fixed;
                left: -100%;
                top: 0;
                height: 100%;
                z-index: 50;
            }
            .anpr-sidebar.open {
                left: 0;
            }
            .anpr-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 40;
            }
            .anpr-overlay.show {
                display: block;
            }
        }
    </style>

    @stack('styles')
    @livewireStyles
</head>

<body class="bg-gray-100 font-sans antialiased">
    <!-- Overlay for mobile -->
    <div id="overlay" class="anpr-overlay" onclick="toggleSidebar()"></div>

    <!-- Fixed Sidebar -->
    <aside id="sidebar" class="anpr-sidebar w-64 fixed inset-y-0 left-0 flex flex-col shadow-lg z-50 transform -translate-x-full md:translate-x-0 transition-transform duration-300">
        <!-- Logo Section -->
        <div class="p-4 border-b border-green-700">
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/CLSU.png') }}" alt="CLSU Logo" class="h-10 w-10 object-contain bg-white rounded-lg p-1">
                <div>
                    <h1 class="text-lg font-bold text-white">CLSU ANPR</h1>
                    <p class="text-xs text-green-200">Security Monitor</p>
                </div>
            </div>
        </div>

        <!-- User Info -->
        <div class="p-4 border-b border-green-700">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-full bg-green-700 flex items-center justify-center text-white font-bold">
                    {{ auth()->user()->name_initial ?? 'S' }}
                </div>
                <div>
                    <p class="text-sm font-medium text-white">{{ auth()->user()->details?->first_name ?? 'Security' }}</p>
                    <p class="text-xs text-green-200">{{ auth()->user()?->hasRole('security_admin') ? 'Security Admin' : 'Security Personnel' }}</p>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto py-4">
            <ul class="space-y-1 px-2">
                <!-- Dashboard -->
                <li>
                    <a href="{{ route('anpr.dashboard') }}"
                       class="anpr-sidebar-item flex items-center px-4 py-3 text-green-100 rounded-r-lg {{ request()->routeIs('anpr.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt w-5 mr-3"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- Live Feeds -->
                <li>
                    <a href="{{ route('anpr.live-feeds') }}"
                       class="anpr-sidebar-item flex items-center px-4 py-3 text-green-100 rounded-r-lg {{ request()->routeIs('anpr.live-feeds') ? 'active' : '' }}">
                        <i class="fas fa-video w-5 mr-3"></i>
                        <span>Live Feeds</span>
                        <span class="ml-auto bg-green-700 text-xs px-2 py-0.5 rounded-full">3</span>
                    </a>
                </li>

                <!-- Alerts -->
                <li>
                    <a href="{{ route('anpr.alerts') }}"
                       class="anpr-sidebar-item flex items-center px-4 py-3 text-green-100 rounded-r-lg {{ request()->routeIs('anpr.alerts') ? 'active' : '' }}">
                        <i class="fas fa-exclamation-triangle w-5 mr-3"></i>
                        <span>Alerts</span>
                        <span class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">8</span>
                    </a>
                </li>

                <li class="my-3 border-t border-green-700"></li>

                <!-- Flagged Vehicles -->
                <li>
                    <a href="{{ route('anpr.flagged-vehicles') }}"
                       class="anpr-sidebar-item flex items-center px-4 py-3 text-green-100 rounded-r-lg {{ request()->routeIs('anpr.flagged-vehicles') ? 'active' : '' }}">
                        <i class="fas fa-flag w-5 mr-3"></i>
                        <span>Flagged Vehicles</span>
                    </a>
                </li>

                <!-- Analytics -->
                <li>
                    <a href="{{ route('anpr.analytics') }}"
                       class="anpr-sidebar-item flex items-center px-4 py-3 text-green-100 rounded-r-lg {{ request()->routeIs('anpr.analytics') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar w-5 mr-3"></i>
                        <span>Analytics</span>
                    </a>
                </li>

                <li class="my-3 border-t border-green-700"></li>

                <!-- Manage Accounts - Only for security_admin -->
                @if(auth()->user()?->hasRole('security_admin'))
                <li>
                    <a href="{{ route('anpr.accounts') }}"
                       class="anpr-sidebar-item flex items-center px-4 py-3 text-green-100 rounded-r-lg {{ request()->routeIs('anpr.accounts') ? 'active' : '' }}">
                        <i class="fas fa-users-cog w-5 mr-3"></i>
                        <span>Manage Accounts</span>
                    </a>
                </li>
                @endif

                <!-- Profile -->
                <li>
                    <a href="{{ route('anpr.profile') }}"
                       class="anpr-sidebar-item flex items-center px-4 py-3 text-green-100 rounded-r-lg {{ request()->routeIs('anpr.profile') ? 'active' : '' }}">
                        <i class="fas fa-user-cog w-5 mr-3"></i>
                        <span>My Profile</span>
                    </a>
                </li>

                <!-- Settings -->
                <li>
                    <a href="{{ route('anpr.settings') }}"
                       class="anpr-sidebar-item flex items-center px-4 py-3 text-green-100 rounded-r-lg {{ request()->routeIs('anpr.settings') ? 'active' : '' }}">
                        <i class="fas fa-cog w-5 mr-3"></i>
                        <span>Settings</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Logout -->
        <div class="p-4 border-t border-green-700">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center px-4 py-2 text-red-300 hover:text-red-100 hover:bg-red-900/30 rounded-lg transition-colors">
                    <i class="fas fa-sign-out-alt w-5 mr-3"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area - with left margin for sidebar -->
    <div class="md:ml-64 min-h-screen">
        <!-- Top Header -->
        <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-30">
            <div class="flex items-center justify-between px-4 md:px-6 py-4">
                <!-- Mobile Menu Toggle -->
                <button onclick="toggleSidebar()" class="md:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100">
                    <i class="fas fa-bars text-xl"></i>
                </button>

                <!-- Page Title -->
                <div class="hidden md:block">
                    <h1 class="text-xl font-bold text-gray-900">@yield('page-title', 'ANPR Dashboard')</h1>
                    <p class="text-sm text-gray-500">@yield('page-subtitle', 'Vehicle Monitoring System')</p>
                </div>

                <!-- Right Side -->
                <div class="flex items-center space-x-4">
                    <!-- Status Indicator -->
                    <div class="flex items-center space-x-2 bg-green-50 px-3 py-1.5 rounded-full">
                        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                        <span class="text-sm text-green-700 font-medium">System Online</span>
                    </div>

                    <!-- Current Time -->
                    <div class="hidden md:flex items-center space-x-2 text-gray-600">
                        <i class="fas fa-clock"></i>
                        <span id="current-time" class="text-sm font-medium"></span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-6">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg flex items-center">
                    <i class="fas fa-check-circle mr-3"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center">
                    <i class="fas fa-exclamation-circle mr-3"></i>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
            {{ $slot ?? '' }}
        </main>
    </div>

    <script>
        // Toggle sidebar on mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('show');
        }

        // Update current time
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            const dateString = now.toLocaleDateString('en-US', {
                weekday: 'short',
                month: 'short',
                day: 'numeric'
            });
            const element = document.getElementById('current-time');
            if (element) {
                element.textContent = `${dateString} ${timeString}`;
            }
        }

        updateTime();
        setInterval(updateTime, 1000);
    </script>

    @stack('scripts')
    @livewireScripts
</body>

</html>
