{{-- resources/views/components/search-filter.blade.php --}}
@props([
'title' => 'Filter and Search',
'searchPlaceholder' => 'Search...',
'filterOptions' => [
    'all' => 'All Statuses',
    'pending' => 'Pending',
    'approved' => 'Approved',
    'rejected' => 'Rejected',
    'active' => 'Active',     // Added for admin status
    'inactive' => 'Inactive'  // Added for admin status
],
'sortOptions' => [
    'newest' => 'Newest First',
    'oldest' => 'Oldest First',
    'a-z' => 'A-Z',
    'z-a' => 'Z-A'
],
'typeOptions' => [
    'applicant' => [
        'resident' => 'Resident',
        'employee' => 'Employee',
        'visitor' => 'Visitor'
    ],
    'vehicle' => [
        'motorcycle' => 'Motorcycle',
        'car' => 'Car',
        'truck' => 'Truck'
    ],
    'gatePass' => [
        'gate_pass' => 'Gate Pass',
        'gate_pass_tag' => 'Gate Pass Tag',
        'gate_pass_reader' => 'Gate Pass Reader',
    ],
    'admins' => [    // Added admin type options
        'super_admin' => 'Super Admin',
        'admin' => 'Admin',
        'moderator' => 'Moderator'
    ]
],
'roleOptions' => [    // New property for admin roles
    'all' => 'All Roles',
    'super_admin' => 'Super Admin',
    'admin' => 'Admin',
    'moderator' => 'Moderator'
],
'showType' => 'applicant',
'showReset' => true,
'showDateRange' => true,
'showTypeFilter' => true,
'showStatusFilter' => true,
'showRoleFilter' => false,  // New property to control role filter visibility
'showSortBy' => true,
'formAction' => null,
'formMethod' => 'GET'
])
<style>
    @media (max-width: 640px) {
    #filterDropdown {
        right: -30px; /* Adjust to center better on mobile */
        max-height: 80vh;
        overflow-y: auto;
    }
    
    .type-dropdown {
        max-height: 150px;
    }
    
    /* Make form elements easier to tap */
    select, input, button, .type-dropdown label {
        min-height: 44px;
    }
}
/* Fix dropdown positioning when it would overflow the viewport */
@media (max-width: 400px) {
    #filterDropdown {
        right: -50px;
    }
}
</style>
<div
    x-data="{
        activeFilterType: '{{ $showType }}',
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
        <form class="flex flex-col md:flex-row md:items-center md:justify-between gap-4" action="{{ $formAction }}" method="{{ $formMethod }}">
            @csrf
            <x-dashboard.search-input
                :placeholder="$searchPlaceholder"
                name="search"
                id="searchInput"
                class="w-full md:w-auto"
                clearButtonId="clearSearchBtn" />
                
            <!-- Filter Buttons -->
            <div class="flex flex-wrap items-center gap-3">
                @if($showReset)
                <!-- Reset Button -->
                <x-dashboard.reset-button />
                @endif
                
                <!-- Filter Dropdown -->
                <div class="relative">
                    <button
                        id="filterBtn"
                        type="button"
                        class="h-11 px-4 flex items-center gap-2 border border-gray-200 hover:border-green-300 hover:bg-green-50 rounded-lg transition-all duration-300 hover:shadow-md">
                        <i class="fas fa-filter h-4 w-4 text-gray-600"></i>
                        <h3 class="text-sm font-medium text-gray-700">Filters</h3>
                        <span
                            id="filterCount"
                            class="h-5 px-2 bg-green-100 text-green-700 rounded-full text-xs  items-center justify-center min-w-[20px] transition-all duration-200 ">
                            <!-- class="h-5 px-2 bg-green-100 text-green-700 rounded-full text-xs flex items-center justify-center min-w-[20px] transition-all duration-200 hidden"> -->

                            0
                        </span>
                        <i class="fas fa-chevron-down text-gray-400 ml-1 transition-transform duration-200"></i>
                    </button>
                    
                    <!-- Filter Dropdown Content -->
                    <div
                        id="filterDropdown"
                        class="absolute right-0 mt-2 w-[300px] max-w-[95vw] p-4 sm:p-5 bg-white border border-gray-100 rounded-xl shadow-lg z-10 hidden transition-all duration-300 ease-in-out transform origin-top-right opacity-0 scale-95">
                        <div class="space-y-5">
                            <!-- Filter Options -->
                            <div class="space-y-4">
                                @if($showStatusFilter)
                                <!-- Status Filter -->
                                <div class="space-y-2" x-show="activeFilterType !== 'admins'">
                                    <label class="text-xs font-medium text-gray-600">Status</label>
                                    <select
                                        id="statusFilter"
                                        name="status"
                                        class="w-full rounded-lg border px-3 py-2 text-sm shadow-sm transition-all duration-300 bg-gray-50 border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/50 focus:shadow-md">
                                        @foreach($filterOptions as $value => $label)
                                            @if($value !== 'active' && $value !== 'inactive')
                                            <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Admin Status Filter - Only show for admin type -->
                                <div class="space-y-2" x-show="activeFilterType === 'admins'">
                                    <label class="text-xs font-medium text-gray-600">Admin Status</label>
                                    <select
                                        id="adminStatusFilter"
                                        name="admin_status"
                                        class="w-full rounded-lg border px-3 py-2 text-sm shadow-sm transition-all duration-300 bg-gray-50 border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/50 focus:shadow-md">
                                        <option value="all">All Statuses</option>
                                        <option value="active" {{ request('admin_status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ request('admin_status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                                @endif

                           <!-- Replace the current Admin Role Filter select element with this: -->
                            <div class="space-y-2" x-show="activeFilterType === 'admins'">
                                <label class="text-xs font-medium text-gray-600">Admin Role</label>
                                <div class="relative">
                                    <x-dashboard.search-filter-dropdown id="role" :option="$roleOptions" :skipKeys="['all']"/>
                                </div>
                            </div>
                                                    
                                @if($showTypeFilter)
                                <!-- Dynamic Type Filter -->
                                <div class="space-y-2" x-show="activeFilterType !== 'admins'">
                                    <label class="text-xs font-medium text-gray-600" x-text="
                                        activeFilterType === 'applicant' ? 'Applicant Type' : 
                                        activeFilterType === 'vehicle' ? 'Vehicle Type' : 
                                        'Gate Pass Type'
                                    "></label>
                                    
                                    <!-- Applicant Type Dropdown - Only shown for applicant tab -->
                                    <div x-show="activeFilterType === 'applicant'" class="relative">
                                        <x-dashboard.search-filter-dropdown id="applicant" :option="$typeOptions" />
                                    </div>
                                    
                                    <!-- Vehicle Type Dropdown - Only shown for vehicles tab -->
                                    <div x-show="activeFilterType === 'vehicle'" class="relative">
                                        <x-dashboard.search-filter-dropdown id="vehicle" :option="$typeOptions" />
                                    </div>
                                    
                                    <!-- RFID Type Dropdown - Only shown for RFID tab -->
                                    <div x-show="activeFilterType === 'gatePass'" class="relative">
                                        <x-dashboard.search-filter-dropdown id="gatePass" :option="$typeOptions" />
                                    </div>

                                    <!-- Admin Type Dropdown - Only shown for Admin tab -->
                                    <div x-show="activeFilterType === 'admins'" class="relative">
                                       <x-dashboard.search-filter-dropdown id="admins" :option="$typeOptions" />
                                    </div>
                                </div>
                                @endif
                                
                                @if($showDateRange)
                                <!-- Date Range Filter -->
                                <div class="space-y-2">
                                    <label class="text-xs font-medium text-gray-600">Date Range</label>
                                    <div class="relative">
                                        <input
                                            type="text"
                                            id="dateRangePicker"
                                            name="date_range"
                                            class="w-full rounded-lg border px-3 py-2 pl-9 text-sm shadow-sm transition-all duration-300 bg-gray-50 border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/50 focus:shadow-md"
                                            value="{{ request('date_range') }}" 
                                            placeholder="Select date range..." />
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-calendar-alt text-gray-400"></i>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                
                                @if($showSortBy)
                                <!-- Sort By Filter -->
                                <div class="space-y-2">
                                    <label class="text-xs font-medium text-gray-600">Sort By</label>
                                    <div class="relative">
                                        <select
                                            id="sortByFilter"
                                            name="sort_by"
                                            class="w-full rounded-lg border px-3 py-2 pl-9 text-sm shadow-sm transition-all duration-300 bg-gray-50 border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/50 focus:shadow-md appearance-none">
                                            @foreach($sortOptions as $value => $label)
                                            <option value="{{ $value }}" {{ request('sort_by') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-sort text-gray-400"></i>
                                        </div>
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <i class="fas fa-chevron-down text-gray-400"></i>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                
                                <!-- Clear Filters Button -->
                                <div class="text-right mt-4" id="clearFiltersContainer" style="display: none;">
                                    <button type="button" id="clearAllFiltersBtn" class="text-xs text-green-600 hover:text-green-700 transition-colors">
                                        Clear All Filters
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Search Button -->
                <x-dashboard.buttons type="search" icon="true" text="Search" class="min-w-[100px] h-11"/>
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
@once
@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-5px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out forwards;
    }
    .type-dropdown {
        max-height: 200px;
        overflow-y: auto;
    }
</style>
@endpush
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="{{ asset('js/components/search-filter.js') }}"></script>
@endpush
@endonce