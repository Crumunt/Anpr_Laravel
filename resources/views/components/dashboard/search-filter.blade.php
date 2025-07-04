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
'applicants' => [
'resident' => 'Resident',
'employee' => 'Employee',
'visitor' => 'Visitor'
],
'vehicles' => [
'motorcycle' => 'Motorcycle',
'car' => 'Car',
'truck' => 'Truck'
],
'rfid' => [
'rfid' => 'RFID',
'rfid_tag' => 'RFID Tag',
'rfid_reader' => 'RFID Reader',
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
'showType' => 'applicants',
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
            if (tab === 'Applicants') this.activeFilterType = 'applicants';
            else if (tab === 'Registered Vehicles') this.activeFilterType = 'vehicles';
            else if (tab === 'RFID Management') this.activeFilterType = 'rfid';
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
        <button
            id="roleFilterBtn"
            type="button"
            class="w-full flex justify-between items-center rounded-lg border px-3 py-2 text-sm bg-gray-50 border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/50 hover:bg-green-50 transition-all duration-200">
            <span class="selected-types-label">Select Roles</span>
            <i class="fas fa-chevron-down text-gray-400 transition-transform duration-200"></i>
        </button>
        <div
            id="roleFilterDropdown"
            class="type-dropdown absolute left-0 mt-2 w-full p-3 bg-white border border-gray-100 rounded-lg shadow-lg z-10 hidden transition-all duration-200 opacity-0 transform scale-95">
            <div class="mb-2 pb-2 border-b border-gray-100">
                <label class="flex items-center gap-2 text-sm text-green-600 font-medium">
                    <input type="checkbox" class="select-all-types form-checkbox text-green-500 rounded">
                    Select All
                </label>
            </div>
            @foreach($roleOptions as $value => $label)
            @if($value !== 'all') {{-- Skip the "All Roles" option for checkboxes --}}
            <label class="flex items-center gap-2 mb-2 hover:bg-green-50 p-1 rounded transition-colors">
                <input
                    type="checkbox"
                    name="role_types[]"
                    value="{{ $value }}"
                    class="typeCheckbox roleTypeCheckbox rounded text-green-500"
                    {{ in_array($value, request('role_types', [])) ? 'checked' : '' }}>
                {{ $label }}
            </label>
            @endif
            @endforeach
        </div>
    </div>
</div>
                                
                                @if($showTypeFilter)
                                <!-- Dynamic Type Filter -->
                                <div class="space-y-2" x-show="activeFilterType !== 'admins'">
                                    <label class="text-xs font-medium text-gray-600" x-text="
                                        activeFilterType === 'applicants' ? 'Applicant Type' : 
                                        activeFilterType === 'vehicles' ? 'Vehicle Type' : 
                                        'RFID Type'
                                    "></label>
                                    
                                    <!-- Applicant Type Dropdown - Only shown for applicant tab -->
                                    <div x-show="activeFilterType === 'applicants'" class="relative">
                                        <button
                                            id="applicantTypeFilterBtn"
                                            type="button"
                                            class="w-full flex justify-between items-center rounded-lg border px-3 py-2 text-sm bg-gray-50 border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/50 hover:bg-green-50 transition-all duration-200">
                                            <span class="selected-types-label">Select Types</span>
                                            <i class="fas fa-chevron-down text-gray-400 transition-transform duration-200"></i>
                                        </button>
                                        <div
                                            id="applicantTypeFilterDropdown"
                                            class="type-dropdown absolute left-0 mt-2 w-full p-3 bg-white border border-gray-100 rounded-lg shadow-lg z-10 hidden transition-all duration-200 opacity-0 transform scale-95">
                                            <div class="mb-2 pb-2 border-b border-gray-100">
                                                <label class="flex items-center gap-2 text-sm text-green-600 font-medium">
                                                    <input type="checkbox" class="select-all-types form-checkbox text-green-500 rounded">
                                                    Select All
                                                </label>
                                            </div>
                                            @foreach($typeOptions['applicants'] as $value => $label)
                                            <label class="flex items-center gap-2 mb-2 hover:bg-green-50 p-1 rounded transition-colors">
                                                <input
                                                    type="checkbox"
                                                    name="applicant_types[]"
                                                    value="{{ $value }}"
                                                    class="typeCheckbox applicantTypeCheckbox rounded text-green-500"
                                                    {{ in_array($value, request('applicant_types', [])) ? 'checked' : '' }}>
                                                {{ $label }}
                                            </label>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    <!-- Vehicle Type Dropdown - Only shown for vehicles tab -->
                                    <div x-show="activeFilterType === 'vehicles'" class="relative">
                                        <button
                                            id="vehicleTypeFilterBtn"
                                            type="button"
                                            class="w-full flex justify-between items-center rounded-lg border px-3 py-2 text-sm bg-gray-50 border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/50 hover:bg-green-50 transition-all duration-200">
                                            <span class="selected-types-label">Select Types</span>
                                            <i class="fas fa-chevron-down text-gray-400 transition-transform duration-200"></i>
                                        </button>
                                        <div
                                            id="vehicleTypeFilterDropdown"
                                            class="type-dropdown absolute left-0 mt-2 w-full p-3 bg-white border border-gray-100 rounded-lg shadow-lg z-10 hidden transition-all duration-200 opacity-0 transform scale-95">
                                            <div class="mb-2 pb-2 border-b border-gray-100">
                                                <label class="flex items-center gap-2 text-sm text-green-600 font-medium">
                                                    <input type="checkbox" class="select-all-types form-checkbox text-green-500 rounded">
                                                    Select All
                                                </label>
                                            </div>
                                            @foreach($typeOptions['vehicles'] as $value => $label)
                                            <label class="flex items-center gap-2 mb-2 hover:bg-green-50 p-1 rounded transition-colors">
                                                <input
                                                    type="checkbox"
                                                    name="vehicle_types[]"
                                                    value="{{ $value }}"
                                                    class="typeCheckbox vehicleTypeCheckbox rounded text-green-500"
                                                    {{ in_array($value, request('vehicle_types', [])) ? 'checked' : '' }}>
                                                {{ $label }}
                                            </label>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    <!-- RFID Type Dropdown - Only shown for RFID tab -->
                                    <div x-show="activeFilterType === 'rfid'" class="relative">
                                        <button
                                            id="rfidTypeFilterBtn"
                                            type="button"
                                            class="w-full flex justify-between items-center rounded-lg border px-3 py-2 text-sm bg-gray-50 border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/50 hover:bg-green-50 transition-all duration-200">
                                            <span class="selected-types-label">Select Types</span>
                                            <i class="fas fa-chevron-down text-gray-400 transition-transform duration-200"></i>
                                        </button>
                                        <div
                                            id="rfidTypeFilterDropdown"
                                            class="type-dropdown absolute left-0 mt-2 w-full p-3 bg-white border border-gray-100 rounded-lg shadow-lg z-10 hidden transition-all duration-200 opacity-0 transform scale-95">
                                            <div class="mb-2 pb-2 border-b border-gray-100">
                                                <label class="flex items-center gap-2 text-sm text-green-600 font-medium">
                                                    <input type="checkbox" class="select-all-types form-checkbox text-green-500 rounded">
                                                    Select All
                                                </label>
                                            </div>
                                            @foreach($typeOptions['rfid'] as $value => $label)
                                            <label class="flex items-center gap-2 mb-2 hover:bg-green-50 p-1 rounded transition-colors">
                                                <input
                                                    type="checkbox"
                                                    name="rfid_types[]"
                                                    value="{{ $value }}"
                                                    class="typeCheckbox rfidTypeCheckbox rounded text-green-500"
                                                    {{ in_array($value, request('rfid_types', [])) ? 'checked' : '' }}>
                                                {{ $label }}
                                            </label>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Admin Type Dropdown - Only shown for Admin tab -->
                                    <div x-show="activeFilterType === 'admins'" class="relative">
                                        <button
                                            id="adminTypeFilterBtn"
                                            type="button"
                                            class="w-full flex justify-between items-center rounded-lg border px-3 py-2 text-sm bg-gray-50 border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/50 hover:bg-green-50 transition-all duration-200">
                                            <span class="selected-types-label">Select Admin Types</span>
                                            <i class="fas fa-chevron-down text-gray-400 transition-transform duration-200"></i>
                                        </button>
                                        <div
                                            id="adminTypeFilterDropdown"
                                            class="type-dropdown absolute left-0 mt-2 w-full p-3 bg-white border border-gray-100 rounded-lg shadow-lg z-10 hidden transition-all duration-200 opacity-0 transform scale-95">
                                            <div class="mb-2 pb-2 border-b border-gray-100">
                                                <label class="flex items-center gap-2 text-sm text-green-600 font-medium">
                                                    <input type="checkbox" class="select-all-types form-checkbox text-green-500 rounded">
                                                    Select All
                                                </label>
                                            </div>
                                            @foreach($typeOptions['admins'] as $value => $label)
                                            <label class="flex items-center gap-2 mb-2 hover:bg-green-50 p-1 rounded transition-colors">
                                                <input
                                                    type="checkbox"
                                                    name="admin_types[]"
                                                    value="{{ $value }}"
                                                    class="typeCheckbox adminTypeCheckbox rounded text-green-500"
                                                    {{ in_array($value, request('admin_types', [])) ? 'checked' : '' }}>
                                                {{ $label }}
                                            </label>
                                            @endforeach
                                        </div>
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
                <x-dashboard.buttons type="search" icon="true" text="Hanapin mo" class="min-w-[100px] h-11"/>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Flatpickr for date range picker with improved UI
        flatpickr("#dateRangePicker", {
            mode: "range",
            dateFormat: "Y-m-d",
            showMonths: 1,
            animate: true,
            disableMobile: true,
            onChange: function(selectedDates, dateStr, instance) {
                updateFilters();
            }
        });
        
        
        // DOM Elements
        const searchInput = document.getElementById('searchInput');
        const clearSearchBtn = document.getElementById('clearSearchBtn');
        const resetBtn = document.getElementById('resetBtn');
        const filterBtn = document.getElementById('filterBtn');
        const filterDropdown = document.getElementById('filterDropdown');
        const filterCount = document.getElementById('filterCount');
        const activeFilters = document.getElementById('activeFilters');
        const statusFilter = document.getElementById('statusFilter');
        const adminStatusFilter = document.getElementById('adminStatusFilter');
        // const roleFilter = document.getElementById('roleFilter'); // We're replacing this with dropdown
        const sortByFilter = document.getElementById('sortByFilter');
        const dateRangePicker = document.getElementById('dateRangePicker');
        const clearAllFiltersBtn = document.getElementById('clearAllFiltersBtn');
        const clearFiltersContainer = document.getElementById('clearFiltersContainer');
        
        // Type filter buttons
        const applicantTypeFilterBtn = document.getElementById('applicantTypeFilterBtn');
        const vehicleTypeFilterBtn = document.getElementById('vehicleTypeFilterBtn');
        const rfidTypeFilterBtn = document.getElementById('rfidTypeFilterBtn');
        const adminTypeFilterBtn = document.getElementById('adminTypeFilterBtn');
        const roleFilterBtn = document.getElementById('roleFilterBtn');  // Add role filter button
        
        // Type filter dropdowns
        const applicantTypeFilterDropdown = document.getElementById('applicantTypeFilterDropdown');
        const vehicleTypeFilterDropdown = document.getElementById('vehicleTypeFilterDropdown');
        const rfidTypeFilterDropdown = document.getElementById('rfidTypeFilterDropdown');
        const adminTypeFilterDropdown = document.getElementById('adminTypeFilterDropdown');
        const roleFilterDropdown = document.getElementById('roleFilterDropdown');  // Add role filter dropdown
        
        // All type filter buttons and dropdowns
        const typeFilterBtns = [applicantTypeFilterBtn, vehicleTypeFilterBtn, rfidTypeFilterBtn, adminTypeFilterBtn, roleFilterBtn];
        const typeFilterDropdowns = [applicantTypeFilterDropdown, vehicleTypeFilterDropdown, rfidTypeFilterDropdown, adminTypeFilterDropdown, roleFilterDropdown];
        
        let activeFiltersList = [];
        
        // Initialize active filters on page load
        initializeActiveFilters();
        
        // Toggle dropdown with smooth animation
        function toggleDropdown(dropdown, button, show = null) {
            if (!dropdown || !button) return;
            
            const isShowing = show !== null ? show : dropdown.classList.contains('hidden');
            const chevron = button.querySelector('.fa-chevron-down');
            
            if (isShowing) {
                // Show dropdown
                dropdown.classList.remove('hidden');
                setTimeout(() => {
                    dropdown.classList.remove('opacity-0', 'scale-95');
                    dropdown.classList.add('opacity-100', 'scale-100');
                    if (chevron) chevron.classList.add('rotate-180');
                }, 10);
            } else {
                // Hide dropdown
                dropdown.classList.remove('opacity-100', 'scale-100');
                dropdown.classList.add('opacity-0', 'scale-95');
                if (chevron) chevron.classList.remove('rotate-180');
                
                setTimeout(() => {
                    dropdown.classList.add('hidden');
                }, 200);
            }
        }
        
        // Toggle filter dropdown
        if (filterBtn) {
            filterBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleDropdown(filterDropdown, filterBtn);
            });
        }
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (filterDropdown && !filterDropdown.contains(e.target) && !filterBtn.contains(e.target)) {
                toggleDropdown(filterDropdown, filterBtn, false);
            }
        });
        
        // Set up type filter button toggles
        typeFilterBtns.forEach((btn, index) => {
            if (btn) {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const dropdown = typeFilterDropdowns[index];
                    
                    // Close all other type dropdowns first
                    typeFilterDropdowns.forEach((d, i) => {
                        if (d && d !== dropdown) {
                            toggleDropdown(d, typeFilterBtns[i], false);
                        }
                    });
                    
                    // Toggle current dropdown
                    if (dropdown) {
                        toggleDropdown(dropdown, btn);
                    }
                });
            }
        });
        
        // Close type dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            typeFilterDropdowns.forEach((dropdown, index) => {
                if (dropdown &&
                    !dropdown.contains(e.target) &&
                    !typeFilterBtns.some(btn => btn && btn.contains(e.target))) {
                    toggleDropdown(dropdown, typeFilterBtns[index], false);
                }
            });
        });
        
        // Add "Select All" functionality
        document.querySelectorAll('.select-all-types').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const container = this.closest('.type-dropdown');
                const checkboxes = container.querySelectorAll('.typeCheckbox');
                checkboxes.forEach(cb => cb.checked = this.checked);
                updateFilters();
                updateTypeSelectionLabel();
            });
        });
        
        // Update type selection label based on selected checkboxes
        function updateTypeSelectionLabel() {
            document.querySelectorAll('[id$="TypeFilterBtn"]').forEach(btn => {
                if (!btn) return;
                
                const type = btn.id.replace('TypeFilterBtn', '');
                const checkboxes = document.querySelectorAll(`.${type}TypeCheckbox:checked`);
                const label = btn.querySelector('.selected-types-label');
                
                if (checkboxes.length === 0) {
                    if (type === 'admin') {
                        label.textContent = 'Select Admin Types';
                    } else if (type === 'role') {
                        label.textContent = 'Select Roles';
                    } else {
                        label.textContent = 'Select Types';
                    }
                } else {
                    label.textContent = `${checkboxes.length} Selected`;
                }
            });
        }
        
        // Update filters when type checkboxes change
        document.querySelectorAll('.typeCheckbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateFilters();
                updateTypeSelectionLabel();
                
                // Update select all checkbox
                const container = this.closest('.type-dropdown');
                const selectAll = container.querySelector('.select-all-types');
                const checkboxes = container.querySelectorAll('.typeCheckbox');
                const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                const noneChecked = Array.from(checkboxes).every(cb => !cb.checked);
                
                selectAll.checked = allChecked;
                selectAll.indeterminate = !allChecked && !noneChecked;
            });
        });
        
        // Update filters when other filter inputs change
        [statusFilter, adminStatusFilter, sortByFilter, dateRangePicker].forEach(filter => {
            if (filter) {
                filter.addEventListener('change', updateFilters);
            }
        });
        
        // Reset all filters
        if (resetBtn) {
            resetBtn.addEventListener('click', function() {
                resetAllFilters();
            });
        }
        
        // Clear all filters
        if (clearAllFiltersBtn) {
            clearAllFiltersBtn.addEventListener('click', function() {
                resetAllFilters();
            });
        }
        
        function resetAllFilters() {
            activeFiltersList = [];
            if (statusFilter) statusFilter.value = 'all';
            if (adminStatusFilter) adminStatusFilter.value = 'all';
            
            // Reset all type checkboxes
            document.querySelectorAll('.typeCheckbox').forEach(checkbox => checkbox.checked = false);
            document.querySelectorAll('.select-all-types').forEach(checkbox => {
                checkbox.checked = false;
                checkbox.indeterminate = false;
            });
            
            if (sortByFilter) sortByFilter.value = 'newest';
            if (dateRangePicker) {
                dateRangePicker.value = '';
                dateRangePicker._flatpickr.clear();
            }
            if (searchInput) searchInput.value = '';
            if (clearSearchBtn) clearSearchBtn.classList.add('hidden');
            
            updateTypeSelectionLabel();
            updateActiveFilters();
            
            // Submit form to reset all filters
            const form = resetBtn ? resetBtn.closest('form') : null;
            if (form) {
                form.submit();
            }
        }
        
        // Search functionality with improved clear button
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearSearchBtn.classList.toggle('hidden', !this.value);
            });
            
            // Initialize on page load
            clearSearchBtn.classList.toggle('hidden', !searchInput.value);
        }
        
        if (clearSearchBtn) {
            clearSearchBtn.addEventListener('click', function() {
                searchInput.value = '';
                searchInput.focus();
                this.classList.add('hidden');
            });
        }
        
        // Initialize active filters based on URL parameters
        function initializeActiveFilters() {
            const urlParams = new URLSearchParams(window.location.search);
            activeFiltersList = [];
            
            // Status filter
            if (statusFilter && urlParams.has('status') && urlParams.get('status') !== 'all') {
                activeFiltersList.push({
                    type: 'status',
                    value: urlParams.get('status'),
                    label: statusFilter.options[statusFilter.selectedIndex].text
                });
            }
            
            // Admin status filter
            if (adminStatusFilter && urlParams.has('admin_status') && urlParams.get('admin_status') !== 'all') {
                activeFiltersList.push({
                    type: 'admin_status',
                    value: urlParams.get('admin_status'),
                    label: `Status: ${adminStatusFilter.options[adminStatusFilter.selectedIndex].text}`
                });
            }
            
            // Role type filters (using the new role_types[] parameter)
            if (urlParams.has('role_types[]')) {
                const types = urlParams.getAll('role_types[]');
                types.forEach(type => {
                    const checkbox = document.querySelector(`.roleTypeCheckbox[value="${type}"]`);
                    if (checkbox) {
                        checkbox.checked = true;
                        const label = checkbox.closest('label').textContent.trim();
                        activeFiltersList.push({
                            type: 'role_type',
                            value: type,
                            label: `Role: ${label}`
                        });
                    }
                });
            } else if (urlParams.has('role') && urlParams.get('role') !== 'all') {
                // For backward compatibility with the old role parameter
                // This can be removed once you've fully migrated to role_types[]
                const roleValue = urlParams.get('role');
                const roleCheckbox = document.querySelector(`.roleTypeCheckbox[value="${roleValue}"]`);
                if (roleCheckbox) {
                    roleCheckbox.checked = true;
                    const label = roleCheckbox.closest('label').textContent.trim();
                    activeFiltersList.push({
                        type: 'role_type',
                        value: roleValue,
                        label: `Role: ${label}`
                    });
                }
            }
            
            // Applicant type filters
            if (urlParams.has('applicant_types[]')) {
                const types = urlParams.getAll('applicant_types[]');
                types.forEach(type => {
                    const checkbox = document.querySelector(`.applicantTypeCheckbox[value="${type}"]`);
                    if (checkbox) {
                        checkbox.checked = true;
                        const label = checkbox.closest('label').textContent.trim();
                        activeFiltersList.push({
                            type: 'applicant_type',
                            value: type,
                            label: label
                        });
                    }
                });
            }
            
            // Vehicle type filters
            if (urlParams.has('vehicle_types[]')) {
                const types = urlParams.getAll('vehicle_types[]');
                types.forEach(type => {
                    const checkbox = document.querySelector(`.vehicleTypeCheckbox[value="${type}"]`);
                    if (checkbox) {
                        checkbox.checked = true;
                        const label = checkbox.closest('label').textContent.trim();
                        activeFiltersList.push({
                            type: 'vehicle_type',
                            value: type,
                            label: label
                        });
                    }
                });
            }
            
            // RFID type filters
            if (urlParams.has('rfid_types[]')) {
                const types = urlParams.getAll('rfid_types[]');
                types.forEach(type => {
                    const checkbox = document.querySelector(`.rfidTypeCheckbox[value="${type}"]`);
                    if (checkbox) {
                        checkbox.checked = true;
                        const label = checkbox.closest('label').textContent.trim();
                        activeFiltersList.push({
                            type: 'rfid_type',
                            value: type,
                            label: label
                        });
                    }
                });
            }
            
            // Admin type filters
            if (urlParams.has('admin_types[]')) {
                const types = urlParams.getAll('admin_types[]');
                types.forEach(type => {
                    const checkbox = document.querySelector(`.adminTypeCheckbox[value="${type}"]`);
                    if (checkbox) {
                        checkbox.checked = true;
                        const label = checkbox.closest('label').textContent.trim();
                        activeFiltersList.push({
                            type: 'admin_type',
                            value: type,
                            label: label
                        });
                    }
                });
            }
            
            // Date range filter
            if (urlParams.has('date_range') && urlParams.get('date_range')) {
                activeFiltersList.push({
                    type: 'dateRange',
                    value: urlParams.get('date_range'),
                    label: `Date: ${urlParams.get('date_range')}`
                });
            }
            
            // Sort filter
            if (sortByFilter && urlParams.has('sort_by') && urlParams.get('sort_by') !== 'newest') {
                const sortValue = urlParams.get('sort_by');
                const sortOption = sortByFilter.querySelector(`option[value="${sortValue}"]`);
                activeFiltersList.push({
                    type: 'sortBy',
                    value: sortValue,
                    label: `Sorted by: ${sortOption ? sortOption.textContent : sortValue}`
                });
            }
            
            updateTypeSelectionLabel();
            updateActiveFilters();
        }
        
        // Create a filter badge with improved styling and interaction
        function createFilterBadge(filter) {
            const badge = document.createElement('div');
            badge.className = 'px-3 py-1.5 bg-green-50 border border-green-100 rounded-full flex items-center gap-2 text-xs text-green-700 transition-all duration-200 hover:bg-green-100 group animate-fadeIn';
            badge.innerHTML = `
                ${filter.label}
                <button type="button" class="ml-1 opacity-70 group-hover:opacity-100 transition-opacity" data-filter-type="${filter.type}" data-filter-value="${filter.value}">
                    <i class="fas fa-times h-3 w-3 cursor-pointer text-green-600 hover:text-green-700"></i>
                </button>
            `;
            
            badge.querySelector('button').addEventListener('click', function() {
                const filterType = this.dataset.filterType;
                const filterValue = this.dataset.filterValue;
                
                // Handle different filter types
                if (filterType === 'applicant_type' || 
                    filterType === 'vehicle_type' || 
                    filterType === 'rfid_type' || 
                    filterType === 'admin_type' ||
                    filterType === 'role_type') {  // Add role_type
                    let selector;
                    if (filterType === 'applicant_type') selector = `.applicantTypeCheckbox[value="${filterValue}"]`;
                    else if (filterType === 'vehicle_type') selector = `.vehicleTypeCheckbox[value="${filterValue}"]`;
                    else if (filterType === 'rfid_type') selector = `.rfidTypeCheckbox[value="${filterValue}"]`;
                    else if (filterType === 'role_type') selector = `.roleTypeCheckbox[value="${filterValue}"]`;
                    else selector = `.adminTypeCheckbox[value="${filterValue}"]`;
                    
                    const checkbox = document.querySelector(selector);
                    if (checkbox) {
                        checkbox.checked = false;
                        // Also update the "Select All" checkbox
                        const container = checkbox.closest('.type-dropdown');
                        const selectAll = container.querySelector('.select-all-types');
                        const allCheckboxes = container.querySelectorAll('.typeCheckbox');
                        const noneChecked = Array.from(allCheckboxes).every(cb => !cb.checked);
                        
                        selectAll.checked = false;
                        selectAll.indeterminate = !noneChecked;
                    }
                } else {
                    if (filterType === 'status' && statusFilter) statusFilter.value = 'all';
                    if (filterType === 'admin_status' && adminStatusFilter) adminStatusFilter.value = 'all';
                    if (filterType === 'sortBy' && sortByFilter) sortByFilter.value = 'newest';
                    if (filterType === 'dateRange' && dateRangePicker) {
                        dateRangePicker.value = '';
                        dateRangePicker._flatpickr.clear();
                    }
                }
                
                activeFiltersList = activeFiltersList.filter(f => !(f.type === filterType && f.value === filterValue));
                updateTypeSelectionLabel();
                updateActiveFilters();
                
                // Submit form to apply filter change
                const form = activeFilters.closest('form');
                if (form) {
                    form.submit();
                }
            });
            
            return badge;
        }
        
        // Update active filters display with smoother animations
        function updateActiveFilters() {
            if (filterCount) {
                filterCount.textContent = activeFiltersList.length;
                filterCount.classList.toggle('hidden', activeFiltersList.length === 0);
            }
            
            if (activeFilters) {
                // Clear existing filters with a fade-out effect
                const existingBadges = activeFilters.querySelectorAll('div');
                if (existingBadges.length > 0) {
                    existingBadges.forEach(badge => {
                        badge.style.opacity = '0';
                        badge.style.transform = 'translateY(-5px)';
                    });
                    
                    setTimeout(() => {
                        activeFilters.innerHTML = '';
                        renderFilterBadges();
                    }, 200);
                } else {
                    renderFilterBadges();
                }
            }
            
            // Show/hide clear filters button
            if (clearFiltersContainer) {
                clearFiltersContainer.style.display = activeFiltersList.length > 0 ? 'block' : 'none';
            }
        }
        
        function renderFilterBadges() {
            if (activeFiltersList.length > 0) {
                activeFiltersList.forEach((filter, index) => {
                    const badge = createFilterBadge(filter);
                    badge.style.animationDelay = `${index * 50}ms`;
                    activeFilters.appendChild(badge);
                });
            }
        }
        
        // Update filters with optimized performance
        function updateFilters() {
            activeFiltersList = [];
            
            // Status filter
            if (statusFilter && statusFilter.value !== 'all') {
                activeFiltersList.push({
                    type: 'status',
                    value: statusFilter.value,
                    label: statusFilter.options[statusFilter.selectedIndex].text
                });
            }
            
            // Admin status filter
            if (adminStatusFilter && adminStatusFilter.value !== 'all') {
                activeFiltersList.push({
                    type: 'admin_status',
                    value: adminStatusFilter.value,
                    label: `Status: ${adminStatusFilter.options[adminStatusFilter.selectedIndex].text}`
                });
            }
            
            // Applicant type checkboxes
            document.querySelectorAll('.applicantTypeCheckbox:checked').forEach(checkbox => {
                activeFiltersList.push({
                    type: 'applicant_type',
                    value: checkbox.value,
                    label: checkbox.closest('label').textContent.trim()
                });
            });
            
            // Vehicle type checkboxes
            document.querySelectorAll('.vehicleTypeCheckbox:checked').forEach(checkbox => {
                activeFiltersList.push({
                    type: 'vehicle_type',
                    value: checkbox.value,
                    label: checkbox.closest('label').textContent.trim()
                });
            });
            
            // RFID type checkboxes
            document.querySelectorAll('.rfidTypeCheckbox:checked').forEach(checkbox => {
                activeFiltersList.push({
                    type: 'rfid_type',
                    value: checkbox.value,
                    label: checkbox.closest('label').textContent.trim()
                });
            });
            
            // Admin type checkboxes
            document.querySelectorAll('.adminTypeCheckbox:checked').forEach(checkbox => {
                activeFiltersList.push({
                    type: 'admin_type',
                    value: checkbox.value,
                    label: checkbox.closest('label').textContent.trim()
                });
            });
            
            // Role type checkboxes
            document.querySelectorAll('.roleTypeCheckbox:checked').forEach(checkbox => {
                activeFiltersList.push({
                    type: 'role_type',
                    value: checkbox.value,
                    label: `Role: ${checkbox.closest('label').textContent.trim()}`
                });
            });
            
            // Date range filter
            if (dateRangePicker && dateRangePicker.value) {
                activeFiltersList.push({
                    type: 'dateRange',
                    value: dateRangePicker.value,
                    label: `Date: ${dateRangePicker.value}`
                });
            }
            
            // Sort filter
            if (sortByFilter && sortByFilter.value !== 'newest') {
                activeFiltersList.push({
                    type: 'sortBy',
                    value: sortByFilter.value,
                    label: `Sorted by: ${sortByFilter.options[sortByFilter.selectedIndex].text}`
                });
            }
            
            updateActiveFilters();
        }
        
        // In your JavaScript section, ensure this part is working correctly
        document.addEventListener('tab-changed', function(e) {
            // Get the Alpine.js component instance
            const xData = document.querySelector('[x-data]').__x.$data;
            
            if (e.detail === 'Applicants') {
                xData.activeFilterType = 'applicants';
            } else if (e.detail === 'Registered Vehicles') {
                xData.activeFilterType = 'vehicles';
            } else if (e.detail === 'RFID Management') {
                xData.activeFilterType = 'rfid';
            } else if (e.detail === 'All Admins' || e.detail === 'Active' || e.detail === 'Inactive') {
                xData.activeFilterType = 'admins';
            }
            
            // Reinitialize type selection labels after tab change
            updateTypeSelectionLabel();
        });
        
        // Add keyboard navigation support
        typeFilterDropdowns.forEach(dropdown => {
            if (dropdown) {
                dropdown.querySelectorAll('label').forEach((label, i, labels) => {
                    label.setAttribute('tabindex', '0');
                    label.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter' || e.key === ' ') {
                            const checkbox = this.querySelector('input[type="checkbox"]');
                            checkbox.checked = !checkbox.checked;
                            checkbox.dispatchEvent(new Event('change'));
                            e.preventDefault();
                        } else if (e.key === 'ArrowDown' && i < labels.length - 1) {
                            labels[i + 1].focus();
                            e.preventDefault();
                        } else if (e.key === 'ArrowUp' && i > 0) {
                            labels[i - 1].focus();
                            e.preventDefault();
                        }
                    });
                });
            }
        });
    });
</script>
@endpush
@endonce