@props([
    'currentPage' => 1,
    'totalPages' => 10,
    'totalItems' => 156,
    'perPage' => 15,
    'showingFrom' => 1,
    'showingTo' => 15
])

<div class="px-4 md:px-6 py-4 flex flex-wrap items-center justify-between gap-3 border-t border-gray-200 bg-gray-50">
    <div class="flex items-center">
        <label class="text-sm text-gray-600 mr-2" for="entries-select">Show</label>
        <select id="entries-select" class="text-sm border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 py-1 pl-2 pr-8 bg-white" aria-label="Number of entries to show" onchange="changePerPage(this.value)">
            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
            <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15</option>
            <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
            <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
        </select>
        <span class="text-sm text-gray-600 ml-2">entries</span>
    </div>
    
    <div>
        <p class="text-sm text-gray-700">
            Showing <span class="font-medium">{{ $showingFrom }}</span> to <span class="font-medium">{{ $showingTo }}</span> of <span class="font-medium">{{ number_format($totalItems) }}</span> entries
        </p>
    </div>
    
    <div>
        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
            <!-- Previous button -->
            <button 
                class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 {{ $currentPage <= 1 ? 'cursor-not-allowed opacity-50' : '' }}"
                onclick="changePage({{ $currentPage - 1 }})"
                {{ $currentPage <= 1 ? 'disabled' : '' }}
                aria-label="Previous page"
            >
                <i class="fas fa-chevron-left text-xs" aria-hidden="true"></i>
            </button>

            <!-- Page numbers -->
            @php
                $startPage = max(1, $currentPage - 2);
                $endPage = min($totalPages, $currentPage + 2);
                
                // Adjust start and end to show 5 pages when possible
                if ($endPage - $startPage < 4) {
                    if ($startPage == 1) {
                        $endPage = min($totalPages, $startPage + 4);
                    } else {
                        $startPage = max(1, $endPage - 4);
                    }
                }
            @endphp

            <!-- First page -->
            @if($startPage > 1)
                <button 
                    class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                    onclick="changePage(1)"
                    aria-label="Go to page 1"
                >
                    1
                </button>
                @if($startPage > 2)
                    <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                        ...
                    </span>
                @endif
            @endif

            <!-- Page numbers -->
            @for($i = $startPage; $i <= $endPage; $i++)
                <button 
                    class="{{ $i == $currentPage ? 'z-10 bg-green-50 border-green-500 text-green-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50' }} relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                    onclick="changePage({{ $i }})"
                    aria-label="Go to page {{ $i }}"
                    aria-current="{{ $i == $currentPage ? 'page' : 'false' }}"
                >
                    {{ $i }}
                </button>
            @endfor

            <!-- Last page -->
            @if($endPage < $totalPages)
                @if($endPage < $totalPages - 1)
                    <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                        ...
                    </span>
                @endif
                <button 
                    class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                    onclick="changePage({{ $totalPages }})"
                    aria-label="Go to page {{ $totalPages }}"
                >
                    {{ $totalPages }}
                </button>
            @endif

            <!-- Next button -->
            <button 
                class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 {{ $currentPage >= $totalPages ? 'cursor-not-allowed opacity-50' : '' }}"
                onclick="changePage({{ $currentPage + 1 }})"
                {{ $currentPage >= $totalPages ? 'disabled' : '' }}
                aria-label="Next page"
            >
                <i class="fas fa-chevron-right text-xs" aria-hidden="true"></i>
            </button>
        </nav>
    </div>
</div>

<script>
// Pagination functionality
function changePage(page) {
    if (page < 1 || page > {{ $totalPages }}) {
        return;
    }
    
    // Show loading state
    showLoadingState();
    
    // Update URL parameters
    const url = new URL(window.location);
    url.searchParams.set('page', page);
    window.history.pushState({}, '', url);
    
    // Simulate loading delay
    setTimeout(() => {
        loadUsers(page);
    }, 500);
}

function changePerPage(perPage) {
    // Show loading state
    showLoadingState();
    
    // Update URL parameters
    const url = new URL(window.location);
    url.searchParams.set('per_page', perPage);
    url.searchParams.set('page', 1); // Reset to first page
    window.history.pushState({}, '', url);
    
    // Simulate loading delay
    setTimeout(() => {
        loadUsers(1, perPage);
    }, 500);
}

