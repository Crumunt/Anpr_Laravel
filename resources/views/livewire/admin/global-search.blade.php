<div class="relative w-64 md:w-80 hidden md:block"
     x-data="{ open: @entangle('showDropdown') }"
     @click.away="$wire.hideDropdown()">

    <!-- Search Input -->
    <input
        type="text"
        wire:model.live.debounce.300ms="query"
        wire:keydown.arrow-up.prevent="navigateUp"
        wire:keydown.arrow-down.prevent="navigateDown"
        wire:keydown.enter.prevent="selectActive"
        wire:keydown.escape="hideDropdown"
        class="w-full pl-11 pr-11 py-2.5 text-base rounded-xl outline-none duration-200 border border-gray-200 focus:border-[#11BE86] ring-2 ring-transparent focus:ring-[#11BE86] bg-white shadow-sm focus:shadow-md"
        placeholder="Search applicants, vehicles..."
        autocomplete="off"
    />

    <!-- Search Icon -->
    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        @if($isLoading)
            <svg class="animate-spin h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        @else
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        @endif
    </div>

    <!-- Clear Button -->
    @if(strlen($query) > 0)
        <button
            wire:click="clearSearch"
            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
            type="button"
            aria-label="Clear search"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    @endif

    <!-- Dropdown Results -->
    @if($showDropdown)
        <div class="absolute top-14 left-0 w-full z-[200] rounded-xl border border-gray-100 bg-white shadow-2xl overflow-hidden"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2">

            @if($isLoading)
                <!-- Loading State -->
                <div class="p-4">
                    <div class="flex items-center gap-3 text-gray-500">
                        <svg class="animate-spin h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Searching...</span>
                    </div>
                </div>
            @elseif(count($results) === 0 && strlen($query) > 0)
                <!-- No Results -->
                <div class="px-4 py-5 text-gray-500 text-center">
                    No results found for "<strong>{{ $query }}</strong>"
                </div>
            @else
                <!-- Results List -->
                <div class="py-2">
                    @php
                        $applicants = collect($results)->where('type', 'Applicant');
                        $vehicles = collect($results)->where('type', 'Vehicle');
                    @endphp

                    @if($applicants->count() > 0)
                        <div class="pt-2">
                            <div class="px-4 pb-2 text-[11px] tracking-wide uppercase text-gray-400">Applicants</div>
                            <div class="max-h-[45vh] overflow-y-auto">
                                @foreach($applicants as $index => $item)
                                    <a href="{{ $item['url'] }}"
                                       wire:key="result-{{ $item['type'] }}-{{ $item['id'] }}"
                                       class="group flex items-start gap-3 px-4 py-3 hover:bg-green-50 focus:bg-green-50 outline-none cursor-pointer border-b last:border-b-0 border-gray-50 {{ $activeIndex === $loop->index ? 'bg-green-50' : '' }}">
                                        <div class="mt-0.5 w-5 h-5 flex items-center justify-center">
                                            <svg class="h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2">
                                                <div class="font-semibold truncate text-gray-800 group-hover:text-green-700">{{ $item['label'] }}</div>
                                                <span class="text-[10px] px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-100">Applicant</span>
                                            </div>
                                            @if($item['sublabel'])
                                                <div class="text-xs text-gray-500 truncate mt-0.5">{{ $item['sublabel'] }}</div>
                                            @endif
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($vehicles->count() > 0)
                        <div class="pt-2">
                            <div class="px-4 pb-2 text-[11px] tracking-wide uppercase text-gray-400">Vehicles</div>
                            <div class="max-h-[45vh] overflow-y-auto">
                                @foreach($vehicles as $index => $item)
                                    <a href="{{ $item['url'] }}"
                                       wire:key="result-{{ $item['type'] }}-{{ $item['id'] }}"
                                       class="group flex items-start gap-3 px-4 py-3 hover:bg-green-50 focus:bg-green-50 outline-none cursor-pointer border-b last:border-b-0 border-gray-50 {{ $activeIndex === ($applicants->count() + $loop->index) ? 'bg-green-50' : '' }}">
                                        <div class="mt-0.5 w-5 h-5 flex items-center justify-center">
                                            <svg class="h-5 w-5 text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <div class="font-semibold truncate text-gray-800 group-hover:text-green-700">{{ $item['label'] }}</div>
                                                <span class="text-[10px] px-2 py-0.5 rounded-full bg-yellow-50 text-yellow-700 border border-yellow-100">Vehicle</span>
                                                @if(!empty($item['gatePass']))
                                                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-green-50 text-green-700 border border-green-100">
                                                        GP: {{ $item['gatePass'] }}
                                                    </span>
                                                @endif
                                            </div>
                                            @if($item['sublabel'])
                                                <div class="text-xs text-gray-500 truncate mt-0.5">{{ $item['sublabel'] }}</div>
                                            @endif
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Show All Results Button -->
                    @if(count($results) > 0)
                        <div class="px-3 pt-2 pb-2 border-t border-gray-100 mt-2">
                            <a href="{{ route('admin.search.results', ['q' => $query]) }}"
                               class="block w-full text-center px-4 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white font-medium transition">
                                View all {{ count($results) }}+ results
                            </a>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    @endif
</div>
