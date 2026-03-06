<div>
    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-6">
        {{-- Total Flagged --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 lg:p-6 hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-flag text-red-600 text-lg"></i>
                </div>
                <span class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Total</span>
            </div>
            <p class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($stats['total_flagged']) }}</p>
            <p class="text-sm text-gray-600 font-medium">Total Flags</p>
        </div>

        {{-- High Priority --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 lg:p-6 hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-exclamation-circle text-red-600 text-lg"></i>
                </div>
                <span class="text-xs text-gray-500 font-semibold uppercase tracking-wide">High</span>
            </div>
            <p class="text-3xl font-bold text-red-600 mb-1">{{ number_format($stats['high_priority']) }}</p>
            <p class="text-sm text-gray-600 font-medium">High Priority</p>
        </div>

        {{-- Medium Priority --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 lg:p-6 hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-amber-600 text-lg"></i>
                </div>
                <span class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Medium</span>
            </div>
            <p class="text-3xl font-bold text-amber-600 mb-1">{{ number_format($stats['medium_priority']) }}</p>
            <p class="text-sm text-gray-600 font-medium">Medium Priority</p>
        </div>

        {{-- Resolved Today --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 lg:p-6 hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-lg"></i>
                </div>
                <span class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Resolved</span>
            </div>
            <p class="text-3xl font-bold text-green-600 mb-1">{{ number_format($stats['resolved_today']) }}</p>
            <p class="text-sm text-gray-600 font-medium">Resolved Today</p>
        </div>
    </div>

    {{-- Filters and Actions --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            {{-- Filters --}}
            <div class="flex flex-wrap items-center gap-3">
                {{-- Search --}}
                <div class="relative flex-1 min-w-[200px]">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400 text-sm"></i>
                    </div>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search plate numbers..."
                        class="block w-full pl-10 pr-4 py-2.5 text-sm border border-gray-200 rounded-lg bg-white focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    >
                </div>

                {{-- Priority Filter --}}
                <select
                    wire:model.live="priorityFilter"
                    class="px-4 py-2.5 text-sm border border-gray-200 rounded-lg bg-white focus:ring-2 focus:ring-green-500 focus:border-green-500"
                >
                    <option value="all">All Priorities</option>
                    <option value="high">High Priority</option>
                    <option value="medium">Medium Priority</option>
                    <option value="low">Low Priority</option>
                </select>

                {{-- Status Filter --}}
                <select
                    wire:model.live="statusFilter"
                    class="px-4 py-2.5 text-sm border border-gray-200 rounded-lg bg-white focus:ring-2 focus:ring-green-500 focus:border-green-500"
                >
                    <option value="all">All Status</option>
                    <option value="active">Active</option>
                    <option value="resolved">Resolved</option>
                    <option value="dismissed">Dismissed</option>
                </select>

                {{-- Date Range --}}
                <select
                    wire:model.live="dateRange"
                    class="px-4 py-2.5 text-sm border border-gray-200 rounded-lg bg-white focus:ring-2 focus:ring-green-500 focus:border-green-500"
                >
                    <option value="24hours">Last 24 Hours</option>
                    <option value="7days">Last 7 Days</option>
                    <option value="30days">Last 30 Days</option>
                    <option value="90days">Last 90 Days</option>
                    <option value="all">All Time</option>
                </select>

                {{-- Clear Filters --}}
                @if($search || $priorityFilter !== 'all' || $statusFilter !== 'all' || $dateRange !== 'all')
                    <button
                        wire:click="clearFilters"
                        class="px-3 py-2.5 text-sm text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors"
                    >
                        <i class="fas fa-times-circle mr-1"></i>
                        Clear
                    </button>
                @endif
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-2">
                @if(count($selectedRecords) > 0)
                    <button
                        wire:click="bulkResolve"
                        class="px-4 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors"
                    >
                        <i class="fas fa-check mr-2"></i>
                        Resolve ({{ count($selectedRecords) }})
                    </button>
                @endif

                <button
                    wire:click="openFlagModal"
                    class="px-4 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors"
                >
                    <i class="fas fa-plus mr-2"></i>
                    Flag Vehicle
                </button>
            </div>
        </div>
    </div>

    {{-- Flagged Vehicles Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Flagged Vehicles List</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ $records->total() }} vehicle(s) flagged</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <input
                                type="checkbox"
                                wire:model.live="selectAll"
                                class="rounded border-gray-300 text-green-600 focus:ring-green-500"
                            >
                        </th>
                        <th class="px-6 py-3 text-left">
                            <button wire:click="sortBy('plate_number')" class="flex items-center text-xs font-semibold text-gray-500 uppercase tracking-wider hover:text-gray-700">
                                Plate Number
                                @if($sortField === 'plate_number')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @else
                                    <i class="fas fa-sort ml-1 opacity-50"></i>
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <button wire:click="sortBy('priority')" class="flex items-center text-xs font-semibold text-gray-500 uppercase tracking-wider hover:text-gray-700">
                                Priority
                                @if($sortField === 'priority')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @else
                                    <i class="fas fa-sort ml-1 opacity-50"></i>
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Reason</span>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Flagged By</span>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <button wire:click="sortBy('created_at')" class="flex items-center text-xs font-semibold text-gray-500 uppercase tracking-wider hover:text-gray-700">
                                Flagged At
                                @if($sortField === 'created_at')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @else
                                    <i class="fas fa-sort ml-1 opacity-50"></i>
                                @endif
                            </button>
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
                        <tr class="hover:bg-gray-50 transition-colors" wire:key="flagged-{{ $record->id }}">
                            {{-- Checkbox --}}
                            <td class="px-6 py-4">
                                <input
                                    type="checkbox"
                                    wire:click="toggleSelection('{{ $record->id }}')"
                                    @checked(in_array($record->id, $selectedRecords))
                                    class="rounded border-gray-300 text-green-600 focus:ring-green-500"
                                >
                            </td>

                            {{-- Plate Number --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-red-100 mr-3">
                                        <i class="fas fa-car text-red-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800 tracking-wide">{{ $record->plate_number }}</p>
                                        <p class="text-xs text-gray-400">{{ $record->vehicle_info }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Priority --}}
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $record->priority_badge_class }}">
                                    <i class="fas fa-{{ $record->priority_icon }} mr-1"></i>
                                    {{ ucfirst($record->priority) }}
                                </span>
                            </td>

                            {{-- Reason --}}
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-700">{{ $record->reason_display }}</span>
                                @if($record->notes)
                                    <p class="text-xs text-gray-400 truncate max-w-[150px]" title="{{ $record->notes }}">{{ $record->notes }}</p>
                                @endif
                            </td>

                            {{-- Flagged By --}}
                            <td class="px-6 py-4">
                                <div>
                                    <p class="text-sm text-gray-800">{{ $record->flagged_by_name ?? 'Unknown' }}</p>
                                    <p class="text-xs text-gray-400">{{ $record->flagged_by_role ?? 'N/A' }}</p>
                                </div>
                            </td>

                            {{-- Flagged At --}}
                            <td class="px-6 py-4">
                                <div>
                                    <p class="text-sm text-gray-800">{{ $record->created_at?->format('M d, Y') ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-400">{{ $record->created_at?->format('H:i:s') ?? '' }}</p>
                                </div>
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'active' => 'bg-red-100 text-red-700',
                                        'resolved' => 'bg-green-100 text-green-700',
                                        'dismissed' => 'bg-gray-100 text-gray-700',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $statusColors[$record->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ ucfirst($record->status) }}
                                </span>
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <button
                                        wire:click="viewDetails('{{ $record->id }}')"
                                        class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                        title="View Details"
                                    >
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if($record->status === 'active')
                                        <button
                                            wire:click="resolveRecord('{{ $record->id }}')"
                                            class="p-2 text-green-600 bg-green-50 hover:bg-green-100 rounded-lg transition-colors"
                                            title="Resolve"
                                        >
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button
                                            wire:click="dismissRecord('{{ $record->id }}')"
                                            class="p-2 text-gray-600 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors"
                                            title="Dismiss"
                                        >
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mb-4">
                                        <i class="fas fa-check-circle text-3xl text-green-500"></i>
                                    </div>
                                    <p class="text-gray-500 font-medium">No flagged vehicles found</p>
                                    <p class="text-gray-400 text-sm mt-1">All vehicles are operating normally</p>
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

                    <div class="flex items-center gap-2">
                        @if ($records->onFirstPage())
                            <span class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-400 bg-gray-50 border border-gray-200 rounded-lg cursor-not-allowed">
                                <i class="fas fa-chevron-left mr-1.5"></i> Previous
                            </span>
                        @else
                            <button wire:click="previousPage" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all">
                                <i class="fas fa-chevron-left mr-1.5"></i> Previous
                            </button>
                        @endif

                        <span class="inline-flex items-center px-3 py-2 text-sm font-semibold text-gray-900 bg-gray-100 border border-gray-300 rounded-lg">
                            Page {{ $records->currentPage() }} of {{ $records->lastPage() }}
                        </span>

                        @if ($records->hasMorePages())
                            <button wire:click="nextPage" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-gradient-to-r from-green-600 to-green-700 rounded-lg hover:from-green-700 hover:to-green-800 transition-all">
                                Next <i class="fas fa-chevron-right ml-1.5"></i>
                            </button>
                        @else
                            <span class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-400 bg-gray-50 border border-gray-200 rounded-lg cursor-not-allowed">
                                Next <i class="fas fa-chevron-right ml-1.5"></i>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Detail Modal --}}
    @if($showDetailModal && $viewingRecord)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeDetailModal"></div>

                <div class="relative inline-block bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-2xl sm:w-full">
                    <div class="bg-white px-6 py-5">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-semibold text-gray-800">Flagged Vehicle Details</h3>
                            <button wire:click="closeDetailModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                                <i class="fas fa-times text-lg"></i>
                            </button>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Plate Number</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $viewingRecord->plate_number }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Vehicle Info</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $viewingRecord->vehicle_info }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Priority</label>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $viewingRecord->priority_badge_class }}">
                                    <i class="fas fa-{{ $viewingRecord->priority_icon }} mr-2"></i>
                                    {{ ucfirst($viewingRecord->priority) }}
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Reason</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $viewingRecord->reason_display }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Flagged By</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $viewingRecord->flagged_by_name ?? 'Unknown' }}</p>
                                <p class="text-sm text-gray-500">{{ $viewingRecord->flagged_by_role ?? '' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Flagged At</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $viewingRecord->created_at?->format('M d, Y H:i:s') }}</p>
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                                @php
                                    $statusColors = [
                                        'active' => 'bg-red-100 text-red-700',
                                        'resolved' => 'bg-green-100 text-green-700',
                                        'dismissed' => 'bg-gray-100 text-gray-700',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$viewingRecord->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    <i class="fas fa-{{ $viewingRecord->status === 'active' ? 'flag' : ($viewingRecord->status === 'resolved' ? 'check-circle' : 'times-circle') }} mr-2"></i>
                                    {{ ucfirst($viewingRecord->status) }}
                                </span>
                            </div>
                            @if($viewingRecord->notes)
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Notes</label>
                                    <p class="text-gray-700">{{ $viewingRecord->notes }}</p>
                                </div>
                            @endif
                            @if($viewingRecord->status !== 'active' && $viewingRecord->resolution_notes)
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Resolution Notes</label>
                                    <p class="text-gray-700">{{ $viewingRecord->resolution_notes }}</p>
                                </div>
                            @endif
                        </div>

                        <div class="mt-6 flex justify-end gap-3">
                            <button
                                wire:click="closeDetailModal"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                            >
                                Close
                            </button>
                            @if($viewingRecord->status === 'active')
                                <button
                                    wire:click="resolveRecord('{{ $viewingRecord->id }}')"
                                    class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors"
                                >
                                    <i class="fas fa-check mr-2"></i>
                                    Resolve
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Flag Vehicle Modal --}}
    @if($showFlagModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeFlagModal"></div>

                <div class="relative inline-block bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full max-h-[90vh] overflow-y-auto">
                    <div class="bg-white px-6 py-5">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Flag a Vehicle</h3>
                            <button wire:click="closeFlagModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <form wire:submit="flagVehicle">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Plate Number <span class="text-red-500">*</span></label>
                                <input
                                    type="text"
                                    wire:model="flagForm.plate_number"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent uppercase"
                                    placeholder="ABC 1234"
                                >
                                @error('flagForm.plate_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Reason for Flagging <span class="text-red-500">*</span></label>
                                <select
                                    wire:model="flagForm.reason"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white"
                                >
                                    <option value="">Select reason...</option>
                                    @foreach($reasonOptions as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('flagForm.reason')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Priority <span class="text-red-500">*</span></label>
                                <select
                                    wire:model="flagForm.priority"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white"
                                >
                                    <option value="high">High Priority</option>
                                    <option value="medium">Medium Priority</option>
                                    <option value="low">Low Priority</option>
                                </select>
                            </div>

                            {{-- Vehicle Details (Optional) --}}
                            <div class="mb-4 border-t border-gray-200 pt-4">
                                <p class="text-sm font-medium text-gray-700 mb-3">Vehicle Details (Optional)</p>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Make</label>
                                        <input
                                            type="text"
                                            wire:model="flagForm.vehicle_make"
                                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                            placeholder="Toyota"
                                        >
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Model</label>
                                        <input
                                            type="text"
                                            wire:model="flagForm.vehicle_model"
                                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                            placeholder="Vios"
                                        >
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Color</label>
                                        <input
                                            type="text"
                                            wire:model="flagForm.vehicle_color"
                                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                            placeholder="White"
                                        >
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Type</label>
                                        <select
                                            wire:model="flagForm.vehicle_type"
                                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white"
                                        >
                                            <option value="">Select...</option>
                                            <option value="Sedan">Sedan</option>
                                            <option value="SUV">SUV</option>
                                            <option value="Van">Van</option>
                                            <option value="Truck">Truck</option>
                                            <option value="Motorcycle">Motorcycle</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Notes (Optional)</label>
                                <textarea
                                    wire:model="flagForm.notes"
                                    rows="3"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    placeholder="Additional information about why this vehicle is being flagged..."
                                ></textarea>
                            </div>

                            <div class="flex items-center justify-end space-x-3">
                                <button
                                    type="button"
                                    wire:click="closeFlagModal"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors"
                                >
                                    <i class="fas fa-flag mr-2"></i>
                                    Flag Vehicle
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
