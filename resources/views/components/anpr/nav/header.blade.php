@props([
    'pageTitle' => 'Vehicle Monitoring Dashboard',
    'searchPlaceholder' => 'Search vehicles, plates...',
    'searchAriaLabel' => 'Search vehicles',
    'notificationCount' => 3,
    'userInitials' => 'JD',
    'userName' => 'John Doe',
    'systemStatus' => 'Operational',
    'systemStatusColor' => 'emerald-600',
    'currentDate' => null,
    'currentTime' => null
])

<header class="bg-white shadow-sm sticky top-0 z-10">
    <div class="flex items-center justify-between h-16 px-4 md:px-8">
        <div class="flex items-center">
            <button id="menu-toggle" class="mr-4 text-gray-500 hover:text-gray-700 lg:hidden" aria-label="Toggle menu" onclick="toggleSidebar()">
                <i class="fas fa-bars" aria-hidden="true"></i>
            </button>
            <h1 class="text-xl font-bold clsu-text">{{ $pageTitle }}</h1>
        </div>
        <div class="flex items-center space-x-4">
            <div class="relative hidden md:block">
                <input type="text" id="search-input" placeholder="{{ $searchPlaceholder }}" class="py-2 pl-10 pr-4 w-64 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-400 text-sm" aria-label="{{ $searchAriaLabel }}">
                <i class="fas fa-search absolute left-3 top-2.5 text-gray-400" aria-hidden="true"></i>
            </div>
            <div class="relative">
                <button class="relative p-2 text-gray-600 hover:text-gray-900 rounded-full hover:bg-gray-100 tooltip" aria-label="{{ $notificationCount }} notifications">
                    <i class="fas fa-bell" aria-hidden="true"></i>
                    <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">{{ $notificationCount }}</span>
                    <span class="tooltip-text">Notifications</span>
                </button>
            </div>
            <div class="h-8 w-0.5 bg-gray-200"></div>
            <div class="flex items-center">
                <div class="w-9 h-9 rounded-full bg-green-100 clsu-text flex items-center justify-center font-medium" aria-label="User profile">
                    {{ $userInitials }}
                </div>
                <i class="fas fa-chevron-down ml-2 text-gray-500 text-xs" aria-hidden="true"></i>
            </div>
        </div>
    </div>
    <div class="bg-gray-50 border-b border-gray-200">
        <div class="flex items-center justify-between px-4 md:px-8 py-3">
            <div class="text-sm text-gray-500 flex items-center">
                <i class="far fa-calendar-alt mr-2 text-gray-400" aria-hidden="true"></i>
                <span id="current-date">{{ $currentDate ?? 'Friday, March 28, 2025' }}</span> 
                <span class="mx-2 text-gray-300">|</span> 
                <i class="far fa-clock mr-2 text-gray-400" aria-hidden="true"></i>
                <span id="current-time">{{ $currentTime ?? '14:32:45' }}</span>
            </div>
            <div class="flex items-center">
                <span class="text-sm text-gray-500 mr-2">System Status:</span>
                <span class="flex items-center text-sm font-medium text-{{ $systemStatusColor }}">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full mr-1 animate-pulse"></span>
                    {{ $systemStatus }}
                </span>
            </div>
        </div>
    </div>
</header> 

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

// Header date/time update
function updateDateTime() {
    const now = new Date();
    const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
    
    const dateElem = document.getElementById('current-date');
    const timeElem = document.getElementById('current-time');
    if (dateElem) dateElem.textContent = now.toLocaleDateString('en-US', dateOptions);
    if (timeElem) timeElem.textContent = now.toLocaleTimeString('en-US', timeOptions);
}

// Auto-update time every second
setInterval(updateDateTime, 1000);
window.addEventListener('DOMContentLoaded', updateDateTime);
</script> 