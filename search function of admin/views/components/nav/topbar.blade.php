<div>
    <div class="flex-1 ml-0 md:ml-64 smooth-transition">
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
          <div class="relative w-full md:w-2/3 md:block">
            <input
              class="w-full peer z-[21] px-4 py-2.5 text-base rounded-xl outline-none duration-200 border border-gray-300 focus:border-[#11BE86] ring-2 ring-[transparent] focus:ring-[#11BE86] bg-white"
              color="white"
              size="xl"
              placeholder="Search..."
              id="searchInput"
              autocomplete="off"
            />
            <div
              id="searchResultsDropdown"
              class="opacity-0 -translate-y-2 peer-focus:translate-y-0 pointer-events-none peer-focus:pointer-events-auto duration-200 peer-focus:opacity-100 absolute top-16 left-0 w-full z-[500] rounded-xl border border-gray-200 p-4 bg-white shadow-2xl"
            >
              <p class="font-semibold text-xs text-[#5D5D5F]">LAST SEARCHES</p>
              <ul id="lastSearches" class="flex gap-2 flex-col mt-2">
              </ul>
              <p class="font-semibold text-xs text-[#5D5D5F] mt-4">EXTENDED SEARCHES</p>
              <ul id="extendedSearches" class="flex gap-2 mt-4 flex-col">
              </ul>
            </div>
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
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 smooth-transition">
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
     
      <main class="pt-2 px-4 md:px-8 pb-8">
        <!-- Your page content goes here -->
        {{ $slot ?? '' }}
    </main>
    </div>
</div>

<script>
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

const sampleData = [
    // Admins
    { type: 'Admin', name: 'Admin User', email: 'admin@example.com', phone: '(555) 123-4567', role: 'Super Admin' },
    { type: 'Admin', name: 'Moderator One', email: 'mod1@example.com', phone: '(555) 765-4321', role: 'Moderator' },
    { type: 'Admin', name: 'Staff Member', email: 'staff@example.com', phone: '(555) 987-6543', role: 'Admin' },
    { type: 'Admin', name: 'System User', email: 'system@example.com', phone: '(555) 456-7890', role: 'Admin' },
    { type: 'Admin', name: 'Guest Access', email: 'guest@example.com', phone: '(555) 234-5678', role: 'Moderator' },
    // Applicants
    { type: 'Applicant', name: 'John Doe', email: 'john.doe@example.com', phone: '(555) 123-4567', status: 'Pending' },
    { type: 'Applicant', name: 'Jane Smith', email: 'jane.smith@example.com', phone: '(555) 765-4321', status: 'Approved' },
    // Vehicles
    { type: 'Vehicle', vehicle: 'Toyota Camry', owner: 'John Doe', registration_date: '2023-05-15' },
    { type: 'Vehicle', vehicle: 'Honda Civic', owner: 'Jane Smith', registration_date: '2023-05-16' },
    // RFID
    { type: 'RFID', rfid_tag: 'RFID12345', status: 'Active', assigned_to: 'John Doe' },
    { type: 'RFID', rfid_tag: 'RFID67890', status: 'Inactive', assigned_to: 'Jane Smith' },
    { type: 'RFID', rfid_tag: 'RFID23456', status: 'Active', assigned_to: 'Michael Johnson' },
    { type: 'RFID', rfid_tag: 'RFID78901', status: 'Active', assigned_to: 'Emily Wilson' },
];

let searchTimeout = null;
const searchInput = document.getElementById('searchInput');
const resultsDropdown = document.getElementById('searchResultsDropdown');

function filterSampleData(query) {
    query = query.toLowerCase();
    return sampleData.filter(item => {
        return Object.values(item).some(val =>
            typeof val === 'string' && val.toLowerCase().includes(query)
        );
    });
}

