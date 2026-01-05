@props([
    'title' => '',
    'subtitle' => '',
    'search_placeholder' => 'Search...',
    'show_entries' => true,
    'show_pagination' => true,
    'total_entries' => 0,
    'current_page' => 1,
    'per_page' => 10
])

<div class="glass-card overflow-hidden">
    <div class="bg-gradient-to-r from-gray-50 to-white px-4 md:px-6 py-4 border-b flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center">
            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center clsu-text mr-3">
                <i class="fas fa-history" aria-hidden="true"></i>
            </div>
            <div>
                <h2 class="font-semibold text-gray-800 text-lg">{{ $title }}</h2>
                <p class="text-xs text-gray-500">{{ $subtitle }}</p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <div class="relative">
                <input type="text" id="table-search" placeholder="{{ $search_placeholder }}" class="py-1.5 pl-8 pr-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500" aria-label="Search in table">
                <i class="fas fa-search absolute left-3 top-2 text-gray-400 text-xs" aria-hidden="true"></i>
            </div>
            <select id="entrance-filter" class="text-sm border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 py-1.5 pl-3 pr-8 bg-white" aria-label="Filter by entrance">
                <option>All Entrances</option>
                <option>Main Entrance</option>
                <option>Exit Gate</option>
                <option>Parking Area</option>
                <option>Back Gate</option>
            </select>
            <div class="relative inline-block">
                <button id="filter-dropdown-btn" class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm hover:bg-gray-50 flex items-center bg-white transition-colors" aria-expanded="false" aria-haspopup="true">
                    <i class="fas fa-filter mr-1.5" aria-hidden="true"></i> 
                    <span>Advanced Filter</span>
                    <span id="filter-badge" class="ml-1 bg-green-500 text-white text-xs rounded-full px-1.5 py-0.5 hidden">0</span>
                    <i class="fas fa-chevron-down ml-1 text-gray-400 text-xs" aria-hidden="true"></i>
                </button>
                <!-- Enhanced Advanced Filter dropdown -->
                <div id="filter-dropdown" class="absolute right-0 mt-1 w-80 bg-white rounded-lg shadow-xl hidden z-10 border border-gray-200" role="menu">
                    <div class="p-4 max-h-96 overflow-y-auto filter-dropdown-scroll">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-semibold text-gray-800 flex items-center">
                                <i class="fas fa-sliders-h mr-2 text-green-500" aria-hidden="true"></i>
                                Advanced Filters
                            </h4>
                            <button id="close-filter-btn" class="text-gray-400 hover:text-gray-600 p-1 rounded">
                                <i class="fas fa-times" aria-hidden="true"></i>
                            </button>
                        </div>
                        
                        <!-- Search within filters -->
                        <div class="mb-4">
                            <div class="relative">
                                <input type="text" id="filter-search" placeholder="Search filters..." class="w-full py-2 pl-8 pr-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500">
                                <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-xs" aria-hidden="true"></i>
                            </div>
                        </div>
                        
                        <!-- Date Range Filter -->
                        <div class="mb-6">
                            <h5 class="font-medium text-sm text-gray-700 mb-4 flex items-center">
                                <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-calendar-alt text-blue-600 text-sm" aria-hidden="true"></i>
                                </div>
                                Date Range
                            </h5>
                            <div class="grid grid-cols-2 gap-3 mb-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-2">From Date</label>
                                    <input type="date" id="date-from" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-2">To Date</label>
                                    <input type="date" id="date-to" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <button class="quick-date-btn text-xs px-3 py-2 bg-gray-100 hover:bg-blue-100 hover:text-blue-700 rounded-lg transition-all duration-200 font-medium" data-days="1">
                                    <i class="fas fa-sun mr-1" aria-hidden="true"></i>Today
                                </button>
                                <button class="quick-date-btn text-xs px-3 py-2 bg-gray-100 hover:bg-blue-100 hover:text-blue-700 rounded-lg transition-all duration-200 font-medium" data-days="7">
                                    <i class="fas fa-calendar-week mr-1" aria-hidden="true"></i>Last 7 days
                                </button>
                                <button class="quick-date-btn text-xs px-3 py-2 bg-gray-100 hover:bg-blue-100 hover:text-blue-700 rounded-lg transition-all duration-200 font-medium" data-days="30">
                                    <i class="fas fa-calendar-alt mr-1" aria-hidden="true"></i>Last 30 days
                                </button>
                            </div>
                        </div>
                        
                        <!-- Status Filters -->
                        <div class="mb-6">
                            <h5 class="font-medium text-sm text-gray-700 mb-4 flex items-center">
                                <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-shield-alt text-green-600 text-sm" aria-hidden="true"></i>
                                </div>
                                Vehicle Status
                            </h5>
                            <div class="space-y-3">
                                <label class="flex items-center p-3 rounded-lg hover:bg-green-50 cursor-pointer transition-all duration-200 border border-transparent hover:border-green-200">
                                    <input type="checkbox" class="rounded text-green-600 focus:ring-green-500 h-4 w-4" data-filter="status" data-value="Authorized">
                                    <div class="ml-3 flex-1">
                                        <span class="text-sm font-medium text-gray-700">Authorized</span>
                                        <p class="text-xs text-gray-500">Valid access granted</p>
                                    </div>
                                    <span class="bg-green-100 text-green-800 text-xs font-bold px-2.5 py-1 rounded-full">Valid</span>
                                </label>
                                <label class="flex items-center p-3 rounded-lg hover:bg-amber-50 cursor-pointer transition-all duration-200 border border-transparent hover:border-amber-200">
                                    <input type="checkbox" class="rounded text-amber-600 focus:ring-amber-500 h-4 w-4" data-filter="status" data-value="Pending">
                                    <div class="ml-3 flex-1">
                                        <span class="text-sm font-medium text-gray-700">Pending</span>
                                        <p class="text-xs text-gray-500">Awaiting approval</p>
                                    </div>
                                    <span class="bg-amber-100 text-amber-800 text-xs font-bold px-2.5 py-1 rounded-full">Review</span>
                                </label>
                                <label class="flex items-center p-3 rounded-lg hover:bg-red-50 cursor-pointer transition-all duration-200 border border-transparent hover:border-red-200">
                                    <input type="checkbox" class="rounded text-red-600 focus:ring-red-500 h-4 w-4" data-filter="status" data-value="Unauthorized">
                                    <div class="ml-3 flex-1">
                                        <span class="text-sm font-medium text-gray-700">Unauthorized</span>
                                        <p class="text-xs text-gray-500">Access denied</p>
                                    </div>
                                    <span class="bg-red-100 text-red-800 text-xs font-bold px-2.5 py-1 rounded-full">Blocked</span>
                                </label>
                                <label class="flex items-center p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-all duration-200 border border-transparent hover:border-gray-200">
                                    <input type="checkbox" class="rounded text-gray-600 focus:ring-gray-500 h-4 w-4" data-filter="status" data-value="Flagged">
                                    <div class="ml-3 flex-1">
                                        <span class="text-sm font-medium text-gray-700">Flagged</span>
                                        <p class="text-xs text-gray-500">Requires attention</p>
                                    </div>
                                    <span class="bg-gray-100 text-gray-800 text-xs font-bold px-2.5 py-1 rounded-full">Alert</span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Vehicle Type Filters -->
                        <div class="mb-6">
                            <h5 class="font-medium text-sm text-gray-700 mb-4 flex items-center">
                                <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-car text-blue-600 text-sm" aria-hidden="true"></i>
                                </div>
                                Vehicle Type
                            </h5>
                            <div class="space-y-3">
                                <label class="flex items-center p-3 rounded-lg hover:bg-green-50 cursor-pointer transition-all duration-200 border border-transparent hover:border-green-200">
                                    <input type="checkbox" class="rounded text-green-600 focus:ring-green-500 h-4 w-4" data-filter="type" data-value="Regular">
                                    <div class="ml-3 flex-1">
                                        <span class="text-sm font-medium text-gray-700">Regular</span>
                                        <p class="text-xs text-gray-500">Standard vehicle access</p>
                                    </div>
                                    <span class="bg-green-100 text-green-800 text-xs font-bold px-2.5 py-1 rounded-full">Standard</span>
                                </label>
                                <label class="flex items-center p-3 rounded-lg hover:bg-blue-50 cursor-pointer transition-all duration-200 border border-transparent hover:border-blue-200">
                                    <input type="checkbox" class="rounded text-blue-600 focus:ring-blue-500 h-4 w-4" data-filter="type" data-value="Visitor">
                                    <div class="ml-3 flex-1">
                                        <span class="text-sm font-medium text-gray-700">Visitor</span>
                                        <p class="text-xs text-gray-500">Temporary access</p>
                                    </div>
                                    <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-1 rounded-full">Temporary</span>
                                </label>
                                <label class="flex items-center p-3 rounded-lg hover:bg-purple-50 cursor-pointer transition-all duration-200 border border-transparent hover:border-purple-200">
                                    <input type="checkbox" class="rounded text-purple-600 focus:ring-purple-500 h-4 w-4" data-filter="type" data-value="Staff">
                                    <div class="ml-3 flex-1">
                                        <span class="text-sm font-medium text-gray-700">Staff</span>
                                        <p class="text-xs text-gray-500">Employee vehicle</p>
                                    </div>
                                    <span class="bg-purple-100 text-purple-800 text-xs font-bold px-2.5 py-1 rounded-full">Employee</span>
                                </label>
                                <label class="flex items-center p-3 rounded-lg hover:bg-orange-50 cursor-pointer transition-all duration-200 border border-transparent hover:border-orange-200">
                                    <input type="checkbox" class="rounded text-orange-600 focus:ring-orange-500 h-4 w-4" data-filter="type" data-value="Unknown">
                                    <div class="ml-3 flex-1">
                                        <span class="text-sm font-medium text-gray-700">Unknown</span>
                                        <p class="text-xs text-gray-500">No record found</p>
                                    </div>
                                    <span class="bg-orange-100 text-orange-800 text-xs font-bold px-2.5 py-1 rounded-full">No Record</span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Time Range Filter -->
                        <div class="mb-6">
                            <h5 class="font-medium text-sm text-gray-700 mb-4 flex items-center">
                                <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-clock text-purple-600 text-sm" aria-hidden="true"></i>
                                </div>
                                Time Range
                            </h5>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="flex items-center p-3 rounded-lg hover:bg-purple-50 cursor-pointer transition-all duration-200 border border-transparent hover:border-purple-200">
                                    <input type="checkbox" class="rounded text-purple-600 focus:ring-purple-500 h-4 w-4" data-filter="time" data-value="last-hour">
                                    <div class="ml-3 flex-1">
                                        <span class="text-sm font-medium text-gray-700">Last Hour</span>
                                        <p class="text-xs text-gray-500">Recent activity</p>
                                    </div>
                                </label>
                                <label class="flex items-center p-3 rounded-lg hover:bg-purple-50 cursor-pointer transition-all duration-200 border border-transparent hover:border-purple-200">
                                    <input type="checkbox" class="rounded text-purple-600 focus:ring-purple-500 h-4 w-4" data-filter="time" data-value="last-6-hours">
                                    <div class="ml-3 flex-1">
                                        <span class="text-sm font-medium text-gray-700">Last 6 Hours</span>
                                        <p class="text-xs text-gray-500">Today's activity</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Active Filters Display -->
                        <div id="active-filters" class="mb-6 hidden">
                            <h5 class="font-medium text-sm text-gray-700 mb-3 flex items-center">
                                <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-filter text-indigo-600 text-sm" aria-hidden="true"></i>
                                </div>
                                Active Filters
                            </h5>
                            <div id="filter-tags" class="flex flex-wrap gap-2">
                                <!-- Filter tags will be dynamically added here -->
                            </div>
                        </div>
                    </div>
                    <!-- Action Buttons always visible at bottom -->
                    <div class="flex justify-between items-center pt-4 border-t border-gray-200 bg-white sticky bottom-0 z-10 px-4 pb-4">
                        <button id="reset-filters-btn" class="px-4 py-2.5 text-sm text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-all duration-200 flex items-center font-medium">
                            <i class="fas fa-undo mr-2" aria-hidden="true"></i>
                            Reset All
                        </button>
                        <div class="flex gap-3">
                            <button id="save-filters-btn" class="px-4 py-2.5 text-sm text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-all duration-200 flex items-center font-medium">
                                <i class="fas fa-save mr-2" aria-hidden="true"></i>
                                Save
                            </button>
                            <button id="apply-filters-btn" class="px-5 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 font-medium flex items-center shadow-lg hover:shadow-xl">
                                <i class="fas fa-check mr-2" aria-hidden="true"></i>
                                Apply Filters
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table id="vehicles-table" class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <button class="flex items-center focus:outline-none" onclick="sortTable(0)">
                            License Plate
                            <i class="fas fa-sort ml-1 text-gray-400" aria-hidden="true"></i>
                        </button>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <button class="flex items-center focus:outline-none" onclick="sortTable(1)">
                            Time
                            <i class="fas fa-sort ml-1 text-gray-400" aria-hidden="true"></i>
                        </button>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <button class="flex items-center focus:outline-none" onclick="sortTable(2)">
                            Location
                            <i class="fas fa-sort ml-1 text-gray-400" aria-hidden="true"></i>
                        </button>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <button class="flex items-center focus:outline-none" onclick="sortTable(3)">
                            Owner
                            <i class="fas fa-sort ml-1 text-gray-400" aria-hidden="true"></i>
                        </button>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <button class="flex items-center focus:outline-none" onclick="sortTable(4)">
                            Status
                            <i class="fas fa-sort ml-1 text-gray-400" aria-hidden="true"></i>
                        </button>
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="vehicle-records">
                {{ $slot }}
            </tbody>
        </table>
    </div>
    
    @if($show_entries || $show_pagination)
        <div class="px-4 md:px-6 py-4 flex flex-wrap items-center justify-between gap-3 border-t border-gray-200 bg-gray-50">
            @if($show_entries)
                <div class="flex items-center">
                    <label class="text-sm text-gray-600 mr-2" for="entries-select">Show</label>
                    <select id="entries-select" class="text-sm border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 py-1 pl-2 pr-8 bg-white" aria-label="Number of entries to show">
                        <option>10</option>
                        <option>25</option>
                        <option>50</option>
                        <option>100</option>
                    </select>
                    <span class="text-sm text-gray-600 ml-2">entries</span>
                </div>
            @endif
            
            <div>
                <p class="text-sm text-gray-700">
                    Showing <span class="font-medium">1</span> to <span class="font-medium">{{ $total_entries ?: 4 }}</span> of <span class="font-medium">24</span> entries
                </p>
            </div>
            
            @if($show_pagination)
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50" aria-label="Previous page">
                            <i class="fas fa-chevron-left text-xs" aria-hidden="true"></i>
                        </a>
                        <a href="#" aria-current="page" class="z-10 bg-green-50 border-green-500 text-green-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium" aria-label="Page 1">
                            1
                        </a>
                        <a href="#" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium" aria-label="Page 2">
                            2
                        </a>
                        <a href="#" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 hidden md:inline-flex relative items-center px-4 py-2 border text-sm font-medium" aria-label="Page 3">
                            3
                        </a>
                        <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                            ...
                        </span>
                        <a href="#" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 hidden md:inline-flex relative items-center px-4 py-2 border text-sm font-medium" aria-label="Page 6">
                            6
                        </a>
                        <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50" aria-label="Next page">
                            <i class="fas fa-chevron-right text-xs" aria-hidden="true"></i>
                        </a>
                    </nav>
                </div>
            @endif
        </div>
    @endif
</div>