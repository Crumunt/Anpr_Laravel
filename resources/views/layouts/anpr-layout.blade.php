<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @hasSection('title')
        <title>@yield('title') - CLSU ANPR System</title>
    @else
        <title>CLSU ANPR System</title>
    @endif

    <!-- FONT -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
    @livewireStyles
</head>

<body class="bg-gray-100 font-sans antialiased">
    <!-- Overlay for mobile sidebar -->
    <div id="overlay" class="overlay" onclick="toggleSidebar()"></div>

    <!-- ANPR Sidebar -->
    @include('components.anpr.nav.sidebar')

    <!-- Main Content Wrapper - with left margin for sidebar on desktop -->
    <div class="flex-1 ml-0 md:ml-64 smooth-transition">
        <!-- ANPR Header - Fixed at top -->
        @php
            $pageTitle = $__env->yieldContent('page-title');
        @endphp
        @include('components.anpr.nav.header', [
            'pageTitle' => $pageTitle ?: 'ANPR Dashboard',
            'searchPlaceholder' => 'Search vehicles, plates...',
            'notificationCount' => isset($alertsCount) && is_array($alertsCount) ? ($alertsCount['total'] ?? 0) : 0
        ])

        <!-- Page Content - with top padding to account for fixed header -->
        <main id="main-content" class="pt-20 px-4 md:px-8 pb-28">
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

            if (sidebar) {
                sidebar.classList.toggle('open');
            }
            if (overlay) {
                overlay.classList.toggle('show');
            }

            if (sidebar && sidebar.classList.contains('open')) {
                const firstLink = document.querySelector('.sidebar a');
                if (firstLink) firstLink.focus();
            }
        }

        // Close sidebar when clicking overlay
        document.addEventListener('DOMContentLoaded', function() {
            const overlay = document.getElementById('overlay');
            if (overlay) {
                overlay.addEventListener('click', function() {
                    toggleSidebar();
                });
            }
        });
    </script>

    @stack('scripts')
    @livewireScripts
</body>

</html>