// Loading spinner logic
let isLoading = false;
function showLoadingSpinner() {
    if (!document.getElementById('searchLoadingSpinner')) {
        const spinner = document.createElement('div');
        spinner.id = 'searchLoadingSpinner';
        spinner.className = 'absolute right-3 top-1/2 -translate-y-1/2';
        spinner.innerHTML = `<svg class='animate-spin h-5 w-5 text-green-500' xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24'><circle class='opacity-25' cx='12' cy='12' r='10' stroke='currentColor' stroke-width='4'></circle><path class='opacity-75' fill='currentColor' d='M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z'></path></svg>`;
        searchInput.parentNode.appendChild(spinner);
    }
    isLoading = true;
}
function hideLoadingSpinner() {
    const spinner = document.getElementById('searchLoadingSpinner');
    if (spinner) spinner.remove();
    isLoading = false;
}

// --- Enhanced Search Dropdown Logic ---
let lastSearches = JSON.parse(localStorage.getItem('lastSearches') || '[]');
function addLastSearch(query) {
    if (!query) return;
    lastSearches = lastSearches.filter(q => q !== query);
    lastSearches.unshift(query);
    if (lastSearches.length > 5) lastSearches = lastSearches.slice(0, 5);
    localStorage.setItem('lastSearches', JSON.stringify(lastSearches));
}

function renderLastSearches() {
    const ul = document.getElementById('lastSearches');
    if (!ul) return;
    ul.innerHTML = lastSearches.length ? lastSearches.map(q => `<li class="px-2 cursor-pointer text-sm hover:bg-green-100 py-2 rounded-lg" onclick="document.getElementById('searchInput').value='${q}';document.getElementById('searchInput').focus();triggerSearch('${q}')">${q}</li>`).join('') : '<li class="text-gray-400 text-xs">No recent searches</li>';
}

function renderExtendedSearches(results, query) {
    const ul = document.getElementById('extendedSearches');
    if (!ul) return;
    if (!results.length) {
        ul.innerHTML = '<li class="text-gray-400 text-xs">No results found.</li>';
        return;
    }
    ul.innerHTML = results.slice(0, 5).map(item => {
        let identifier = '';
        let icon = '';
        if (item.type === 'Admin') icon = '<i class="fas fa-user-shield text-indigo-500 mr-2"></i>';
        else if (item.type === 'Applicant') icon = '<i class="fas fa-user text-blue-500 mr-2"></i>';
        else if (item.type === 'Vehicle') icon = '<i class="fas fa-car text-yellow-500 mr-2"></i>';
        else if (item.type === 'RFID') icon = '<i class="fas fa-ticket-alt text-purple-500 mr-2"></i>';
        if (item.type === 'Admin' || item.type === 'Applicant') {
            identifier = encodeURIComponent(item.email || item.name);
        } else if (item.type === 'Vehicle') {
            identifier = encodeURIComponent(item.vehicle);
        } else if (item.type === 'RFID') {
            identifier = encodeURIComponent(item.rfid_tag);
        }
        return `<li class="grid grid-cols-10 cursor-pointer border border-[#E7EDFB] rounded-xl overflow-hidden group hover:bg-green-50" onclick="window.location='/details/${item.type.toLowerCase()}/${identifier}'">
            <div class="col-span-3 flex items-center justify-center bg-gray-50">
                <span class="text-green-300 text-2xl">${icon}</span>
            </div>
            <div class="col-span-7 p-3 flex flex-col justify-between">
                <h4 class="text-base duration-200 group-hover:text-[#11BE86] font-medium text-black line-clamp-2">${item.name || item.vehicle || item.rfid_tag}</h4>
                <div class="flex justify-between items-center mt-4">
                    <p class="flex text-sm gap-2 text-[#9A999B] items-center">
                        <i class="fas fa-calendar-alt w-4 h-4"></i> 18.04.2024
                    </p>
                    <p class="flex text-sm gap-1 text-[#9A999B] items-center">
                        <i class="fas fa-eye w-4 h-4"></i> 806
                    </p>
                </div>
            </div>
        </li>`;
    }).join('');
}

