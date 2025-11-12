<!-- /resources/views/components/sidebar.blade.php -->
@props([
    'appName' => 'Admin Dashboard',
    'appLogoPath' => 'images/CLSU.png', // default logo path
    'mobileToggleId' => 'sidebarToggle'
])
<!-- Skip to main content link for accessibility -->
<a href="#main-content" class="skip-link">Skip to main content</a>
<div>
    <!-- Overlay for mobile sidebar -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 opacity-0 invisible md:hidden smooth-transition" onclick="toggleSidebar()"></div>
    
    <!-- Sidebar -->
    <aside id="sidebar" class="w-64 sidebar text-white flex flex-col z-50 fixed inset-y-0 left-0 transform -translate-x-full md:translate-x-0 smooth-transition">
        <!-- Logo -->
        <div class="sidebar-header flex flex-col items-center px-6 py-5 h-auto border-b border-[rgba(255,255,255,0.1)] relative">
            <!-- Logo image linked directly -->
            <div class="w-full h-20 bg-transparent rounded-lg flex items-center justify-center mb-3">
                <img src="{{ asset($appLogoPath) }}" alt="CLSU Logo" class="max-h-full max-w-full object-contain">
    <!-- Fallback if image fails to load -->
               <!-- <i class="fas fa-image text-3xl text-white opacity-70 hidden" aria-hidden="true"></i> -->
            </div>
            <div class="text-center">
                <h1 class="text-lg font-bold">CLSU</h1>
          <p class="text-xs opacity-80">{{ $appName }}</p>
            </div>
            <!-- Close button - positioned absolutely -->
            <button
                id="sidebarClose"
     class="md:hidden absolute top-4 right-4 text-white p-2 hover:bg-green-700 rounded-full"
                type="button"
                aria-label="Close sidebar"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
            </button>
        </div>
        
        <!-- Navigation content -->
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto" aria-label="Main Navigation">
            {{ $slot }}
        </nav>
        
        <!-- Logout button -->
        <div class="p-4 border-t border-[rgba(255,255,255,0.1)]">
            <x-nav.partial.sidebar-item 
                title="Logout" 
                action="logout()" 
                icon="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
            />
        </div>
    </aside>

    <!-- Mobile sidebar toggle button - fixed to screen -->
    <button 
        id="mobileSidebarToggle"class="md:hidden fixed bottom-6 right-6 bg-green-800 text-white p-3 rounded-full shadow-lg z-30 hover:bg-green-700"
        type="button"
        aria-label="Open sidebar menu"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
    :root {
      --clsu-green: #006300;
      --clsu-light-green: #006300;
      --clsu-dark-green: #006300;
      --clsu-accent: #f3c423;
      --clsu-hover: #068406;
    }
    body {
 font-family: 'Poppins', sans-serif;
    }
    .sidebar {
      background-color: #006300;
      transition: transform 0.3s ease-in-out;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
    }
    .sidebar-header {
      background-color: var(--clsu-green);
    }
    .sidebar-item {
      border-left: 3px solid transparent;
      transition: all 0.3s;
    }
    .sidebar-item.active {
      border-left-color: var(--clsu-accent);
      background-color: var(--clsu-hover);
    }
    .sidebar-item:hover {
      background-color: var(--clsu-hover);
      transform: translateX(3px);
    }
    .nav-item {
      margin-bottom: 2px;
      border-radius: 6px;
      transition: all 0.2s ease;
      border-left: 3px solid transparent;
    }
    .nav-item:hover {
      background-color: var(--clsu-hover);
      transform: translateX(3px);
    }
    .nav-item-active {
      border-left-color: var(--clsu-accent);
      background-color: var(--clsu-hover);
    }
    .nav-icon {
      opacity: 0.85;
    }
    .nav-item:hover .nav-icon {
      opacity: 1;
    }
    .section-title {
      color: rgba(255, 255, 255, 0.5);
      font-size: 11px;
    }
    /* Tooltip styles */
    .tooltip {
      position: relative;
      display: inline-block;
    }
    .tooltip .tooltip-text {
      visibility: hidden;
      width: 120px;
      background-color: rgba(0, 0, 0, 0.8);
      color: #fff;
      text-align: center;
      border-radius: 6px;
      padding: 5px;
      position: absolute;
      z-index: 1;
      bottom: 125%;
      left: 50%;
      margin-left: -60px;
      opacity: 0;
      transition: opacity 0.3s;
      font-size: 0.75rem;
      pointer-events: none;
    }
    .tooltip:hover .tooltip-text {
      visibility: visible;
      opacity: 1;
    }
    /* Focus styles for accessibility */
    a:focus, button:focus {
      outline: 2px solid var(--clsu-accent);
      outline-offset: 2px;
    }
    /* Skip to main content for accessibility */
    .skip-link {
      position: absolute;
      top: -40px;
 left: 0;
      background: var(--clsu-accent);
      color: black;
      padding: 8px;
      z-index: 100;transition: top 0.3s;
    }
    .skip-link:focus {
      top: 0;
    }
    /* Mobile responsiveness */
    @media (max-width: 768px) {
      .sidebar {
        width: 80%; /* More reasonable width for mobile */
        max-width: 280px;}
      #mobileSidebarToggle {
        display: flex;
        align-items: center;
        justify-content: center;
      }
    }
    .smooth-transition {
      transition: all 0.3s ease;
    }
    /* Close button styling */
    #sidebarClose {
      transition: background-color 0.2s ease;
    }
