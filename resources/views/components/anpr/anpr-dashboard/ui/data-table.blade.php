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
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table id="vehicles-table" class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">License Plate</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Owner</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
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
                    Showing <span class="font-medium">1</span> to <span class="font-medium">{{ $total_entries ?: 4 }}</span> of <span class="font-medium">{{ $total_entries ?: 24 }}</span> entries
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
                        <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50" aria-label="Next page">
                            <i class="fas fa-chevron-right text-xs" aria-hidden="true"></i>
                        </a>
                    </nav>
                </div>
            @endif
        </div>
    @endif
</div>
