<div>
    <!-- Very little is needed to make a happy life. - Marcus Aurelius -->
    <div class="flex-1 ml-0 md:ml-64 smooth-transition">
           <!-- Navbar -->
           <header class="bg-white h-16 border-b border-green-200 px-4 md:px-8 flex items-center justify-between fixed top-0 left-0 right-0 z-30 shadow-md w-full md:left-64 md:w-[calc(100%-16rem)]"> <!-- Added md:px-8 for better spacing -->
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
          <div class="relative w-full md:w-2/3 hidden md:block">
            <input
              class="w-full peer z-[21] pl-11 pr-11 py-2.5 text-base rounded-xl outline-none duration-200 border border-gray-200 focus:border-[#11BE86] ring-2 ring-[transparent] focus:ring-[#11BE86] bg-white shadow-sm focus:shadow-md"
              placeholder="Search..."
              id="globalSearchInput"
              autocomplete="off"
            />
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <i class="fas fa-search text-gray-400"></i>
            </div>
            <button id="globalSearchClear" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 hidden" type="button" aria-label="Clear search">
              <i class="fas fa-times-circle"></i>
            </button>
            <div
              id="globalSearchDropdown"
              class="hidden opacity-0 -translate-y-2 duration-200 absolute top-14 left-0 w-full z-[500] rounded-xl border border-gray-100 bg-white shadow-2xl"
            ></div>
          </div>
          
          <button class="relative p-2 rounded-full text-gray-600 hover:text-green-700 hover:bg-green-50 smooth-transition">
           <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
      </svg>
            <span class="absolute top-1 right-1 w-2 h-2 bg-green-500 rounded-full"></span>
          </button><button class="p-2 rounded-full text-gray-600 hover:text-green-700 hover:bg-green-50 smooth-transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
          </button>
          
          <div class="relative" id="userDropdown">
            <button class="h-9 w-9 rounded-full bg-green-100 flex items-center justify-center hover:bg-green-200 smooth-transition" onclick="toggleUserMenu()">
          <span class="text-green-700 font-bold text-sm">JA</span>
           </button>
            
            <div id="userMenu" class="absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg border border-gray-200 z-50 hidden animate-fadeIn">
              <div class="p-3 border-b border-gray-100">
                <p class="text-sm font-medium">John Admin</p>
           <p class="text-xs text-gray-500">System Administrator</p>
              </div>
              <div class="py-1">
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 smooth-transition">
                  <div class="flex items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
           Profile
                  </div>
       </a>
                <a href="{{ route('admin.settings') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 smooth-transition">
                  <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
       </svg>
                    Settings
    </div>
                </a>
            </div>
              <div class="py-1 border-t border-gray-100">
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 smooth-transition">
                  <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path> </svg>
                    Log out
                  </div>
          </a>
              </div>
            </div>
          </div>
        </div>
      </header>

      <!-- Date & Time Banner -->
    <div class="bg-gray-50 border-b border-gray-200 fixed top-16 left-0 right-0 z-20 md:left-64 md:w-[calc(100%-16rem)]">
        <div class="flex items-center justify-between px-4 md:px-8 py-3">
          <div class="text-sm text-gray-500 flex items-center">
            <i class="far fa-calendar-alt mr-2 text-gray-400" aria-hidden="true"></i>
            <span id="current-date">Loading date...</span>
          <span class="mx-2 text-gray-300">|</span>
            <i class="far fa-clock mr-2 text-gray-400" aria-hidden="true"></i>
            <span id="current-time">Loading time...</span>
          </div>
          
        </div>
      </div>
     
      <!-- Main content area with proper top padding to account for fixed elements -->
      <main class="pt-2 px-4 md:px-8 pb-8">
        <!-- Your page content goes here -->
        {{ $slot ?? '' }}
    </main>
    </div>
</div>

