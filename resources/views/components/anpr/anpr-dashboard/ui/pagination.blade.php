@props([
    'currentPage' => 1,
    'totalEntries' => 0,
    'perPage' => 10,
    'startEntry' => 1,
    'endEntry' => 10,
    'totalPages' => 1
])
<div class="px-4 md:px-6 py-4 flex flex-wrap items-center justify-between gap-3 border-t border-gray-200 bg-gray-50">
    <div class="flex items-center">
        <label class="text-sm text-gray-600 mr-2" for="entries-select">Show</label>
        <select id="entries-select" class="text-sm border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 py-1 pl-2 pr-8 bg-white" aria-label="Number of entries to show">
            <option @if($perPage == 10) selected @endif>10</option>
            <option @if($perPage == 25) selected @endif>25</option>
            <option @if($perPage == 50) selected @endif>50</option>
            <option @if($perPage == 100) selected @endif>100</option>
        </select>
        <span class="text-sm text-gray-600 ml-2">entries</span>
    </div>
    <div>
        <p class="text-sm text-gray-700">
            Showing <span class="font-medium">{{ $startEntry }}</span> to <span class="font-medium">{{ $endEntry }}</span> of <span class="font-medium">{{ $totalEntries }}</span> entries
        </p>
    </div>
    <div>
        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
            <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50" aria-label="Previous page">
                <i class="fas fa-chevron-left text-xs" aria-hidden="true"></i>
            </a>
            @for ($i = 1; $i <= $totalPages; $i++)
                @if ($i == $currentPage)
                    <a href="#" aria-current="page" class="z-10 bg-green-50 border-green-500 text-green-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium" aria-label="Page {{ $i }}">
                        {{ $i }}
                    </a>
                @elseif ($i <= 2 || $i > $totalPages - 2 || abs($i - $currentPage) <= 1)
                    <a href="#" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium" aria-label="Page {{ $i }}">
                        {{ $i }}
                    </a>
                @elseif ($i == 3 && $currentPage > 4)
                    <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">...</span>
                @elseif ($i == $totalPages - 2 && $currentPage < $totalPages - 3)
                    <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">...</span>
                @endif
            @endfor
            <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50" aria-label="Next page">
                <i class="fas fa-chevron-right text-xs" aria-hidden="true"></i>
            </a>
        </nav>
    </div>
</div> 