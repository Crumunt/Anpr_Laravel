@props([
    'navItems' => null,
    'userName' => 'John Doe',
    'userRole' => 'System Administrator',
    'userInitials' => 'JD',
    'alertsCount' => 8,
    'cameraCount' => 3
])

<style>
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

/* Mobile responsiveness for sidebar */
@media (max-width: 768px) {
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
    $defaultNavItems = [
        [
            'route' => 'anpr.dashboard',
            'icon' => 'tachometer-alt',
            'label' => 'Dashboard',
            'active' => request()->routeIs('anpr.dashboard'),
        ],
        [
            'route' => 'anpr.live-feeds',
            'icon' => 'video',
            'label' => 'Live Feeds',
            'active' => request()->routeIs('anpr.live-feeds'),
            'badge' => $cameraCount,
        ],
        [
            'route' => 'anpr.alerts',
            'icon' => 'exclamation-triangle',
            'label' => 'Alerts',
            'active' => request()->routeIs('anpr.alerts'),
            'badge' => $alertsCount,
            'badge_class' => 'bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full',
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
            'route' => 'anpr.user-management',
            'icon' => 'users',
            'label' => 'User Management',
            'active' => request()->routeIs('anpr.user-management'),
        ],
        [
            'route' => 'anpr.user-management.profile',
            'icon' => 'user-circle',
            'label' => 'My Profile',
            'active' => request()->routeIs('anpr.user-management.profile'),
        ],
        [
            'route' => 'anpr.settings',
            'icon' => 'cog',
            'label' => 'System Settings',
            'active' => request()->routeIs('anpr.settings'),
        ],
    ];
    $navItemsToShow = $navItems && is_array($navItems) && count($navItems) > 0 ? $navItems : $defaultNavItems;
@endphp

<div id="sidebar" class="w-64 sidebar text-white flex flex-col z-30 lg:relative">
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
                <a href="{{ route($item['route']) }}" class="flex items-center px-4 py-3 text-white rounded-lg sidebar-item {{ $item['active'] ? 'active' : '' }}" aria-current="{{ $item['active'] ? 'page' : 'false' }}">
                    <div class="bg-white bg-opacity-10 w-8 h-8 rounded-md flex items-center justify-center">
                        <i class="fas fa-{{ $item['icon'] }}" aria-hidden="true"></i>
                    </div>
                    <span class="ml-3 font-medium">{{ $item['label'] }}</span>
                    @if(isset($item['badge']) && $item['badge'])
                        <span class="ml-auto {{ $item['badge_class'] ?? 'bg-white bg-opacity-20 text-xs px-1.5 py-0.5 rounded' }}" aria-label="{{ $item['badge'] }}">{{ $item['badge'] }}</span>
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
</div> 

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