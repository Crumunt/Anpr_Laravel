<div class="bg-white border-t border-gray-200 px-4 py-3 sm:px-6">
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">

        <!-- Results Summary -->
        <div class="flex items-center gap-2 text-sm text-gray-700">
            <span class="font-medium">Showing</span>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md bg-green-50 text-green-700 font-semibold border border-green-200">
                {{ $from }}
            </span>
            <span class="text-gray-500">to</span>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md bg-green-50 text-green-700 font-semibold border border-green-200">
                {{ $to }}
            </span>
            <span class="text-gray-500">of</span>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md bg-gray-100 text-gray-900 font-semibold border border-gray-300">
                {{ $total }}
            </span>
            <span class="font-medium">results</span>
        </div>

        <!-- Pagination Controls -->
        <div class="flex items-center gap-2">

            <!-- Previous Button -->
            @if ($currentPage <= 1)
                <span class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-400 bg-gray-50 border border-gray-200 rounded-lg cursor-not-allowed">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                    Previous
                </span>
            @else
                <button wire:click="previousPage"
                        type="button"
                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-green-500/50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                    Previous
                </button>
            @endif

            <!-- Page Numbers -->
            <div class="hidden sm:flex items-center gap-1">
                @foreach ($pages as $page)
                    @if ($page === '...')
                        <!-- Dots Separator -->
                        <span class="inline-flex items-center justify-center w-10 h-10 text-gray-500 font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M6 12a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </span>
                    @elseif ($page == $currentPage)
                        <!-- Active Page -->
                        <span class="inline-flex items-center justify-center w-10 h-10 text-sm font-bold text-white bg-gradient-to-br from-green-600 to-green-700 rounded-lg shadow-sm ring-2 ring-green-500/50">
                            {{ $page }}
                        </span>
                    @else
                        <!-- Inactive Page -->
                        <button wire:click="goToPage({{ $page }})"
                                type="button"
                                class="inline-flex items-center justify-center w-10 h-10 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-green-50 hover:border-green-400 hover:text-green-700 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-green-500/50">
                            {{ $page }}
                        </button>
                    @endif
                @endforeach
            </div>

            <!-- Mobile: Current Page Indicator -->
            <div class="sm:hidden inline-flex items-center px-3 py-2 text-sm font-semibold text-gray-900 bg-gray-100 border border-gray-300 rounded-lg">
                Page {{ $currentPage }} of {{ $lastPage }}
            </div>

            <!-- Next Button -->
            @if ($currentPage >= $lastPage)
                <span class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-400 bg-gray-50 border border-gray-200 rounded-lg cursor-not-allowed">
                    Next
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </span>
            @else
                <button wire:click="nextPage"
                        type="button"
                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-gradient-to-r from-green-600 to-green-700 rounded-lg hover:from-green-700 hover:to-green-800 transition-all duration-200 shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-green-500/50">
                    Next
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            @endif
        </div>
    </div>

    <!-- Quick Jump -->
    @if($lastPage > 10)
    <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-center gap-3" x-data="{ showJump: false, pageNum: '' }">
        <button @click="showJump = !showJump"
                type="button"
                class="text-sm text-gray-600 hover:text-green-600 font-medium transition-colors duration-200">
            <span x-show="!showJump">Jump to page...</span>
            <span x-show="showJump" x-cloak>Cancel</span>
        </button>

        <div x-show="showJump"
             x-cloak
             x-transition
             class="flex items-center gap-2">
            <input type="number"
                   x-model="pageNum"
                   min="1"
                   max="{{ $lastPage }}"
                   placeholder="Page #"
                   @keydown.enter="$wire.jumpToPage(pageNum)"
                   class="w-20 px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/50 focus:border-green-500">
            <button @click="$wire.jumpToPage(pageNum)"
                    type="button"
                    class="px-3 py-1.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors duration-200">
                Go
            </button>
        </div>
    </div>
    @endif
</div>
