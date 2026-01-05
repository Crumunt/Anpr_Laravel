<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @hasSection('title')
        <title>@yield('title') - {{ config('app.name') }}</title>
    @else
        <title>{{ config('app.name') }}</title>
    @endif

    <!-- FONT -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
    @livewireStyles
</head>

<body class="bg-gray-50 font-sans antialiased">
    <!-- Applicant Topbar -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('applicant.dashboard') }}" class="flex items-center space-x-3">
                        <img src="{{ asset('images/CLSU.png') }}" alt="CLSU Logo" class="h-10 w-10 object-contain">
                        <div class="hidden sm:block">
                            <h1 class="text-lg font-bold text-gray-900">CLSU Gate Pass</h1>
                            <p class="text-xs text-gray-500">Vehicle Registration System</p>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <nav class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('applicant.dashboard') }}"
                       class="flex items-center space-x-2 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                              {{ request()->routeIs('applicant.dashboard') ? 'text-emerald-600 bg-emerald-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('applicant.vehicles') }}"
                       class="flex items-center space-x-2 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                              {{ request()->routeIs('applicant.vehicles*') ? 'text-emerald-600 bg-emerald-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        <i class="fas fa-car"></i>
                        <span>My Vehicles</span>
                    </a>
                    <a href="{{ route('applicant.profile') }}"
                       class="flex items-center space-x-2 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                              {{ request()->routeIs('applicant.profile') ? 'text-emerald-600 bg-emerald-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        <i class="fas fa-user"></i>
                        <span>Profile</span>
                    </a>
                    <a href="{{ route('applicant.activity-log') }}"
                       class="flex items-center space-x-2 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                              {{ request()->routeIs('applicant.activity-log') ? 'text-emerald-600 bg-emerald-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        <i class="fas fa-history"></i>
                        <span>Activity</span>
                    </a>
                </nav>

                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    <!-- User Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center space-x-3 focus:outline-none">
                            <div class="w-9 h-9 rounded-full bg-emerald-600 flex items-center justify-center text-white font-medium text-sm">
                                {{ auth()->user()->name_initial ?? 'U' }}
                            </div>
                            <div class="hidden md:block text-left">
                                <p class="text-sm font-medium text-gray-900">{{ auth()->user()->details?->first_name ?? 'User' }}</p>
                                <p class="text-xs text-gray-500">Applicant</p>
                            </div>
                            <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open"
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50 border border-gray-200">
                            <a href="{{ route('applicant.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user mr-2 w-4"></i> My Profile
                            </a>
                            <hr class="my-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    <i class="fas fa-sign-out-alt mr-2 w-4"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Mobile Menu Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div x-data="{ mobileMenuOpen: false }" x-show="mobileMenuOpen" class="md:hidden border-t border-gray-200 bg-white">
            <nav class="px-4 py-3 space-y-1">
                <a href="{{ route('applicant.dashboard') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('applicant.dashboard') ? 'text-emerald-600 bg-emerald-50' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="fas fa-home w-5"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('applicant.vehicles') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('applicant.vehicles*') ? 'text-emerald-600 bg-emerald-50' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="fas fa-car w-5"></i>
                    <span>My Vehicles</span>
                </a>
                <a href="{{ route('applicant.profile') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('applicant.profile') ? 'text-emerald-600 bg-emerald-50' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="fas fa-user w-5"></i>
                    <span>Profile</span>
                </a>
                <a href="{{ route('applicant.activity-log') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('applicant.activity-log') ? 'text-emerald-600 bg-emerald-50' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="fas fa-history w-5"></i>
                    <span>Activity</span>
                </a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="pt-20 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-sm text-gray-500">
                    © {{ date('Y') }} CLSU Gate Pass System. All rights reserved.
                </p>
                <div class="flex items-center space-x-4 mt-4 md:mt-0">
                    <a href="#" class="text-sm text-gray-500 hover:text-gray-700">Help</a>
                    <a href="#" class="text-sm text-gray-500 hover:text-gray-700">Contact Support</a>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
    @livewireScripts
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>

</html>