<script>
// Global header search
document.addEventListener('DOMContentLoaded', function() {
  const input = document.getElementById('globalSearchInput');
  const dropdown = document.getElementById('globalSearchDropdown');
  const clearBtn = document.getElementById('globalSearchClear');
  let debounceTimer = null;
  let activeIndex = -1;
  let currentOptions = [];

  function hideDropdown() {
    if (!dropdown) return;
    dropdown.classList.add('hidden');
    dropdown.classList.add('opacity-0');
    activeIndex = -1;
    currentOptions = [];
  }
  function showDropdown() {
    if (!dropdown) return;
    dropdown.classList.remove('hidden');
    setTimeout(() => dropdown.classList.remove('opacity-0'), 10);
  }
  function renderLoading() {
    dropdown.innerHTML = `
      <div class="p-4">
        <div class="flex items-center gap-3 text-gray-500">
          <svg class="animate-spin h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V2C5.373 2 0 7.373 0 14h4z"></path>
          </svg>
          <span>Searching...</span>
        </div>
      </div>
    `;
    showDropdown();
  }
  function buildResultItem(item, index) {
    const icon = item.type === 'Vehicle'
      ? '<i class="fas fa-car text-yellow-500"></i>'
      : '<i class="fas fa-user text-blue-500"></i>';
    const typeBadge = item.type === 'Vehicle'
      ? '<span class="text-[10px] px-2 py-0.5 rounded-full bg-yellow-50 text-yellow-700 border border-yellow-100">Vehicle</span>'
      : '<span class="text-[10px] px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-100">Applicant</span>';
    return `
      <a href="${item.url}"
         class="global-search-item group flex items-start gap-3 px-4 py-3 hover:bg-green-50 focus:bg-green-50 outline-none cursor-pointer border-b last:border-b-0 border-gray-50"
         data-index="${index}"
         role="option"
         aria-selected="${activeIndex === index ? 'true' : 'false'}"
      >
        <div class="mt-0.5 w-5 h-5 flex items-center justify-center">${icon}</div>
        <div class="flex-1 min-w-0">
          <div class="flex items-center gap-2">
            <div class="font-semibold truncate text-gray-800 group-hover:text-green-700">${item.label}</div>
            ${typeBadge}
          </div>
          ${item.sublabel ? `<div class="text-xs text-gray-500 truncate mt-0.5">${item.sublabel}</div>` : ''}
        </div>
      </a>
    `;
  }
  function highlightActive() {
    const items = dropdown.querySelectorAll('.global-search-item');
    items.forEach((el, i) => {
      if (i === activeIndex) {
        el.classList.add('bg-green-50');
      } else {
        el.classList.remove('bg-green-50');
      }
    });
  }
  function renderResults(q, results) {
    if (results.length === 0) {
      dropdown.innerHTML = `<div class="px-4 py-5 text-gray-500 text-center">No results found.</div>`;
      showDropdown();
      return;
    }
    const users = results.filter(r => r.type !== 'Vehicle');
    const vehicles = results.filter(r => r.type === 'Vehicle');
    currentOptions = results;
    const section = (title, list) => `
      <div class="pt-2">
        <div class="px-4 pb-2 text-[11px] tracking-wide uppercase text-gray-400">${title}</div>
        <div class="max-h-[45vh] overflow-y-auto">
          ${list.map((item, i) => buildResultItem(item, i)).join('')}
        </div>
      </div>
    `;
    dropdown.innerHTML = `
      <div class="py-2">
        ${users.length ? section('Applicants', users) : ''}
        ${vehicles.length ? section('Vehicles', vehicles) : ''}
        <div class="px-3 pt-2">
          <a href="/admin/search/results?q=${encodeURIComponent(q)}"
             class="block w-full text-center px-4 py-2.5 mt-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-medium transition">
            Show all results
          </a>
        </div>
      </div>
    `;
    showDropdown();
  }
  async function doSearch(q) {
    try {
      renderLoading();
      const res = await fetch(`/admin/search?q=${encodeURIComponent(q)}`, { headers: { 'Accept':'application/json' }});
      if (!res.ok) throw new Error('Search failed');
      const data = await res.json();
      const results = (data && data.results) || [];
      renderResults(q, results);
    } catch (e) {
      dropdown.innerHTML = `<div class="px-4 py-3 text-red-500 text-center">Error performing search.</div>`;
      showDropdown();
    }
  }
  if (input && dropdown) {
    input.addEventListener('input', function() {
      clearTimeout(debounceTimer);
      const q = this.value.trim();
      if (!q) {
        hideDropdown();
        dropdown.innerHTML = '';
        clearBtn.classList.add('hidden');
        return;
      }
      clearBtn.classList.remove('hidden');
      debounceTimer = setTimeout(() => doSearch(q), 250);
    });
    input.addEventListener('keydown', function(e) {
      const items = dropdown.querySelectorAll('.global-search-item');
      if (!items.length) return;
      if (e.key === 'ArrowDown') {
        e.preventDefault();
        activeIndex = (activeIndex + 1) % items.length;
        highlightActive();
        items[activeIndex].scrollIntoView({ block: 'nearest' });
      } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        activeIndex = (activeIndex - 1 + items.length) % items.length;
        highlightActive();
        items[activeIndex].scrollIntoView({ block: 'nearest' });
      } else if (e.key === 'Enter') {
        if (activeIndex >= 0 && activeIndex < items.length) {
          const href = items[activeIndex].getAttribute('href');
          if (href) window.location.href = href;
        } else {
          // fallback to show all
          const q = input.value.trim();
          if (q) window.location.href = `/admin/search/results?q=${encodeURIComponent(q)}`;
        }
      } else if (e.key === 'Escape') {
        hideDropdown();
      }
    });
    document.addEventListener('click', function(e) {
      if (!dropdown.contains(e.target) && e.target !== input) {
        hideDropdown();
      }
    });
    if (clearBtn) {
      clearBtn.addEventListener('click', function() {
        input.value = '';
        input.focus();
        clearBtn.classList.add('hidden');
        hideDropdown();
      });
    }
  }
});
// Update date and time
function updateDateTime() {
  const now = new Date();
  const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
  
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
    
    if (dropdown && !userDropdown.contains(event.target)) {
      dropdown.classList.add('hidden');
    }
  });
});
</script>