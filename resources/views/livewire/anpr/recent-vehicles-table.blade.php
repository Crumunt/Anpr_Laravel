<div class="px-4 md:px-8 pb-4 md:pb-8" wire:poll.5s>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- Table Header with Filters --}}
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-white to-gray-50/50">
            <div class="flex flex-col gap-4">
                {{-- Title Row --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">Recent Vehicles</h2>
                        <p class="text-sm text-gray-500">Last 24 hours activity</p>
                    </div>

                    {{-- Clear Filters Button --}}
                    @if($search || $gateFilter !== 'all' || $statusFilter !== 'all')
                        <button
                            wire:click="clearFilters"
                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors"
                        >
                            <i class="fas fa-times-circle mr-2"></i>
                            Clear All Filters
                        </button>
                    @endif
                </div>

                {{-- Filters Row --}}
                <div class="flex flex-col sm:flex-row gap-3">
                    {{-- Search Input --}}
                    <div class="relative flex-1 sm:max-w-xs">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400 text-sm"></i>
                        </div>
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Search plate numbers..."
                            class="block w-full pl-10 pr-10 py-2.5 text-sm border border-gray-200 rounded-lg bg-white focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all placeholder-gray-400"
                        >
                        @if($search)
                            <button
                                wire:click="$set('search', '')"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors"
                            >
                                <i class="fas fa-times text-sm"></i>
                            </button>
                        @endif
                    </div>

                    {{-- Gate Filter --}}
                    <div class="relative">
                        <select
                            wire:model.live="gateFilter"
                            class="appearance-none w-full sm:w-44 pl-4 pr-10 py-2.5 text-sm border border-gray-200 rounded-lg bg-white focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all cursor-pointer"
                        >
                            <option value="all">All Gates</option>
                            @foreach($availableGates as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                        </div>
                    </div>

                    {{-- Status Filter --}}
                    <div class="relative">
                        <select
                            wire:model.live="statusFilter"
                            class="appearance-none w-full sm:w-40 pl-4 pr-10 py-2.5 text-sm border border-gray-200 rounded-lg bg-white focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all cursor-pointer"
                        >
                            <option value="all">All Status</option>
                            <option value="normal">Normal</option>
                            <option value="flagged">Flagged</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table Content --}}
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <button
                                wire:click="sortBy('plate_number')"
                                class="flex items-center text-xs font-semibold text-gray-500 uppercase tracking-wider hover:text-gray-700"
                            >
                                Plate Number
                                @if($sortField === 'plate_number')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @else
                                    <i class="fas fa-sort ml-1 opacity-50"></i>
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <button
                                wire:click="sortBy('confidence')"
                                class="flex items-center text-xs font-semibold text-gray-500 uppercase tracking-wider hover:text-gray-700"
                            >
                                Confidence
                                @if($sortField === 'confidence')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @else
                                    <i class="fas fa-sort ml-1 opacity-50"></i>
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <button
                                wire:click="sortBy('detected_at')"
                                class="flex items-center text-xs font-semibold text-gray-500 uppercase tracking-wider hover:text-gray-700"
                            >
                                Detection Time
                                @if($sortField === 'detected_at')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @else
                                    <i class="fas fa-sort ml-1 opacity-50"></i>
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Gate</span>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Gate Pass</span>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</span>
                        </th>
                        <th class="px-6 py-3 text-right">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($records as $record)
                        @php
                            $isFlagged = $record->is_flagged;
                            $showRecord = $statusFilter === 'all' ||
                                ($statusFilter === 'flagged' && $isFlagged) ||
                                ($statusFilter === 'normal' && !$isFlagged);
                        @endphp

                        @if($showRecord)
                            <tr class="hover:bg-gray-50 transition-colors" wire:key="record-{{ $record->id }}">
                                {{-- Plate Number --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-lg flex items-center justify-center {{ $isFlagged ? 'bg-red-100' : 'bg-green-100' }} mr-3">
                                            <i class="fas fa-car {{ $isFlagged ? 'text-red-600' : 'text-green-600' }}"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800 tracking-wide">{{ $record->plate_number }}</p>
                                            <p class="text-xs text-gray-400">{{ $record->camera_id ?? 'Camera #1' }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Confidence Score --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                            <div
                                                class="h-2 rounded-full {{ $record->confidence >= 0.9 ? 'bg-green-500' : ($record->confidence >= 0.75 ? 'bg-yellow-500' : 'bg-red-500') }}"
                                                style="width: {{ $record->confidence_percent }}%"
                                            ></div>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700">{{ $record->confidence_percent }}%</span>
                                    </div>
                                </td>

                                {{-- Detection Time --}}
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="text-sm text-gray-800">{{ $record->detected_at?->format('H:i:s') ?? 'N/A' }}</p>
                                        <p class="text-xs text-gray-400">{{ $record->detected_at?->format('M d, Y') ?? '' }}</p>
                                    </div>
                                </td>

                                {{-- Gate --}}
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                        <i class="fas fa-map-marker-alt mr-1 text-gray-400"></i>
                                        {{ $record->gate_display_name }}
                                    </span>
                                </td>

                                {{-- Gate Pass --}}
                                <td class="px-6 py-4">
                                    @if($record->has_gate_pass)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                            <i class="fas fa-id-card mr-1"></i>
                                            {{ $record->gate_pass_number }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                            <i class="fas fa-minus-circle mr-1"></i>
                                            None
                                        </span>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4">
                                    @if($isFlagged)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                            <i class="fas fa-flag mr-1"></i>
                                            Flagged
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Normal
                                        </span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        {{-- Edit Button --}}
                                        <button
                                            wire:click="editRecord('{{ $record->id }}')"
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-600 bg-gray-100 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                            title="Edit Record"
                                        >
                                            <i class="fas fa-edit mr-1.5"></i>
                                            Edit
                                        </button>

                                        {{-- Flag/Unflag Button --}}
                                        <button
                                            wire:click="toggleFlag('{{ $record->id }}')"
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ $isFlagged ? 'text-red-600 bg-red-50 hover:bg-red-100' : 'text-gray-600 bg-gray-100 hover:text-red-600 hover:bg-red-50' }}"
                                            title="{{ $isFlagged ? 'Unflag Record' : 'Flag Record' }}"
                                        >
                                            <i class="fas fa-flag mr-1.5"></i>
                                            {{ $isFlagged ? 'Unflag' : 'Flag' }}
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                        <i class="fas fa-car text-3xl text-gray-300"></i>
                                    </div>
                                    <p class="text-gray-500 font-medium">No vehicle records found</p>
                                    <p class="text-gray-400 text-sm mt-1">
                                        @if($search || $gateFilter !== 'all' || $statusFilter !== 'all')
                                            Try adjusting your filters
                                        @else
                                            No detections in the last 24 hours
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($records->hasPages())
            <div class="bg-white border-t border-gray-200 px-4 py-4 sm:px-6">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    {{-- Results Summary --}}
                    <div class="flex items-center gap-2 text-sm text-gray-700">
                        <span class="font-medium">Showing</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md bg-green-50 text-green-700 font-semibold border border-green-200">
                            {{ $records->firstItem() ?? 0 }}
                        </span>
                        <span class="text-gray-500">to</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md bg-green-50 text-green-700 font-semibold border border-green-200">
                            {{ $records->lastItem() ?? 0 }}
                        </span>
                        <span class="text-gray-500">of</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md bg-gray-100 text-gray-900 font-semibold border border-gray-300">
                            {{ $records->total() }}
                        </span>
                        <span class="font-medium">results</span>
                    </div>

                    {{-- Pagination Controls --}}
                    <div class="flex items-center gap-2">
                        {{-- Previous Button --}}
                        @if ($records->onFirstPage())
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

                        {{-- Page Numbers (Desktop) --}}
                        <div class="hidden sm:flex items-center gap-1">
                            @php
                                $currentPage = $records->currentPage();
                                $lastPage = $records->lastPage();
                                $pages = [];

                                // Always show first page
                                $pages[] = 1;

                                // Calculate range around current page
                                $start = max(2, $currentPage - 1);
                                $end = min($lastPage - 1, $currentPage + 1);

                                // Add dots if needed before range
                                if ($start > 2) {
                                    $pages[] = '...';
                                }

                                // Add range pages
                                for ($i = $start; $i <= $end; $i++) {
                                    $pages[] = $i;
                                }

                                // Add dots if needed after range
                                if ($end < $lastPage - 1) {
                                    $pages[] = '...';
                                }

                                // Always show last page if more than 1 page
                                if ($lastPage > 1) {
                                    $pages[] = $lastPage;
                                }
                            @endphp

                            @foreach ($pages as $page)
                                @if ($page === '...')
                                    <span class="inline-flex items-center justify-center w-10 h-10 text-gray-500 font-medium">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M6 12a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                    </span>
                                @elseif ($page == $currentPage)
                                    <span class="inline-flex items-center justify-center w-10 h-10 text-sm font-bold text-white bg-gradient-to-br from-green-600 to-green-700 rounded-lg shadow-sm ring-2 ring-green-500/50">
                                        {{ $page }}
                                    </span>
                                @else
                                    <button wire:click="gotoPage({{ $page }})"
                                            type="button"
                                            class="inline-flex items-center justify-center w-10 h-10 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-green-50 hover:border-green-400 hover:text-green-700 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-green-500/50">
                                        {{ $page }}
                                    </button>
                                @endif
                            @endforeach
                        </div>

                        {{-- Mobile: Current Page Indicator --}}
                        <div class="sm:hidden inline-flex items-center px-3 py-2 text-sm font-semibold text-gray-900 bg-gray-100 border border-gray-300 rounded-lg">
                            Page {{ $records->currentPage() }} of {{ $records->lastPage() }}
                        </div>

                        {{-- Next Button --}}
                        @if ($records->hasMorePages())
                            <button wire:click="nextPage"
                                    type="button"
                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-gradient-to-r from-green-600 to-green-700 rounded-lg hover:from-green-700 hover:to-green-800 transition-all duration-200 shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-green-500/50">
                                Next
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        @else
                            <span class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-400 bg-gray-50 border border-gray-200 rounded-lg cursor-not-allowed">
                                Next
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="px-6 py-4 border-t border-gray-100">
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <span class="font-medium">Total:</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md bg-gray-100 text-gray-900 font-semibold border border-gray-300">
                        {{ $records->count() }}
                    </span>
                    <span>{{ Str::plural('record', $records->count()) }}</span>
                </div>
            </div>
        @endif
    </div>

    {{-- Edit Modal --}}
    @if($showEditModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                {{-- Backdrop --}}
                <div
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                    wire:click="closeEditModal"
                ></div>

                {{-- Modal Content --}}
                <div class="relative inline-block bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full">
                    <div class="bg-white px-6 py-5">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800" id="modal-title">
                                Edit Record
                            </h3>
                            <button
                                wire:click="closeEditModal"
                                class="text-gray-400 hover:text-gray-600 transition-colors"
                            >
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <form wire:submit="saveRecord">
                            {{-- Plate Number --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Plate Number
                                </label>
                                <input
                                    type="text"
                                    wire:model="editForm.plate_number"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent uppercase"
                                    placeholder="ABC 1234"
                                >
                                @error('editForm.plate_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Gate --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Gate
                                </label>
                                <select
                                    wire:model="editForm.gate_type"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white"
                                >
                                    <option value="">Select Gate</option>
                                    @foreach($availableGates as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('editForm.gate_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Confidence --}}
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Confidence Score (0-1)
                                </label>
                                <input
                                    type="number"
                                    wire:model="editForm.confidence"
                                    step="0.01"
                                    min="0"
                                    max="1"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    placeholder="0.95"
                                >
                                @error('editForm.confidence')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Actions --}}
                            <div class="flex items-center justify-end space-x-3">
                                <button
                                    type="button"
                                    wire:click="closeEditModal"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white rounded-lg transition-colors"
                                    style="background-color: #1a5632;"
                                >
                                    <i class="fas fa-save mr-1"></i>
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
