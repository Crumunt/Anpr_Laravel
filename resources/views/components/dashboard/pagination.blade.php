@props([   
    'pagination' => [
        'currentPage' => 1,
        'totalPages' => 1,
        'totalItems' => 0,
        'itemsPerPage' => 10
    ],
    'pageSizes' => [10, 25, 50, 100],
    'showSizeSelector' => true,
    'theme' => 'light' // New prop to support light/dark themes
])

<div x-data="{
    currentPage: {{ $pagination['currentPage'] ?? 1 }},
    totalPages: {{ $pagination['totalPages'] ?? 1 }},
    totalItems: {{ $pagination['totalItems'] ?? 0 }},
    itemsPerPage: {{ $pagination['itemsPerPage'] ?? 10 }},
    isLoading: false,
    showJumpToPage: false,
    jumpToPageValue: '',
    
    get showingFrom() {
        return this.totalItems === 0 ? 0 : (this.currentPage - 1) * this.itemsPerPage + 1;
    },
    
    get showingTo() {
        return Math.min(this.currentPage * this.itemsPerPage, this.totalItems);
    },
    
    async goToPage(page) {
        if (page >= 1 && page <= this.totalPages && page !== this.currentPage && !this.isLoading) {
            this.isLoading = true;
            this.currentPage = page;
            
            this.$dispatch('pagination-changed', { 
                page: this.currentPage, 
                itemsPerPage: this.itemsPerPage 
            });
            
            setTimeout(() => {
                this.isLoading = false;
            }, 300);
        }
    },
    
    async jumpToPageHandler() {
        const page = parseInt(this.jumpToPageValue);
        if (!isNaN(page) && page >= 1 && page <= this.totalPages) {
            this.goToPage(page);
            this.showJumpToPage = false;
            this.jumpToPageValue = '';
        }
    },
    
    async changePageSize(size) {
        if (this.itemsPerPage !== parseInt(size) && !this.isLoading) {
            this.isLoading = true;
            this.itemsPerPage = parseInt(size);
            this.currentPage = 1;
            
            this.$dispatch('pagination-changed', { 
                page: 1, 
                itemsPerPage: this.itemsPerPage 
            });
            
            setTimeout(() => {
                this.isLoading = false;
            }, 300);
        }
    },
    
    getPageNumbers() {
        const current = this.currentPage;
        const last = this.totalPages;
        const range = [];
        const rangeWithDots = [];
        let l;
        
        if (last <= 5) {
            for (let i = 1; i <= last; i++) {
                range.push(i);
            }
        } else {
            range.push(1);
            
            if (current <= 3) {
                range.push(2, 3, 4);
            } else if (current >= last - 2) {
                range.push(last - 3, last - 2, last - 1);
            } else {
                range.push(current - 1, current, current + 1);
            }
            
            if (last > 1) range.push(last);
            
            for (let i of range) {
                if (l) {
                    if (i - l === 2) {
                        rangeWithDots.push(l + 1);
                    } else if (i - l > 2) {
                        rangeWithDots.push('...');
                    }
                }
                rangeWithDots.push(i);
                l = i;
            }
        }
        
        return rangeWithDots;
    }
}" 
   class="relative w-full mt-6 {{ $theme === 'dark' ? 'bg-gray-800 border-gray-700 text-gray-200' : 'bg-white border-gray-100 text-gray-800' }} rounded-lg border shadow-md overflow-hidden transition-all duration-200"
   @keydown.escape="showJumpToPage = false"
   @click.away="showJumpToPage = false">
    
    <!-- Loading overlay -->
    <div x-show="isLoading" 
         x-transition:enter="transition ease-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="transition ease-in duration-200" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0"
         class="absolute inset-0 {{ $theme === 'dark' ? 'bg-gray-800/80' : 'bg-white/80' }} backdrop-blur-sm z-10 flex items-center justify-center">
        <div class="flex items-center space-x-3 px-4 py-2 rounded-full {{ $theme === 'dark' ? 'bg-gray-700' : 'bg-white' }} shadow-lg">
            <svg class="animate-spin h-5 w-5 text-emerald-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-sm font-medium {{ $theme === 'dark' ? 'text-gray-200' : 'text-gray-700' }}">Loading...</span>
        </div>
    </div>
    
    <div class="flex flex-col sm:flex-row items-center justify-between px-4 py-3 border-b {{ $theme === 'dark' ? 'border-gray-700' : 'border-gray-100' }}">
        <!-- Results info -->
        <div class="text-sm {{ $theme === 'dark' ? 'text-gray-300' : 'text-gray-600' }} mb-3 sm:mb-0 flex flex-wrap items-center">
            Showing <span class="font-medium mx-1" x-text="showingFrom"></span> to <span class="font-medium mx-1" x-text="showingTo"></span> of <span class="font-medium mx-1" x-text="totalItems"></span> results
        </div>
        
        <!-- Controls group -->
        <div class="flex items-center space-x-4">
            <!-- Jump to page -->
            <div class="relative">
                <button
                    @click="showJumpToPage = !showJumpToPage"
                    class="flex items-center text-sm {{ $theme === 'dark' ? 'text-gray-300 hover:text-white' : 'text-gray-600 hover:text-gray-900' }} transition-colors duration-200"
                    title="Jump to page">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5v-4m0 4h-4m4 0l-5-5" />
                    </svg>
                    <span class="hidden sm:inline">Jump</span>
                </button>
                
                <!-- Jump to page dropdown -->
                <div x-show="showJumpToPage" 
                     x-transition:enter="transition ease-out duration-200" 
                     x-transition:enter-start="opacity-0 translate-y-1" 
                     x-transition:enter-end="opacity-100 translate-y-0" 
                     x-transition:leave="transition ease-in duration-150" 
                     x-transition:leave-start="opacity-100 translate-y-0" 
                     x-transition:leave-end="opacity-0 translate-y-1"
                     class="absolute right-0 mt-2 p-2 w-48 rounded-md shadow-lg {{ $theme === 'dark' ? 'bg-gray-700' : 'bg-white' }} ring-1 ring-black ring-opacity-5 z-20">
                    <div class="p-1">
                        <div class="flex items-center space-x-2">
                            <input type="number" 
                                   x-model="jumpToPageValue" 
                                   min="1" 
                                   :max="totalPages"
                                   @keydown.enter="jumpToPageHandler()"
                                   class="block w-full rounded-md text-sm {{ $theme === 'dark' ? 'bg-gray-800 border-gray-600 text-gray-200 focus:border-emerald-400 focus:ring-emerald-400/25' : 'border-gray-300 text-gray-700 focus:border-emerald-500 focus:ring-emerald-500/25' }} focus:ring focus:ring-opacity-50 transition duration-200"
                                   placeholder="Page #">
                            <button @click="jumpToPageHandler()"
                                    class="p-1 rounded-md {{ $theme === 'dark' ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-emerald-500 hover:bg-emerald-600' }} text-white text-sm transition-colors duration-200">
                                Go
                            </button>
                        </div>
                        <div class="mt-1 text-xs {{ $theme === 'dark' ? 'text-gray-400' : 'text-gray-500' }}">
                            Enter a page number (1-<span x-text="totalPages"></span>)
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Items per page selector -->
            @if($showSizeSelector)
            <div class="flex items-center">
                <label for="pageSizeSelect" class="text-sm {{ $theme === 'dark' ? 'text-gray-300' : 'text-gray-600' }} mr-2">Show:</label>
                <select 
                    id="pageSizeSelect" 
                    x-model="itemsPerPage" 
                    @change="changePageSize($event.target.value)"
                    class="form-select rounded-md text-sm py-1 pl-2 pr-8 {{ $theme === 'dark' ? 'bg-gray-700 border-gray-600 text-gray-200 focus:border-emerald-400 focus:ring-emerald-400/25' : 'border-gray-300 text-gray-700 focus:border-emerald-500 focus:ring-emerald-500/25' }} focus:ring focus:ring-opacity-50 cursor-pointer transition duration-200">
                    @foreach($pageSizes as $size)
                    <option value="{{ $size }}">{{ $size }}</option>
                    @endforeach
                </select>
            </div>
            @endif
        </div>
    </div>
    
    <div class="flex items-center justify-between px-4 py-3 {{ $theme === 'dark' ? 'bg-gray-800' : 'bg-white' }}">
        <div class="flex flex-1 justify-between sm:justify-start sm:space-x-4">
            <!-- First and Previous page buttons -->
            <div class="flex space-x-1">
                <button 
                    @click="goToPage(1)" 
                    :disabled="currentPage === 1 || isLoading" 
                    :class="{
                        'opacity-50 cursor-not-allowed': currentPage === 1 || isLoading, 
                        '{{ $theme === 'dark' ? 'hover:bg-gray-700' : 'hover:bg-gray-50' }}': currentPage !== 1 && !isLoading
                    }"
                    class="relative inline-flex items-center px-2 py-2 rounded-md border {{ $theme === 'dark' ? 'border-gray-600 bg-gray-800 text-gray-300' : 'border-gray-300 bg-white text-gray-500' }} text-sm font-medium transition-colors duration-200 focus:outline-none focus:ring-2 {{ $theme === 'dark' ? 'focus:ring-emerald-500/30' : 'focus:ring-emerald-500/50' }}"
                    aria-label="First Page"
                    title="First Page">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M15.707 15.707a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 010 1.414zm-6 0a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 011.414 1.414L5.414 10l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                
                <button 
                    @click="goToPage(currentPage - 1)" 
                    :disabled="currentPage === 1 || isLoading" 
                    :class="{
                        'opacity-50 cursor-not-allowed': currentPage === 1 || isLoading, 
                        '{{ $theme === 'dark' ? 'hover:bg-gray-700' : 'hover:bg-gray-50' }}': currentPage !== 1 && !isLoading
                    }"
                    class="relative inline-flex items-center px-2 py-2 rounded-md border {{ $theme === 'dark' ? 'border-gray-600 bg-gray-800 text-gray-300' : 'border-gray-300 bg-white text-gray-500' }} text-sm font-medium transition-colors duration-200 focus:outline-none focus:ring-2 {{ $theme === 'dark' ? 'focus:ring-emerald-500/30' : 'focus:ring-emerald-500/50' }}"
                    aria-label="Previous Page"
                    title="Previous Page">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            
            <!-- Page numbers -->
            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                <template x-for="(page, index) in getPageNumbers()" :key="index">
                    <template x-if="page === '...'">
                        <span class="relative inline-flex items-center px-3 py-2 {{ $theme === 'dark' ? 'bg-gray-800 text-gray-400 border-gray-600' : 'bg-white text-gray-700 border-gray-300' }} text-sm font-medium">
                            ...
                        </span>
                    </template>
                    <template x-if="page !== '...'">
                        <button
                            @click="goToPage(page)"
                            :class="{
                                'z-10 {{ $theme === 'dark' ? 'bg-emerald-900/30 border-emerald-500 text-emerald-400' : 'bg-emerald-50 border-emerald-500 text-emerald-600' }}': currentPage === page,
                                '{{ $theme === 'dark' ? 'bg-gray-800 border-gray-600 text-gray-300 hover:bg-gray-700' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50' }}': currentPage !== page,
                                'opacity-50 cursor-not-allowed': isLoading
                            }"
                            class="relative inline-flex items-center px-3 py-2 border text-sm font-medium transition-colors duration-200 focus:outline-none focus:ring-2 {{ $theme === 'dark' ? 'focus:ring-emerald-500/30' : 'focus:ring-emerald-500/50' }}"
                            :disabled="isLoading"
                            :aria-current="currentPage === page ? 'page' : null">
                            <span x-text="page"></span>
                        </button>
                    </template>
                </template>
            </nav>
            
            <!-- Next and Last page buttons -->
            <div class="flex space-x-1">
                <button 
                    @click="goToPage(currentPage + 1)" 
                    :disabled="currentPage === totalPages || isLoading" 
                    :class="{
                        'opacity-50 cursor-not-allowed': currentPage === totalPages || isLoading, 
                        '{{ $theme === 'dark' ? 'hover:bg-gray-700' : 'hover:bg-gray-50' }}': currentPage !== totalPages && !isLoading
                    }"
                    class="relative inline-flex items-center px-2 py-2 rounded-md border {{ $theme === 'dark' ? 'border-gray-600 bg-gray-800 text-gray-300' : 'border-gray-300 bg-white text-gray-500' }} text-sm font-medium transition-colors duration-200 focus:outline-none focus:ring-2 {{ $theme === 'dark' ? 'focus:ring-emerald-500/30' : 'focus:ring-emerald-500/50' }}"
                    aria-label="Next Page"
                    title="Next Page">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
                
                <button 
                    @click="goToPage(totalPages)" 
                    :disabled="currentPage === totalPages || isLoading" 
                    :class="{
                        'opacity-50 cursor-not-allowed': currentPage === totalPages || isLoading, 
                        '{{ $theme === 'dark' ? 'hover:bg-gray-700' : 'hover:bg-gray-50' }}': currentPage !== totalPages && !isLoading
                    }"
                    class="relative inline-flex items-center px-2 py-2 rounded-md border {{ $theme === 'dark' ? 'border-gray-600 bg-gray-800 text-gray-300' : 'border-gray-300 bg-white text-gray-500' }} text-sm font-medium transition-colors duration-200 focus:outline-none focus:ring-2 {{ $theme === 'dark' ? 'focus:ring-emerald-500/30' : 'focus:ring-emerald-500/50' }}"
                    aria-label="Last Page"
                    title="Last Page">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 15.707a1 1 0 001.414 0l5-5a1 1 0 000-1.414l-5-5a1 1 0 00-1.414 1.414L8.586 10l-4.293 4.293a1 1 0 000 1.414zm6 0a1 1 0 001.414 0l5-5a1 1 0 000-1.414l-5-5a1 1 0 00-1.414 1.414L14.586 10l-4.293 4.293a1 1 0 000 1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>