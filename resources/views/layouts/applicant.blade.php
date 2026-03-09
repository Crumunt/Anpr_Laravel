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

<body class="bg-gray-50/80 font-sans antialiased" x-data="{ sidebarOpen: false }">

    <!-- Mobile Overlay -->
    <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"
         class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-40 lg:hidden" style="display: none;"></div>

    <!-- ===== SIDEBAR ===== -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed top-0 left-0 z-50 h-full w-[280px] transform transition-transform duration-300 ease-in-out lg:translate-x-0 flex flex-col shadow-xl shadow-black/5">

        <!-- Sidebar Header - CLSU Branding -->
        <div class="bg-[#1a5c1f] px-5 pt-5 pb-4 flex-shrink-0 relative">
            <a href="{{ route('applicant.dashboard') }}" class="flex items-center gap-3 focus-ring" aria-label="Go to Dashboard">
                <div class="w-12 h-12 bg-white/15 rounded-xl flex items-center justify-center ring-1 ring-white/20">
                    <img src="{{ asset('images/CLSU.png') }}" alt="CLSU Logo" class="h-9 w-9 object-contain">
                </div>
                <div>
                    <h1 class="text-base font-bold text-white tracking-tight leading-tight">CLSU Gate Pass</h1>
                    <p class="text-xs text-green-300/70 font-medium">Vehicle Registration System</p>
                </div>
            </a>
            <!-- Mobile Close -->
            <button @click="sidebarOpen = false" class="lg:hidden absolute top-4 right-3 text-white/60 hover:text-white p-1.5 rounded-lg hover:bg-white/10 transition-colors" aria-label="Close menu">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 bg-white overflow-y-auto px-3 py-5 space-y-1" aria-label="Main navigation">
            <p class="px-3 text-[11px] font-bold text-gray-400 uppercase tracking-[0.1em] mb-2">Main Menu</p>

            <a href="{{ route('applicant.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[15px] font-medium transition-all duration-150 focus-ring
                      {{ request()->routeIs('applicant.dashboard') ? 'bg-green-50 text-green-800 font-semibold shadow-sm' : 'text-gray-600 hover:text-gray-800 hover:bg-gray-50' }}"
               aria-current="{{ request()->routeIs('applicant.dashboard') ? 'page' : 'false' }}">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 {{ request()->routeIs('applicant.dashboard') ? 'bg-green-600 text-white' : 'text-gray-400' }}">
                    <i class="fas fa-th-large text-sm"></i>
                </div>
                <span>Dashboard</span>
                @if(request()->routeIs('applicant.dashboard'))
                    <div class="ml-auto w-1.5 h-1.5 rounded-full bg-green-500"></div>
                @endif
            </a>

            <a href="{{ route('applicant.vehicles') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[15px] font-medium transition-all duration-150 focus-ring
                      {{ request()->routeIs('applicant.vehicles*') ? 'bg-green-50 text-green-800 font-semibold shadow-sm' : 'text-gray-600 hover:text-gray-800 hover:bg-gray-50' }}"
               aria-current="{{ request()->routeIs('applicant.vehicles*') ? 'page' : 'false' }}">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 {{ request()->routeIs('applicant.vehicles*') ? 'bg-green-600 text-white' : 'text-gray-400' }}">
                    <i class="fas fa-car text-sm"></i>
                </div>
                <span>My Vehicles</span>
                @if(request()->routeIs('applicant.vehicles*'))
                    <div class="ml-auto w-1.5 h-1.5 rounded-full bg-green-500"></div>
                @endif
            </a>

            @php $unreadCount = auth()->user()?->unreadNotifications()->count() ?? 0; @endphp
            <a href="{{ route('applicant.notifications') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[15px] font-medium transition-all duration-150 focus-ring
                      {{ request()->routeIs('applicant.notifications') ? 'bg-green-50 text-green-800 font-semibold shadow-sm' : 'text-gray-600 hover:text-gray-800 hover:bg-gray-50' }}"
               aria-current="{{ request()->routeIs('applicant.notifications') ? 'page' : 'false' }}">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 relative {{ request()->routeIs('applicant.notifications') ? 'bg-green-600 text-white' : 'text-gray-400' }}">
                    <i class="fas fa-bell text-sm"></i>
                    @if($unreadCount > 0)
                    <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center ring-2 ring-white">
                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                    </span>
                    @endif
                </div>
                <span>Notifications</span>
                @if($unreadCount > 0)
                <span class="ml-auto bg-red-500 text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                @elseif(request()->routeIs('applicant.notifications'))
                    <div class="ml-auto w-1.5 h-1.5 rounded-full bg-green-500"></div>
                @endif
            </a>

            <a href="{{ route('applicant.activity-log') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[15px] font-medium transition-all duration-150 focus-ring
                      {{ request()->routeIs('applicant.activity-log') ? 'bg-green-50 text-green-800 font-semibold shadow-sm' : 'text-gray-600 hover:text-gray-800 hover:bg-gray-50' }}"
               aria-current="{{ request()->routeIs('applicant.activity-log') ? 'page' : 'false' }}">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 {{ request()->routeIs('applicant.activity-log') ? 'bg-green-600 text-white' : 'text-gray-400' }}">
                    <i class="fas fa-clock-rotate-left text-sm"></i>
                </div>
                <span>Activity Log</span>
                @if(request()->routeIs('applicant.activity-log'))
                    <div class="ml-auto w-1.5 h-1.5 rounded-full bg-green-500"></div>
                @endif
            </a>

            <div class="pt-4 pb-1">
                <p class="px-3 text-[11px] font-bold text-gray-400 uppercase tracking-[0.1em] mb-2">Account</p>
            </div>

            <a href="{{ route('applicant.profile') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[15px] font-medium transition-all duration-150 focus-ring
                      {{ request()->routeIs('applicant.profile') ? 'bg-green-50 text-green-800 font-semibold shadow-sm' : 'text-gray-600 hover:text-gray-800 hover:bg-gray-50' }}"
               aria-current="{{ request()->routeIs('applicant.profile') ? 'page' : 'false' }}">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 {{ request()->routeIs('applicant.profile') ? 'bg-green-600 text-white' : 'text-gray-400' }}">
                    <i class="fas fa-user-circle text-sm"></i>
                </div>
                <span>My Profile</span>
                @if(request()->routeIs('applicant.profile'))
                    <div class="ml-auto w-1.5 h-1.5 rounded-full bg-green-500"></div>
                @endif
            </a>
        </nav>

        <!-- Sidebar Footer - Logout -->
        <div class="bg-white border-t border-gray-100 px-3 py-3 flex-shrink-0">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-[15px] font-medium text-red-600 hover:bg-red-50 transition-all duration-150 focus-ring"
                    aria-label="Sign out of your account">
                    <div class="w-9 h-9 rounded-lg flex items-center justify-center text-red-500 flex-shrink-0">
                        <i class="fas fa-right-from-bracket text-sm"></i>
                    </div>
                    <span>Sign Out</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- ===== MAIN CONTENT AREA ===== -->
    <div class="lg:ml-[280px] min-h-screen flex flex-col">

        <!-- Top Bar -->
        <header class="sticky top-0 z-30 glass border-b border-gray-200/60">
            <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                <!-- Mobile Menu Button -->
                <button @click="sidebarOpen = true" class="lg:hidden p-2.5 rounded-xl text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors focus-ring" aria-label="Open navigation menu">
                    <i class="fas fa-bars text-lg"></i>
                </button>

                <!-- Page Title (Desktop) -->
                <div class="hidden lg:block">
                    <h2 class="text-lg font-bold text-gray-800">@yield('title', 'Dashboard')</h2>
                </div>

                <!-- Right side actions -->
                <div class="flex items-center gap-3">

                    <!-- Notification Bell -->
                    <a href="{{ route('applicant.notifications') }}"
                       class="relative p-2.5 rounded-xl text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors focus-ring"
                       aria-label="View notifications {{ $unreadCount > 0 ? '(' . $unreadCount . ' unread)' : '' }}">
                        <i class="fas fa-bell text-lg"></i>
                        @if($unreadCount > 0)
                        <span class="absolute top-1 right-1 w-4 h-4 bg-red-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center ring-2 ring-white pulse-dot">
                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                        </span>
                        @endif
                    </a>

                    <!-- User Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center gap-2.5 p-1.5 pr-3 rounded-xl hover:bg-gray-100 transition-colors focus-ring" aria-haspopup="true" :aria-expanded="open">
                            <div class="w-9 h-9 rounded-full bg-[#1a5c1f] flex items-center justify-center text-white font-semibold text-sm shadow-sm">
                                {{ auth()->user()->name_initial ?? 'U' }}
                            </div>
                            <span class="hidden md:block text-sm font-medium text-gray-700">{{ auth()->user()->details?->first_name ?? 'User' }}</span>
                            <i class="fas fa-chevron-down text-[10px] text-gray-400 hidden md:block"></i>
                        </button>

                        <div x-show="open" @click.away="open = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                             class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl py-2 z-50 border border-gray-200/80 ring-1 ring-black/5" style="display: none;">
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->details?->first_name ?? 'User' }} {{ auth()->user()->details?->last_name ?? '' }}</p>
                                <p class="text-xs text-gray-500 truncate mt-0.5">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('applicant.profile') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-user-circle w-4 text-gray-400"></i> My Profile
                            </a>
                            <a href="{{ route('applicant.activity-log') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-clock-rotate-left w-4 text-gray-400"></i> Activity Log
                            </a>
                            <hr class="my-1 border-gray-100">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <i class="fas fa-right-from-bracket w-4"></i> Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-5 py-4 rounded-xl flex items-center gap-3 shadow-sm" role="alert">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-xl flex items-center gap-3 shadow-sm" role="alert">
                    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-600"></i>
                    </div>
                    <p class="text-sm font-medium">{{ session('error') }}</p>
                </div>
            @endif

            <!-- Global Livewire Modals -->
            @livewire('applicant.add-vehicle-modal')
            @livewire('applicant.renew-gate-pass-modal')

            <div class="page-transition">
                @yield('content')
                {{ $slot ?? '' }}
            </div>
        </main>

        <!-- Footer -->
        <footer class="border-t border-gray-200/60 bg-white/50 py-5 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
                <div class="flex items-center gap-2 text-sm text-gray-400">
                    <img src="{{ asset('images/CLSU.png') }}" alt="" class="h-5 w-5 opacity-50">
                    <span>&copy; {{ date('Y') }} Central Luzon State University. All rights reserved.</span>
                </div>
                <div class="flex items-center gap-4">
                    <a href="#" class="text-sm text-gray-400 hover:text-green-700 transition-colors">Help Center</a>
                    <span class="text-gray-300">&middot;</span>
                    <a href="#" class="text-sm text-gray-400 hover:text-green-700 transition-colors">Contact Support</a>
                </div>
            </div>
        </footer>
    </div>

    @stack('scripts')
    @livewireScripts
</body>

</html>