</style>

<script>
  // Toggle sidebar function
  function toggleSidebar() {
    console.log("Toggle sidebar function called"); // Debugging
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    
    if (!sidebar || !overlay) {
      console.error("Sidebar or overlay element not found");
      return;
    }if (sidebar.classList.contains('-translate-x-full')) {
      // Opening sidebar
      sidebar.classList.remove('-translate-x-full');
      overlay.classList.remove('opacity-0', 'invisible');
      document.body.style.overflow = 'hidden'; // Prevent background scrolling
    } else {
      // Closing sidebar
      sidebar.classList.add('-translate-x-full');
      overlay.classList.add('opacity-0', 'invisible');
      document.body.style.overflow = ''; // Restore scrolling
    }
  }
  
  // Ensure DOM is loaded before attaching event listeners
  document.addEventListener('DOMContentLoaded', function() {
    console.log("DOM loaded, setting up sidebar"); // Debugging
  
    // Test if buttons exist
    const mobileToggle = document.getElementById('mobileSidebarToggle');
    const sidebarClose = document.getElementById('sidebarClose');
    
    if (mobileToggle) {
      console.log("Mobile toggle button found");
      mobileToggle.addEventListener('click', function(e) {
        e.preventDefault();
        toggleSidebar();
      });
    } else {
      console.error("Mobile toggle button not found");
    }
    
    if (sidebarClose) {
      console.log("Sidebar close button found");
      sidebarClose.addEventListener('click', function(e) {
        e.preventDefault();
        toggleSidebar();
 });
    } else {
      console.error("Sidebar close button not found");
    }
    
    // Highlight current page
    const currentPath = window.location.pathname;
    document.querySelectorAll('nav a').forEach(link => {
      const href = link.getAttribute('href');
      if (href === currentPath || href === window.location.href) {
        link.classList.add('nav-item-active');
        link.setAttribute('aria-current', 'page');
      }
    });

    // Close sidebar when clicking overlay
    const overlay = document.getElementById('sidebarOverlay');
    if (overlay) {
      overlay.addEventListener('click', toggleSidebar);
    }
    
    // Close sidebar with Escape key
    document.addEventListener('keydown', function(event) {
      if (event.key === 'Escape') {
        const sidebar = document.getElementById('sidebar');
        if (sidebar && !sidebar.classList.contains('-translate-x-full')) {
          toggleSidebar();
        }
      }
    });
  });
  
  // Logout function - You need to customize this
  function logout() {
    // You can replace this with your actual logout logic
    window.location.href = '/logout';
  }
</script>