<div class="w-full bg-white rounded-xl shadow-sm transition-all duration-300 hover:shadow-md border border-gray-100"> {{--removed overflow-hidden on root div--}}

    <!-- Bulk Selection Bar -->
    @if(count($selectedRows))
    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-b border-green-100 px-6 py-3 flex items-center justify-between sticky top-0 z-40 backdrop-blur-sm">
        <div class="flex items-center gap-3">
            <div class="flex items-center justify-center w-8 h-8 bg-green-600 text-white rounded-full text-xs font-bold">
                {{ count($selectedRows) }}
            </div>
            <span class="text-sm font-medium text-green-900">
                {{ count($selectedRows) }} {{ (count($selectedRows) > 1) ? 'items' : 'item' }} selected
            </span>
        </div>

        <div class="flex items-center gap-2">
            <!-- Clear Selection Button -->
            <button wire:click="clearSelection" type="button"
                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-white/50 rounded-md transition-all duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Clear
            </button>

            <!-- Bulk Actions Dropdown -->
            <div class="relative" x-data="{isOpen: false}">
                <button @click="isOpen = !isOpen"
                        type="button"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500/50 transition-all duration-200 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Bulk Actions
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-4 w-4 ml-2 transition-transform duration-200"
                         :class="{'rotate-180': isOpen}"
                         viewBox="0 0 20 20"
                         fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="isOpen"
                     x-cloak
                     @click.away="isOpen = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-lg shadow-xl overflow-hidden z-50">

                    <div class="px-4 py-3 border-b border-gray-100 bg-gray-50">
                        <p class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Available Actions</p>
                    </div>

                    <div class="py-1">
                        @foreach ($bulkActionBtns as $action => $label)
                        <button wire:click="executeBulkAction('{{ $action }}')"
                                @click="isOpen = false"
                                class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 transition-colors duration-150 flex items-center group">
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400 group-hover:bg-green-600 mr-3 transition-colors duration-150"></span>
                            {{ $label }}
                        </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Table Container with Horizontal Scroll -->
    <div class="relative w-full"> {{--removed overflow-x-auto on table root div--}}
        <table class="w-full text-sm border-collapse">

            <!-- Table Caption -->
            @if($caption)
            <caption class="py-4 text-sm text-gray-500 font-medium">{{ $caption }}</caption>
            @endif

            <!-- Table Header -->
            <thead class="sticky top-0 bg-gradient-to-r from-gray-50 to-gray-100 z-10 border-b border-gray-200">
                <tr>
                    <!-- Select All Checkbox -->
                    @if($showCheckboxes)
                    <th class="w-12 px-4 py-4 text-left">
                        <div class="flex items-center justify-center">
                            <input type="checkbox"
                                   wire:model.live="selectedAll"
                                   class="w-4 h-4 text-green-600 bg-white border-gray-300 rounded focus:ring-green-500 focus:ring-2 cursor-pointer transition-all duration-200">
                        </div>
                    </th>
                    @endif

                    <!-- Column Headers -->
                    @foreach ($headers as $header)
                        @php
                            $headerData = is_array($header) ? $header : ['key' => $header, 'label' => $header];
                            $label = $headerData['label'];
                            $width = $headerData['width'] ?? 'auto';
                            $sortable = $headerData['sortable'] ?? false;
                        @endphp

                        <th class="px-4 py-4 text-left align-middle font-semibold text-gray-700 text-xs uppercase tracking-wider whitespace-nowrap"
                            style="width: {{ $width }}">
                            @if($sortable)
                            <button wire:click="sortBy('{{ $headerData['key'] }}')"
                                    class="flex items-center gap-2 hover:text-green-600 transition-colors duration-150">
                                {{ $label }}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                </svg>
                            </button>
                            @else
                                {{ $label }}
                            @endif
                        </th>
                    @endforeach

                    <!-- Actions Column -->
                    @if($showActions)
                    <th class="px-4 py-4 text-right align-middle font-semibold text-gray-700 text-xs uppercase tracking-wider w-24">
                        Actions
                    </th>
                    @endif
                </tr>
            </thead>

            <!-- Table Body -->
            <tbody class="divide-y divide-gray-100 bg-white" id="user_data">
                @forelse ($rows as $index => $row)
                    @php
                        $isSelected = isset($selectedRows[$row['id']]);
                        $rowCount = count($rows);
                    @endphp

                    <tr class="group relative transition-all duration-200 hover:bg-green-50/60 {{ $isSelected ? 'bg-green-50/80 shadow-sm' : '' }}"
                        wire:key="row-{{ $row['id'] }}"
                        >

                        <!-- Row Checkbox -->
                        @if($showCheckboxes)
                        <td class="px-4 py-4 align-middle">
                            <div class="flex items-center justify-center">
                                <input type="checkbox"
                                       :checked="{{ $isSelected ? 'true' : 'false' }}"
                                       wire:click="toggleRow('{{$row['id']}}')"
                                       value="{{ $row['id'] }}"
                                       id="checkbox-{{ $row['id'] }}"
                                       class="w-4 h-4 text-green-600 bg-white border-gray-300 rounded focus:ring-green-500 focus:ring-2 cursor-pointer transition-all duration-200">
                            </div>
                        </td>
                        @endif

                        <!-- Row Data Cells -->
                        @foreach ($headers as $header)
                            @php
                                $key = $header['key'] ?? $header['label'];
                                $value = $row[$key] ?? null;
                                $isBold = $key === 'clsu_id' || ($header['bold'] ?? false);
                            @endphp

                            <x-table.cell-renderer :value="$value" :index="$index" :isBold="$isBold" :rowCount="$rowCount"/>
                        @endforeach

                        <!-- Actions Column -->
                        @if($showActions)
                        <td class="px-4 py-4 align-middle text-right">
                            <div class="relative inline-block" x-data="{open: false}">
                                <button @click="open = !open"
                                        type="button"
                                        class="inline-flex items-center justify-center w-8 h-8 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-green-500/50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                    </svg>
                                </button>

                                <!-- Action Dropdown -->
                                <div x-show="open"
                                     x-cloak
                                     @click.away="open = false"
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden z-50">

                                    <div class="py-1">
                                        <button wire:click="viewRow('{{ $row['id'] }}')"
                                                @click="open = false"
                                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-150 flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            View Details
                                        </button>

                                        <button wire:click="editRow('{{ $row['id'] }}')"
                                                @click="open = false"
                                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-150 flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </button>

                                        <hr class="my-1 border-gray-100">

                                        <button wire:click="deleteRow('{{ $row['id'] }}')"
                                                @click="open = false"
                                                wire:confirm="Are you sure you want to delete this item?"
                                                class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors duration-150 flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($headers) + ($showCheckboxes ? 1 : 0) + ($showActions ? 1 : 0) }}"
                            class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center gap-4">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-lg font-semibold text-gray-700 mb-1">No data available</p>
                                    <p class="text-sm text-gray-500">There are no records to display at the moment.</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Loading State Overlay -->
    {{-- <div wire:loading class="absolute inset-0 bg-white/80 backdrop-blur-sm flex items-center justify-center z-50 rounded-xl">
        <div class="flex flex-col items-center gap-3">
            <div class="animate-spin rounded-full h-10 w-10 border-4 border-green-200 border-t-green-600"></div>
            <p class="text-sm font-medium text-gray-600">Loading data...</p>
        </div>
    </div> --}}
</div>

<!-- Alpine.js Initialization -->
<script>
    document.addEventListener('alpine:init', () => {
        // Action dropdown component
        Alpine.data('actionDropdown', () => ({
            open: false,
            toggle() {
                this.open = !this.open
            },
            close() {
                this.open = false
            }
        }))
    })

    // Table configuration for external JS
    window.tableConfig = {
        showCheckboxes: @json($showCheckboxes),
        showActions: @json($showActions),
        headers: @json($headers),
        selectedRows: @json($selectedRows)
    };
</script>

<!-- External JS (if needed) -->
@if(file_exists(public_path('js/components/data.js')))
<script src="{{ asset('js/components/data.js') }}"></script>
@endif
