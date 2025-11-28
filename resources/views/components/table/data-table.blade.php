<div x-data="dataTable({{ count($rows) }})"
     class="w-full bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-md">

    <!-- Bulk Actions / Selection Indicator -->
    <div x-show="selectedRows.length > 0" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform -translate-y-4"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-4"
         class="bg-green-50 border-b border-green-100 px-4 py-2 flex items-center justify-between top-0 z-40">

        <div class="text-sm text-green-800 font-medium">
            <span x-text="selectedRows.length"></span> item<span x-show="selectedRows.length !== 1">s</span> selected
        </div>

        <div class="relative">
            <button @click="toggleBulkActionMenu($event)" type="button"
                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-green-700 bg-green-100 rounded-md hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-green-500/50 transition-all duration-200">
                Bulk Actions
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                          clip-rule="evenodd" />
                </svg>
            </button>

            <!-- Bulk actions dropdown -->
            <div x-show="showBulkActionDropdown" x-cloak @click.away="showBulkActionDropdown = false"
                 class="absolute right-0 mt-2 bg-white border border-gray-200 rounded-lg shadow-xl min-w-[180px] overflow-hidden z-50">
                <div class="divide-y divide-gray-100">
                    <div class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">
                        Bulk Actions
                    </div>
                    <div class="p-1">
                        @foreach ($bulkActionBtns as $btn)
                            <button @click="executeBulkAction('{{ $btn['key'] }}', $event)"
                                    class="w-full text-left px-3 py-1 text-sm hover:bg-gray-100">
                                {{ $btn['key'] }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="relative w-full table-container overflow-x-auto">
        <table class="w-full caption-bottom text-sm">
            @if($caption)
                <caption class="mt-4 text-sm text-gray-500 animate-fade-in">{{ $caption }}</caption>
            @endif
            <thead class="top-0 backdrop-blur-sm bg-white/95 z-10 sticky">
            <tr class="border-b border-gray-200 transition-colors hover:bg-gray-50/50">
                @if($showCheckboxes)
                    <th class="h-12 px-4 text-left font-medium text-gray-500 w-[50px]">
                        <input type="checkbox" @click="toggleAllRows">
                    </th>
                @endif

                @foreach($headers as $header)
                    <th class="h-12 px-4 text-left font-medium text-gray-500"
                        style="width: {{ $header['width'] ?? 'auto' }}">
                        {{ $header['label'] }}
                    </th>
                @endforeach

                @if($showActions)
                    <th class="h-12 px-4 text-right font-medium text-gray-500">Actions</th>
                @endif
            </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                {{ $slot }}
            </tbody>
        </table>
    </div>

    <!-- Row Action Dropdown -->
    <div x-show="showActionDropdown" @click.away="showActionDropdown = false" x-cloak
         class="absolute bg-white border border-gray-200 rounded-lg shadow-xl min-w-[220px] overflow-hidden z-50"
         :style="window.innerWidth >= 640 ? `top: ${actionDropdownPosition.top}px; left: ${actionDropdownPosition.left}px;` : ''">
        <div class="divide-y divide-gray-100">
            <div class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">
                Actions
            </div>
            <div class="p-1">
                @foreach($actionOptions as $action => $data)
                    <button @click="handleRowAction('{{ $action }}', selectedRow, $event)"
                            class="w-full text-left px-3 py-1 text-sm hover:bg-gray-100">
                        {{ $data['label'] }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>

</div>