function showLoadingState() {
    const tableContainer = document.querySelector('.overflow-x-auto');
    if (tableContainer) {
        const loadingOverlay = document.createElement('div');
        loadingOverlay.id = 'loading-overlay';
        loadingOverlay.className = 'absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center z-10';
        loadingOverlay.innerHTML = `
            <div class="text-center">
                <div class="spinner mb-4"></div>
                <p class="text-gray-600">Loading users...</p>
            </div>
        `;
        
        tableContainer.style.position = 'relative';
        tableContainer.appendChild(loadingOverlay);
    }
}

function loadUsers(page = 1, perPage = {{ $perPage }}) {
    // Remove loading state
    const loadingOverlay = document.getElementById('loading-overlay');
    if (loadingOverlay) {
        loadingOverlay.remove();
    }
    
    // Update pagination info
    const totalItems = {{ $totalItems }};
    const showingFrom = (page - 1) * perPage + 1;
    const showingTo = Math.min(page * perPage, totalItems);
    
    // Update the "Showing X to Y of Z entries" text
    const infoElement = document.querySelector('.text-sm.text-gray-700');
    if (infoElement) {
        infoElement.innerHTML = `Showing <span class="font-medium">${showingFrom}</span> to <span class="font-medium">${showingTo}</span> of <span class="font-medium">${totalItems.toLocaleString()}</span> entries`;
    }
    
    // Update pagination buttons
    updatePaginationButtons(page, {{ $totalPages }});
    
    // Simulate data update (in real implementation, this would be an AJAX call)
    console.log(`Loading users for page ${page} with ${perPage} per page`);
    
    // Show success notification
    showNotification(`Loaded page ${page}`, 'success');
}

function updatePaginationButtons(currentPage, totalPages) {
    // Update previous button
    const prevBtn = document.querySelector('[aria-label="Previous page"]');
    if (prevBtn) {
        prevBtn.disabled = currentPage <= 1;
        prevBtn.className = `relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 ${currentPage <= 1 ? 'cursor-not-allowed opacity-50' : ''}`;
    }
    
    // Update next button
    const nextBtn = document.querySelector('[aria-label="Next page"]');
    if (nextBtn) {
        nextBtn.disabled = currentPage >= totalPages;
        nextBtn.className = `relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 ${currentPage >= totalPages ? 'cursor-not-allowed opacity-50' : ''}`;
    }
    
    // Update page number buttons
    const pageButtons = document.querySelectorAll('[aria-label^="Go to page"]');
    pageButtons.forEach(btn => {
        const pageNum = parseInt(btn.textContent.trim());
        if (pageNum === currentPage) {
            btn.className = 'z-10 bg-green-50 border-green-500 text-green-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium';
            btn.setAttribute('aria-current', 'page');
        } else {
            btn.className = 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium';
            btn.setAttribute('aria-current', 'false');
        }
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Set up keyboard navigation for pagination
    document.addEventListener('keydown', function(e) {
        if (e.key === 'ArrowLeft' && e.ctrlKey) {
            e.preventDefault();
            const prevBtn = document.querySelector('[aria-label="Previous page"]:not([disabled])');
            if (prevBtn) {
                prevBtn.click();
            }
        } else if (e.key === 'ArrowRight' && e.ctrlKey) {
            e.preventDefault();
            const nextBtn = document.querySelector('[aria-label="Next page"]:not([disabled])');
            if (nextBtn) {
                nextBtn.click();
            }
        }
    });
    
    // Add focus management for accessibility
    const paginationButtons = document.querySelectorAll('nav button');
    paginationButtons.forEach(btn => {
        btn.addEventListener('focus', function() {
            this.style.outline = '2px solid var(--clsu-accent)';
            this.style.outlineOffset = '2px';
        });
        
        btn.addEventListener('blur', function() {
            this.style.outline = '';
            this.style.outlineOffset = '';
        });
    });
});

// Notification function (if not already defined)
function showNotification(message, type = 'info') {
    // Check if notification function already exists
    if (typeof window.showNotification === 'function') {
        window.showNotification(message, type);
        return;
    }
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
        type === 'success' ? 'bg-green-500 text-white' : 
        type === 'error' ? 'bg-red-500 text-white' : 
        'bg-blue-500 text-white'
    }`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${type === 'success' ? 'fa-check' : type === 'error' ? 'fa-exclamation-triangle' : 'fa-info-circle'} mr-2"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}
</script>
