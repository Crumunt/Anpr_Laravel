<div class="w-full bg-white rounded-xl shadow-sm transition-all duration-300 hover:shadow-md border border-gray-100"> {{--removed overflow-hidden on root div--}}

    <!-- Bulk Action Confirmation Modal -->
    @if($showBulkConfirmation)
    <div class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="cancelBulkAction"></div>

            <!-- Modal panel -->
            <div class="relative inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <!-- Icon -->
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full sm:mx-0 sm:h-10 sm:w-10
                            {{ $bulkActionType === 'danger' ? 'bg-red-100' : ($bulkActionType === 'success' ? 'bg-green-100' : 'bg-yellow-100') }}">
                            @if($bulkActionType === 'danger')
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                            @elseif($bulkActionType === 'success')
                                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            @else
                                <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                </svg>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-semibold text-gray-900" id="modal-title">
                                {{ $bulkActionLabel }}
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    {{ $bulkActionDescription }}
                                </p>
                                <div class="mt-3 flex items-center gap-2 text-sm">
                                    <span class="inline-flex items-center justify-center w-6 h-6 bg-gray-100 text-gray-700 rounded-full font-semibold text-xs">
                                        {{ count($selectedRows) }}
                                    </span>
                                    <span class="text-gray-600">
                                        {{ count($selectedRows) === 1 ? 'item' : 'items' }} will be affected
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                    <button type="button"
                            wire:click="confirmBulkAction"
                            wire:loading.attr="disabled"
                            class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm transition-all duration-200 disabled:opacity-50
                                {{ $bulkActionType === 'danger' ? 'bg-red-600 hover:bg-red-700 focus:ring-red-500' : ($bulkActionType === 'success' ? 'bg-green-600 hover:bg-green-700 focus:ring-green-500' : 'bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500') }}">
                        <span wire:loading.remove wire:target="confirmBulkAction">Confirm</span>
                        <span wire:loading wire:target="confirmBulkAction" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing...
                        </span>
                    </button>
                    <button type="button"
                            wire:click="cancelBulkAction"
                            class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 sm:mt-0 sm:w-auto sm:text-sm transition-all duration-200">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Bulk Selection Bar -->
    @if(count($selectedRows))
    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-b border-green-100 px-4 sm:px-6 py-3 flex flex-wrap items-center justify-between gap-2 sticky top-0 z-40 backdrop-blur-sm">
        <div class="flex items-center gap-2 sm:gap-3">
            <div class="flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8 bg-green-600 text-white rounded-full text-xs font-bold">
                {{ count($selectedRows) }}
            </div>
            <span class="text-xs sm:text-sm font-medium text-green-900">
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
                        @foreach ($bulkActionBtns as $action => $config)
                        @php
                            $label = is_array($config) ? ($config['label'] ?? $action) : $config;
                            $icon = is_array($config) ? ($config['icon'] ?? 'bolt') : 'bolt';
                            $color = is_array($config) ? ($config['color'] ?? 'default') : 'default';

                            $colorClasses = match($color) {
                                'danger' => 'text-red-600 hover:bg-red-50 hover:text-red-700',
                                'success' => 'text-green-600 hover:bg-green-50 hover:text-green-700',
                                'warning' => 'text-yellow-600 hover:bg-yellow-50 hover:text-yellow-700',
                                default => 'text-gray-700 hover:bg-green-50 hover:text-green-700',
                            };

                            $dotColor = match($color) {
                                'danger' => 'bg-red-400 group-hover:bg-red-600',
                                'success' => 'bg-green-400 group-hover:bg-green-600',
                                'warning' => 'bg-yellow-400 group-hover:bg-yellow-600',
                                default => 'bg-gray-400 group-hover:bg-green-600',
                            };
                        @endphp
                        <button wire:click="executeBulkAction('{{ $action }}')"
                                @click="isOpen = false"
                                class="w-full text-left px-4 py-2.5 text-sm transition-colors duration-150 flex items-center group {{ $colorClasses }}">
                            <span class="w-1.5 h-1.5 rounded-full mr-3 transition-colors duration-150 {{ $dotColor }}"></span>
                            @if($icon === 'check-circle')
                                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            @elseif($icon === 'x-circle')
                                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            @elseif($icon === 'trash')
                                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            @elseif($icon === 'key')
                                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                            @elseif($icon === 'clock')
                                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            @elseif($icon === 'user-check')
                                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 8l2 2-4 4" />
                                </svg>
                            @elseif($icon === 'user-x')
                                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 12l4 4m0-4l-4 4" />
                                </svg>
                            @else
                                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            @endif
                            {{ $label }}
                        </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Mobile Card View (visible on small screens) -->
    <div class="md:hidden space-y-3 p-4">
        @forelse ($rows as $index => $row)
            @php
                $isSelected = isset($selectedRows[$row['id']]);
            @endphp
            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm {{ $isSelected ? 'ring-2 ring-green-500 bg-green-50/50' : '' }}"
                 wire:key="mobile-row-{{ $row['id'] }}">

                <!-- Card Header with Checkbox and Actions -->
                <div class="flex items-center justify-between mb-3 pb-3 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        @if($showCheckboxes)
                        <input type="checkbox"
                               :checked="{{ $isSelected ? 'true' : 'false' }}"
                               wire:click="toggleRow('{{$row['id']}}')"
                               value="{{ $row['id'] }}"
                               class="w-4 h-4 text-green-600 bg-white border-gray-300 rounded focus:ring-green-500 focus:ring-2 cursor-pointer">
                        @endif
                        @php
                            $primaryKey = $headers[0]['key'] ?? array_key_first($row);
                            $primaryValue = $row[$primaryKey] ?? $row['id'] ?? 'N/A';
                        @endphp
                        <span class="font-semibold text-gray-900 text-sm">{{ is_array($primaryValue) ? ($primaryValue['value'] ?? 'N/A') : $primaryValue }}</span>
                    </div>

                    @if($showActions)
                    <div class="relative" x-data="{open: false}">
                        <button @click="open = !open" type="button"
                                class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                            </svg>
                        </button>
                        <div x-show="open" x-cloak @click.away="open = false"
                             class="absolute right-0 mt-2 w-44 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
                            <div class="py-1">
                                @php
                                    $viewRoute = match($type) {
                                        'admin' => route('admin.admins.show', $row['id']),
                                        'applicant' => route('admin.applicant.show', $row['id']),
                                        default => route('admin.applicant.show', $row['id']),
                                    };
                                @endphp
                                <a href="{{ $viewRoute }}" @click="open = false"
                                   class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    View
                                </a>
                                <button wire:click="editRow('{{ $row['id'] }}')" @click="open = false"
                                        class="w-full flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </button>
                                <hr class="my-1 border-gray-100">
                                <button wire:click="deleteRow('{{ $row['id'] }}')" @click="open = false"
                                        wire:confirm="Are you sure you want to delete this item?"
                                        class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Card Body with Data -->
                <div class="space-y-2">
                    @foreach ($headers as $headerIndex => $header)
                        @if($headerIndex > 0) {{-- Skip first header as it's shown in card header --}}
                            @php
                                $key = $header['key'] ?? $header['label'];
                                $value = $row[$key] ?? null;
                                $label = $header['label'] ?? $key;
                            @endphp
                            <div class="flex items-start justify-between text-sm">
                                <span class="text-gray-500 font-medium">{{ $label }}</span>
                                <span class="text-gray-900 text-right ml-2">
                                    @if(is_array($value))
                                        @if(isset($value['component']))
                                            <x-dynamic-component :component="$value['component']" :value="$value['value']" :type="$value['type'] ?? null" />
                                        @else
                                            {{ $value['value'] ?? '-' }}
                                        @endif
                                    @else
                                        {{ $value ?? '-' }}
                                    @endif
                                </span>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @empty
            <div class="text-center py-12 text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <p class="font-medium">No data available</p>
                <p class="text-sm mt-1">There are no records to display.</p>
            </div>
        @endforelse
    </div>

    <!-- Table Container with Horizontal Scroll (hidden on mobile) -->
    <div class="relative w-full overflow-x-auto hidden md:block"> {{--removed overflow-x-auto on table root div--}}
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
                            <div class="relative inline-block" x-data="{open: false, dropdownStyle: ''}">
                                <button @click="
                                        const rect = $el.getBoundingClientRect();
                                        dropdownStyle = `top: ${rect.bottom + 8}px; left: ${rect.left - 160}px;`;
                                        open = !open;
                                        "
                                        type="button"
                                        class="inline-flex items-center justify-center w-8 h-8 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-green-500/50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                    </svg>
                                </button>

                                <!-- Action Dropdown - Fixed positioning to escape overflow -->
                                <template x-teleport="body">
                                    <div x-show="open"
                                         x-cloak
                                         @click.away="open = false"
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="transform opacity-0 scale-95"
                                         x-transition:enter-end="transform opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="transform opacity-100 scale-100"
                                         x-transition:leave-end="transform opacity-0 scale-95"
                                         class="fixed w-48 bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden z-[9999]"
                                         :style="dropdownStyle">

                                        <div class="py-1">
                                            @php
                                                $viewRoute = match($type) {
                                                    'admin' => route('admin.admins.show', $row['id']),
                                                    'applicant' => route('admin.applicant.show', $row['id']),
                                                    default => route('admin.applicant.show', $row['id']),
                                                };
                                            @endphp
                                            <a      href="{{ $viewRoute }}"
                                                    @click="open = false"
                                                    class="w-full text-left px-4 py-2 text-sm cursor-pointer text-gray-700 hover:bg-gray-50 transition-colors duration-150 flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                View Details
                                            </a>

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
                                </template>
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
