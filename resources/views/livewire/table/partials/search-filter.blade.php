<div
    x-data="{
        activeFilterType: '{{ $filterType }}',
        activeFiltersList: [],
        updateFilterType(tab) {
            if (tab === 'Applicant') this.activeFilterType = 'applicant';
            else if (tab === 'Registered Vehicles') this.activeFilterType = 'vehicle';
            else if (tab === 'Gate Pass Management') this.activeFilterType = 'gatePass';
            else if (tab === 'All Admins' || tab === 'Active' || tab === 'Inactive') this.activeFilterType = 'admins';
        }
    }"
    @tab-changed.window="updateFilterType($event.detail)"
    class="w-full bg-white border border-gray-100 rounded-xl shadow-sm transition-all duration-300 hover:shadow-md">

    <div class="p-5">
        <form class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            @csrf
            <x-dashboard.search-input
                placeholder="Search..."
                wire:model.live.debounce.200="search"
                name="search"
                id="table"
                class="w-full md:w-auto"
                clearButtonId="clearSearchBtn" />

            <!-- Filter Buttons -->
            <div class="flex flex-wrap items-center gap-3">
                @if($showReset)
                    <!-- Reset Button -->
                    <x-dashboard.reset-button wire:click="resetFilters" />
                @endif

                <!-- Filter Dropdown -->
                <div class="relative" x-data="{isOpen: false}">
                    <button
                        @click="isOpen = !isOpen"
                        id="filterBtn"
                        type="button"
                        class="h-11 px-4 flex items-center gap-2 border border-gray-200 hover:border-green-300 hover:bg-green-50 rounded-lg transition-all duration-300 hover:shadow-md">
                        <i class="fas fa-filter h-4 w-4 text-gray-600"></i>
                        <h3 class="text-sm font-medium text-gray-700">Filters</h3>
                        <span
                            id="filterCount"
                            class="h-5 px-2 bg-green-100 text-green-700 rounded-full text-xs  items-center justify-center min-w-[20px] transition-all duration-200 ">
                            <!-- class="h-5 px-2 bg-green-100 text-green-700 rounded-full text-xs flex items-center justify-center min-w-[20px] transition-all duration-200 hidden"> -->

                            {{ $activeCount }}
                        </span>
                        <i class="fas fa-chevron-down text-gray-400 ml-1 transition-transform duration-200" :class="{'rotate-180' : isOpen}"></i>
                    </button>

                    <!-- Filter Dropdown Content -->
                    <div
                        x-show="isOpen"
                        x-cloak
                        @click.away="isOpen = false"
                        id="filterDropdown"
                        class="absolute right-0 mt-2 w-[300px] max-w-[95vw] p-4 sm:p-5 bg-white border border-gray-100 rounded-xl shadow-lg z-50 origin-top-right">
                        <div class="space-y-5">
                            <!-- Filter Options -->
                            <div class="space-y-4">
                                @if($showStatusFilter)
                                <!-- Status Filter -->
                                <div x-data="{
                                        isOpen: false,
                                        localStatus: @entangle('selectedStatus'),
                                        statusMap: @js($statusFilter),
                                        getDisplayLabel() {
                                            return this.statusMap[this.localStatus] || 'Select Status';
                                        }
                                    }"
                                    class="relative">

                                    <label class="text-xs font-medium text-gray-600 block mb-1.5">Status</label>

                                    <!-- Dropdown Button -->
                                    <button
                                        @click="isOpen = !isOpen"
                                        type="button"
                                        class="w-full flex justify-between items-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm bg-white hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-500/50 transition-all duration-200">

                                        <!-- Icon and Selected Value -->
                                        <div class="flex items-center gap-2.5">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span class="font-medium text-gray-700" x-text="getDisplayLabel()"></span>
                                        </div>

                                        <!-- Chevron Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-4 w-4 text-gray-400 transition-transform duration-200"
                                             :class="{ 'rotate-180': isOpen }"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>

                                    <!-- Dropdown Menu -->
                                    <div
                                        x-show="isOpen"
                                        x-cloak
                                        @click.away="isOpen = false"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 -translate-y-2"
                                        x-transition:enter-end="opacity-100 translate-y-0"
                                        x-transition:leave="transition ease-in duration-150"
                                        x-transition:leave-start="opacity-100 translate-y-0"
                                        x-transition:leave-end="opacity-0 -translate-y-2"
                                        class="absolute left-0 right-0 mt-2 bg-white border border-gray-200 rounded-lg shadow-xl z-50 max-h-64 overflow-auto hide-scrollbar">

                                        <!-- Dropdown Header -->
                                        <div class="px-3 py-2 border-b border-gray-100 bg-gray-50">
                                            <p class="text-xs font-semibold text-gray-500 tracking-wider">Select Status</p>
                                        </div>

                                        <!-- Options List -->
                                        <div class="py-1">
                                            @foreach($statusFilter as $value => $status)
                                                <label class="flex items-center justify-between px-4 py-2.5 hover:bg-green-50 cursor-pointer transition-colors duration-150 group">

                                                    <!-- Radio Input and Label -->
                                                    <div class="flex items-center gap-3">
                                                        <div class="relative flex items-center">
                                                            <input
                                                                type="radio"
                                                                name="status"
                                                                x-model="localStatus"
                                                                value="{{ $value }}"
                                                                @change="$wire.set('selectedStatus', '{{ $value }}'); isOpen = false"
                                                                class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500 cursor-pointer accent-green-600">
                                                        </div>
                                                        <span class="text-sm text-gray-700 group-hover:text-green-700 font-medium">{{ $status }}</span>
                                                    </div>

                                                    <!-- Check Icon for Selected Item -->
                                                    <svg x-show="localStatus === '{{ $value }}'"
                                                         xmlns="http://www.w3.org/2000/svg"
                                                         class="h-4 w-4 text-green-600"
                                                         fill="none"
                                                         viewBox="0 0 24 24"
                                                         stroke="currentColor"
                                                         stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <!-- Admin Status Filter - Only show for admin type -->
                                {{-- removed for now --}}
                                @endif

                           <!-- Replace the current Admin Role Filter select element with this: -->
                           {{-- removed for now --}}

                                @if($showTypeFilter)
                                <!-- Dynamic Type Filter -->
                                <div class="space-y-2" x-show="activeFilterType !== 'admins'">
                                    <label class="text-xs font-medium text-gray-600 block mb-1.5" x-text="
                                        activeFilterType === 'applicant' ? 'Applicant Type' :
                                        activeFilterType === 'vehicle' ? 'Vehicle Type' :
                                        'Gate Pass Type'
                                    "></label>

                                    <!-- Applicant Type Dropdown - Only shown for applicant tab -->
                                    <div x-data="{isOpen: false}" x-show="activeFilterType === 'applicant'" class="relative">

                                        <!-- Dropdown Button -->
                                        <button
                                            @click="isOpen = !isOpen"
                                            type="button"
                                            class="w-full flex justify-between items-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm bg-white hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-500/50 transition-all duration-200">

                                            <!-- Icon and Selected Text -->
                                            <div class="flex items-center gap-2.5">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                                <span class="font-medium text-gray-700 selected-types-label">
                                                    @if(count($selectedRole) > 0)
                                                        {{ count($selectedRole) }} {{ count($selectedRole) === 1 ? 'type' : 'types' }} selected
                                                    @else
                                                        Select Types
                                                    @endif
                                                </span>
                                            </div>

                                            <!-- Chevron Icon -->
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="h-4 w-4 text-gray-400 transition-transform duration-200"
                                                 :class="{ 'rotate-180': isOpen }"
                                                 fill="none"
                                                 viewBox="0 0 24 24"
                                                 stroke="currentColor"
                                                 stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>

                                        <!-- Dropdown Menu -->
                                        <div
                                            x-show="isOpen"
                                            x-cloak
                                            @click.away="isOpen = false"
                                            x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0 -translate-y-2"
                                            x-transition:enter-end="opacity-100 translate-y-0"
                                            x-transition:leave="transition ease-in duration-150"
                                            x-transition:leave-start="opacity-100 translate-y-0"
                                            x-transition:leave-end="opacity-0 -translate-y-2"
                                            class="absolute left-0 right-0 mt-2 bg-white border border-gray-200 rounded-lg shadow-xl z-50 max-h-64 overflow-auto hide-scrollbar">

                                            <!-- Dropdown Header with Select All -->
                                            @if(count($roleFilter) > 6)
                                            <div class="px-4 py-2.5 border-b border-gray-100 bg-gray-50">
                                                <label class="flex items-center gap-2.5 cursor-pointer group">
                                                    <input
                                                        wire:model.live="selectedAllTypes"
                                                        type="checkbox"
                                                        class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500 cursor-pointer">
                                                    <span class="text-sm font-semibold text-green-600 group-hover:text-green-700">Select All</span>
                                                </label>
                                            </div>
                                            @endif

                                            <!-- Options List -->
                                            <div class="py-1">
                                                @foreach($roleFilter as $key => $label)
                                                    <label wire:key="role-label-{{ $key }}" class="flex items-center justify-between px-4 py-2.5 hover:bg-green-50 cursor-pointer transition-colors duration-150 group">

                                                        <!-- Checkbox and Label -->
                                                        <div class="flex items-center gap-3">
                                                            <input
                                                                type="checkbox"
                                                                wire:click="toggleRow('{{$key}}')"
                                                                wire:key="role-checkbox-{{ $key }}-{{ in_array($key, $selectedRole) ? 'checked' : 'unchecked' }}"
                                                                @checked(in_array($key, $selectedRole))
                                                                class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500 cursor-pointer">
                                                            <span class="text-sm text-gray-700 group-hover:text-green-700 font-medium">{{ $label }}</span>
                                                        </div>

                                                        <!-- Check Icon for Selected Item -->
                                                        @if(in_array($key, $selectedRole))
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                             class="h-4 w-4 text-green-600"
                                                             fill="none"
                                                             viewBox="0 0 24 24"
                                                             stroke="currentColor"
                                                             stroke-width="2.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                        @endif
                                                    </label>
                                                @endforeach
                                            </div>

                                            <!-- Footer with Clear Selection (if items selected) -->
                                            @if(count($selectedRole) > 0)
                                            <div class="px-4 py-2.5 border-t border-gray-100 bg-gray-50">
                                                <button
                                                    wire:click="clearSelectedRoles"
                                                    type="button"
                                                    class="w-full text-sm font-medium text-red-600 hover:text-red-700 transition-colors duration-150">
                                                    Clear Selection
                                                </button>
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Vehicle Type Dropdown - Only shown for vehicles tab -->
                                    <div x-show="activeFilterType === 'vehicle'" class="relative">
                                        <x-dashboard.search-filter-dropdown id="vehicle" :option="$roleFilter" />
                                    </div>

                                    <!-- RFID Type Dropdown - Only shown for RFID tab -->
                                    <div x-show="activeFilterType === 'gatePass'" class="relative">
                                        <x-dashboard.search-filter-dropdown id="gatePass" :option="$roleFilter" />
                                    </div>

                                    <!-- Admin Type Dropdown - Only shown for Admin tab -->
                                    <div x-show="activeFilterType === 'admins'" class="relative">
                                       <x-dashboard.search-filter-dropdown id="admins" :option="$roleFilter" />
                                    </div>
                                </div>
                                @endif

                                @if($showDateRange)
                                <!-- Date Range Filter -->
                                <div class="space-y-2">
                                    <label for="dateRangePicker" class="text-xs font-medium text-gray-600 block">Date Range</label>
                                    <div class="relative">
                                        <input
                                            type="text"
                                            id="dateRangePicker"
                                            name="date_range"
                                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 pl-10 text-sm bg-white hover:border-gray-400 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-500/50 transition-all duration-200"
                                            value="{{ request('date_range') }}"
                                            placeholder="Select date range..." />
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if($showSortFilter)
                                <!-- Sort By Filter -->
                                <div x-data="{
                                    isOpen: false,
                                    localSort: @entangle('selectedSortOption'),
                                    statusMap: @js($sortFilter),
                                    getDisplayLabel() {
                                        return this.statusMap[this.localSort] || 'Newest First';
                                        }
                                    }"
                                    class="relative">
                                    <!-- Label -->
                                    <label class="text-xs font-medium text-gray-600 block mb-1.5">Sort By</label>

                                    <!-- Dropdown Button -->
                                    <button
                                        @click="isOpen = !isOpen"
                                        type="button"
                                        class="w-full flex justify-between items-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm bg-white hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-500/50 transition-all duration-200">

                                        <!-- Icon and Selected Value -->
                                        <div class="flex items-center gap-2.5">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4" />
                                            </svg>
                                            <span class="font-medium text-gray-700" x-text="getDisplayLabel"></span>
                                        </div>

                                        <!-- Chevron Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-4 w-4 text-gray-400 transition-transform duration-200"
                                             :class="{ 'rotate-180': isOpen }"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>

                                    <!-- Dropdown Menu -->
                                    <div
                                        x-show="isOpen"
                                        x-cloak
                                        @click.away="isOpen = false"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 -translate-y-2"
                                        x-transition:enter-end="opacity-100 translate-y-0"
                                        x-transition:leave="transition ease-in duration-150"
                                        x-transition:leave-start="opacity-100 translate-y-0"
                                        x-transition:leave-end="opacity-0 -translate-y-2"
                                        class="absolute left-0 right-0 mt-2 bg-white border border-gray-200 rounded-lg shadow-xl z-50 max-h-64 overflow-auto hide-scrollbar">

                                        <!-- Dropdown Header -->
                                        <div class="px-3 py-2 border-b border-gray-100 bg-gray-50">
                                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Select Sort Order</p>
                                        </div>

                                        <!-- Options List -->
                                        <div class="py-1">
                                            @foreach($sortFilter as $value => $option)
                                                <label class="flex items-center justify-between px-4 py-2.5 hover:bg-green-50 cursor-pointer transition-colors duration-150 group">

                                                    <!-- Radio Input and Label -->
                                                    <div class="flex items-center gap-3">
                                                        <input
                                                            type="radio"
                                                            name="sort_option"
                                                            x-model="localSort"
                                                            value="{{ $value }}"
                                                            @change="$wire.set('selectedSortOption', '{{ $value }}'); isOpen = false"
                                                            class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500 cursor-pointer">
                                                        <span class="text-sm text-gray-700 group-hover:text-green-700 font-medium">{{ $option }}</span>
                                                    </div>

                                                    <!-- Check Icon for Selected Item -->
                                                    <svg x-show="localSort === '{{ $value }}'"
                                                         xmlns="http://www.w3.org/2000/svg"
                                                         class="h-4 w-4 text-green-600"
                                                         fill="none"
                                                         viewBox="0 0 24 24"
                                                         stroke="currentColor"
                                                         stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>

        <!-- Active Filter Badges -->
        <div
            id="activeFilters"
            class="flex flex-wrap gap-2 mt-4 transition-all duration-200">
            {{-- Filter badges will be added here dynamically --}}
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@endpush

@script
<script>
    Livewire.on('log-action', (event) => {
        console.log('Livewire Log:', event);
    });
</script>
@endscript
@push('scripts')

@endpush
