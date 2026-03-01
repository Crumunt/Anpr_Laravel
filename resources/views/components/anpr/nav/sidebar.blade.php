@props([
    'navItems' => null,
    'userName' => null,
    'userRole' => null,
    'userInitials' => null,
    'alertsCount' => 8,
    'cameraCount' => 3
])

<style>
/* Smooth transition utility */
.smooth-transition {
    transition: all 0.3s ease-in-out;
}

/* Sidebar Component Styles */
.sidebar {
    background-color: #006300;
    transition: transform 0.3s ease-in-out;
}

.sidebar-item {
    border-left: 3px solid transparent;
    transition: all 0.3s;
}

.sidebar-item.active {
    border-left-color: #f3c423;
    background-color: #068406;
}

.sidebar-item:hover {
    background-color: #068406;
    transform: translateX(3px);
}

/* Desktop sidebar positioning */
@media (min-width: 768px) {
    .sidebar {
        position: fixed;
        left: 0;
        top: 0;
        height: 100vh;
        width: 16rem; /* 256px - w-64 */
        z-index: 30;
    }
}

/* Mobile responsiveness for sidebar */
@media (max-width: 767px) {
    .sidebar {
        position: fixed;
        left: -100%;
        top: 0;
        height: 100%;
        z-index: 50;
    }
    .sidebar.open {
        left: 0;
    }
    .overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 40;
    }
    .overlay.show {
        display: block;
    }
}
</style>

@php
    // Ensure all user props are strings, not arrays
    $userNameRaw = $userName ?? auth()->user()->name ?? 'User';
    $userName = is_array($userNameRaw) ? json_encode($userNameRaw) : (string)$userNameRaw;

    $userRoleRaw = $userRole ?? auth()->user()->details?->role ?? 'Security Personnel';
    $userRole = is_array($userRoleRaw) ? json_encode($userRoleRaw) : (string)$userRoleRaw;

    $userInitialsRaw = $userInitials ?? (auth()->user()->name_initial ?? strtoupper(substr($userName, 0, 2)));
    $userInitials = is_array($userInitialsRaw) ? json_encode($userInitialsRaw) : (string)$userInitialsRaw;

    // Ensure counts are integers
    $alertsCount = is_array($alertsCount) ? 0 : (int)$alertsCount;
    $cameraCount = is_array($cameraCount) ? 0 : (int)$cameraCount;

    $defaultNavItems = [
        [
            'route' => 'anpr.dashboard',
            'icon' => 'tachometer-alt',
            'label' => 'Dashboard',
            'active' => request()->routeIs('anpr.dashboard'),
        ],
        [
            'divider' => true
        ],
        [
            'route' => 'anpr.flagged-vehicles',
            'icon' => 'flag',
            'label' => 'Flagged Vehicles',
            'active' => request()->routeIs('anpr.flagged-vehicles'),
        ],
        [
            'route' => 'anpr.analytics',
            'icon' => 'chart-bar',
            'label' => 'Analytics',
            'active' => request()->routeIs('anpr.analytics'),
        ],
        [
            'divider' => true
        ],
        [
            'route' => 'anpr.profile',
            'icon' => 'user-circle',
            'label' => 'My Profile',
            'active' => request()->routeIs('anpr.profile'),
        ],
    ];
    $navItemsToShow = $navItems && is_array($navItems) && count($navItems) > 0 ? $navItems : $defaultNavItems;
@endphp

<aside id="sidebar" class="w-64 sidebar text-white flex flex-col z-30 fixed inset-y-0 left-0 transform -translate-x-full md:translate-x-0 smooth-transition">
    <div class="flex flex-col items-center px-6 py-5 h-auto border-b border-white border-opacity-10">
        <div class="w-full h-20 bg-opacity-10 rounded-lg flex items-center justify-center mb-3">
            <img src="{{ asset('images/CLSU.png') }}" alt="CLSU Logo" class="max-h-full max-w-full object-contain">
        </div>
        <div class="text-center">
            <h1 class="text-lg font-bold">CLSU</h1>
            <p class="text-xs opacity-80">ANPR System</p>
        </div>
    </div>
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto" aria-label="Main Navigation">
        @foreach($navItemsToShow as $item)
            @if(isset($item['divider']) && $item['divider'])
                <div class="my-3 h-px bg-white bg-opacity-10"></div>
            @else
                @php
                    // Safely extract route, ensuring it's a string
                    $route = is_array($item['route'] ?? null) ? '#' : (string)($item['route'] ?? '#');
                    $icon = is_array($item['icon'] ?? null) ? 'circle' : (string)($item['icon'] ?? 'circle');
                    $label = is_array($item['label'] ?? null) ? 'Menu Item' : (string)($item['label'] ?? 'Menu Item');
                    $badge = isset($item['badge']) ? (is_array($item['badge']) ? (string)count($item['badge']) : (string)$item['badge']) : null;
                    $badgeClass = is_array($item['badge_class'] ?? null) ? 'bg-white bg-opacity-20 text-xs px-1.5 py-0.5 rounded' : ($item['badge_class'] ?? 'bg-white bg-opacity-20 text-xs px-1.5 py-0.5 rounded');
                    $isActive = isset($item['active']) && $item['active'];

                    // Safely generate route URL
                    try {
                        $routeUrl = route($route);
                    } catch (\Exception $e) {
                        $routeUrl = '#';
                    }
                @endphp
                <a href="{{ $routeUrl }}" class="flex items-center px-4 py-3 text-white rounded-lg sidebar-item {{ $isActive ? 'active' : '' }}" aria-current="{{ $isActive ? 'page' : 'false' }}">
                    <div class="bg-white bg-opacity-10 w-8 h-8 rounded-md flex items-center justify-center">
                        <i class="fas fa-{{ $icon }}" aria-hidden="true"></i>
                    </div>
                    <span class="ml-3 font-medium">{{ $label }}</span>
                    @if($badge)
                        <span class="ml-auto {{ $badgeClass }}" aria-label="{{ $badge }}">{{ $badge }}</span>
                    @endif
                </a>
            @endif
        @endforeach
    </nav>
    <div class="p-4 border-t border-white border-opacity-10">
        <div class="flex items-center p-2 rounded-lg hover:bg-white hover:bg-opacity-10 transition cursor-pointer">
            <div class="w-10 h-10 rounded-full bg-white bg-opacity-10 flex items-center justify-center" aria-label="User avatar">
                {{ $userInitials }}
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium">{{ $userName }}</p>
                <p class="text-xs opacity-70">{{ $userRole }}</p>
            </div>
            <div class="ml-auto">
                <button class="text-white opacity-70 hover:opacity-100" aria-label="User menu">
                    <i class="fas fa-ellipsis-v" aria-hidden="true"></i>
                </button>
            </div>
        </div>
    </div>
</aside>

<script>
// Sidebar toggle for mobile
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');

    sidebar.classList.toggle('open');
    if (overlay) overlay.classList.toggle('show');

    if (sidebar.classList.contains('open')) {
        const firstLink = document.querySelector('.sidebar a');
        if (firstLink) firstLink.focus();
    }
}
</script>