function triggerSearch(query) {
    // Use your existing filterSampleData logic
    const results = filterSampleData(query);
    renderExtendedSearches(results, query);
}

document.addEventListener('DOMContentLoaded', function() {
    renderLastSearches();
    document.getElementById('searchInput').addEventListener('input', function() {
        const query = this.value.trim();
        triggerSearch(query);
    });
    document.getElementById('searchInput').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            addLastSearch(this.value.trim());
            renderLastSearches();
        }
    });
});

if (searchInput && resultsDropdown) {
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        if (!query) {
            hideDropdown();
            resultsDropdown.innerHTML = '';
            hideLoadingSpinner();
            return;
        }
        showLoadingSpinner();
        searchTimeout = setTimeout(() => {
            const results = filterSampleData(query);
            let html = '';
            if (results.length > 0) {
                html += `<div class='px-4 pt-3 pb-1 text-xs text-gray-400 font-semibold tracking-wide uppercase'>Quick Results</div>`;
                results.slice(0, 5).forEach(item => {
                    let identifier = '';
                    let icon = '';
                    if (item.type === 'Admin') icon = '<i class="fas fa-user-shield text-indigo-500 mr-2"></i>';
                    else if (item.type === 'Applicant') icon = '<i class="fas fa-user text-blue-500 mr-2"></i>';
                    else if (item.type === 'Vehicle') icon = '<i class="fas fa-car text-yellow-500 mr-2"></i>';
                    else if (item.type === 'RFID') icon = '<i class="fas fa-ticket-alt text-purple-500 mr-2"></i>';
                    if (item.type === 'Admin' || item.type === 'Applicant') {
                        identifier = encodeURIComponent(item.email || item.name);
                    } else if (item.type === 'Vehicle') {
                        identifier = encodeURIComponent(item.vehicle);
                    } else if (item.type === 'RFID') {
                        identifier = encodeURIComponent(item.rfid_tag);
                    }
                    html += `<div class="flex items-center gap-2 px-4 py-3 hover:bg-green-50 cursor-pointer border-b last:border-b-0 transition rounded-lg" style="min-height:48px;" onclick="window.location='/details/${item.type.toLowerCase()}/${identifier}'">
                        ${icon}
                        <div class="flex-1 min-w-0">
                            <div class="font-semibold truncate">${item.name || item.vehicle || item.rfid_tag}</div>
                            <div class="text-xs text-gray-500 truncate">${item.type}${item.email ? ' - ' + item.email : ''}${item.owner ? ' - ' + item.owner : ''}${item.assigned_to ? ' - ' + item.assigned_to : ''}</div>
                        </div>
                    </div>`;
                });
            } else {
                html += `<div class="px-4 py-3 text-gray-500 text-center">No results found.</div>`;
            }
            html += `<div class="px-4 py-3 text-center bg-green-50 hover:bg-green-200 cursor-pointer font-bold rounded-b-2xl transition text-green-700 tracking-wide text-base border-t border-green-100" onclick="window.location='/search/results?q=${encodeURIComponent(query)}'">
                <i class='fas fa-search mr-2'></i>Show all results
            </div>`;
            resultsDropdown.innerHTML = html;
            showDropdown();
        }, 200);
    });
    document.addEventListener('click', function(event) {
        if (!resultsDropdown.contains(event.target) && event.target !== searchInput) {
            hideDropdown();
        }
    });
}

// Smooth dropdown show/hide
function showDropdown() {
    resultsDropdown.classList.remove('hidden');
    setTimeout(() => {
        resultsDropdown.classList.remove('opacity-0', 'pointer-events-none', 'scale-95');
        resultsDropdown.classList.add('opacity-100', 'scale-100');
    }, 10);
}
function hideDropdown() {
    resultsDropdown.classList.add('opacity-0', 'pointer-events-none', 'scale-95');
    setTimeout(() => {
        resultsDropdown.classList.add('hidden');
        resultsDropdown.classList.remove('opacity-100', 'scale-100');
    }, 200);
}
</script>