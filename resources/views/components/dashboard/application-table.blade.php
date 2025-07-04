@props([
    'headers' => [],
    'rows' => [],
    'type' => 'applicant',
    'caption' => 'List of applicants and their status',
    'showCheckboxes' => true,
    'showStatus' => true,
    'showActions' => true,
    'actionOptions' => [
        'view' => [
            'label' => 'View Details',
            'icon' => view('components.icons.view')->render(),
        ],
        'edit' => [
            'label' => 'Edit',
            'icon' => view('components.icons.edit')->render(),
        ],
        'approve' => [
            'label' => 'Approve',
            'icon' => view('components.icons.approve')->render(),
        ],
        'delete' => [
            'label' => 'Delete',
            'icon' => view('components.icons.delete')->render(),
        ],
        'reset_password' => [
            'label' => 'Reset Password',
            'icon' => view('components.icons.reset-password')->render(),
        ],
        'deactivate' => [
            'label' => 'Deactivate',
            'icon' => view('components.icons.deactivate')->render(),
        ],
    ],
    'bulkActions' => [
        'approve' => 'Approve Selected',
        'delete' => 'Delete Selected',
        'export' => 'Export Selected',
    ],
])
<style>
    [x-cloak] {
        display: none !important;
    }

    /* Mobile-specific styles */
    @media (max-width: 640px) {

        /* Larger touch targets for checkboxes */
        .checkbox-wrapper {
            min-width: 44px;
            min-height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Fix dropdown positioning for mobile - UPDATED FOR SMALLER SIZE */
        .row-action-menu {
            position: fixed !important;
            top: 30% !important;
            left: 50% !important;
            transform: translateX(-50%) !important;
            width: 70vw;
            /* Reduced from 90vw */
            max-width: 220px;
            /* Reduced from 300px */
            z-index: 1000;
        }

        /* Smaller buttons in dropdown */
        .row-action-menu button {
            padding: 8px 12px !important;
            /* Reduced padding */
            min-height: 36px;
            /* Reduced height */
            font-size: 0.875rem;
            /* Smaller font */
        }

        /* Smaller icons in dropdown */
        .row-action-menu svg {
            height: 0.875rem;
            width: 0.875rem;
        }

        /* Smaller header area */
        .row-action-menu .px-4.py-3 {
            padding: 0.5rem 0.75rem;
            font-size: 0.7rem;
        }

        /* Header in dropdown */
        .row-action-menu .divide-y>div:first-child {
            padding: 8px 12px;
            /* Smaller header padding */
        }

        /* Increase size of action buttons */
        .row-action-button,
        .bulk-action-button {
            min-height: 44px;
            min-width: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Make table scroll horizontally */
        .table-container {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
    }
</style>


<div x-data="applicationTable({{ count($rows) }})"
    class="w-full bg-white rounded-xl shadow-sm transition-all duration-300 hover:shadow-md border border-gray-100 overflow-hidden">
    <!-- Selection indicator and bulk actions -->
    <div x-show="selectedRows.length > 0" x-cloak x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform -translate-y-4"
        x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform -translate-y-4"
        class="bg-green-50 border-b border-green-100 px-4 py-2 flex items-center justify-between top-0 z-40">
        <div class="text-sm text-green-800 font-medium">
            <span x-text="selectedRows.length"></span> item<span x-show="selectedRows.length !== 1">s</span> selected
        </div>
        <div class="relative">
            <button @click="toggleBulkActionMenu($event)" type="button"
                class="bulk-action-button inline-flex items-center px-3 py-1.5 text-sm font-medium text-green-700 bg-green-100 rounded-md hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-green-500/50 transition-all duration-200">
                Bulk Actions
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                </svg>
            </button>
            <!-- Bulk actions dropdown -->
            <div x-show="showBulkActionDropdown" x-cloak @click.away="showBulkActionDropdown = false"
                class="bulk-action-menu absolute right-0 mt-2 bg-white border border-gray-200 rounded-lg shadow-xl min-w-[180px] overflow-hidden z-50">
                <div class="divide-y divide-gray-100">
                    <div class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">
                        Bulk Actions
                    </div>
                    <div class="p-1">
                        <!-- Export option first -->
                        <button @click="executeBulkAction('export', $event)"
                            class="flex w-full items-center px-4 py-2.5 text-sm text-gray-700 transition-colors hover:bg-green-50 rounded-md my-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-blue-600"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"></path>
                                <polyline points="7 10 12 15 17 10"></polyline>
                                <line x1="12" y1="15" x2="12" y2="3"></line>
                            </svg>
                            {{ $bulkActions['export'] }}
                        </button>

                        @if ($type !== 'admin')
                            <!-- Approve option second (not for admin) -->
                            <button @click="executeBulkAction('approve', $event)"
                                class="flex w-full items-center px-4 py-2.5 text-sm text-gray-700 transition-colors hover:bg-green-50 rounded-md my-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-green-600"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg>
                                {{ $bulkActions['approve'] }}
                            </button>
                        @else
                            <!-- Deactivate option for admin -->
                            <button @click="executeBulkAction('deactivate', $event)"
                                class="flex w-full items-center px-4 py-2.5 text-sm text-gray-700 transition-colors hover:bg-green-50 rounded-md my-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-orange-600"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="8" y1="12" x2="16" y2="12"></line>
                                </svg>
                                Deactivate Selected
                            </button>
                        @endif

                        <!-- Delete option third -->
                        <button @click="executeBulkAction('delete', $event)"
                            class="flex w-full items-center px-4 py-2.5 text-sm text-gray-700 transition-colors hover:bg-green-50 rounded-md my-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-red-600"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 6h18"></path>
                                <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6"></path>
                                <path d="M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2"></path>
                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                <line x1="14" y1="11" x2="14" y1="17"></line>
                            </svg>
                            {{ $bulkActions['delete'] }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row action dropdown -->
    <div x-show="showActionDropdown" @click.away="showActionDropdown = false" x-cloak
        class="row-action-menu absolute bg-white border border-gray-200 rounded-lg shadow-xl min-w-[220px] overflow-hidden z-50"
        :style="window.innerWidth >= 640 ? `top: ${actionDropdownPosition.top}px; left: ${actionDropdownPosition.left}px;` : ''">
        <div class="divide-y divide-gray-100">
            <div class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">
                Actions
            </div>
            <div class="p-1">
                @if ($type === 'admin')
                    <!-- Admin-specific actions -->
                    <button @click="handleRowAction('edit', selectedRow, $event)"
                        class="flex w-full items-center px-4 py-2.5 text-sm text-gray-700 transition-colors hover:bg-green-50 rounded-md my-0.5">
                        {!! $actionOptions['edit']['icon'] !!}
                        {{ $actionOptions['edit']['label'] }}
                    </button>
                    <button @click="handleRowAction('reset_password', selectedRow, $event)"
                        class="flex w-full items-center px-4 py-2.5 text-sm text-gray-700 transition-colors hover:bg-green-50 rounded-md my-0.5">
                        {!! $actionOptions['reset_password']['icon'] !!}
                        {{ $actionOptions['reset_password']['label'] }}
                    </button>
                    <button @click="handleRowAction('deactivate', selectedRow, $event)"
                        class="flex w-full items-center px-4 py-2.5 text-sm text-gray-700 transition-colors hover:bg-green-50 rounded-md my-0.5">
                        {!! $actionOptions['deactivate']['icon'] !!}
                        {{ $actionOptions['deactivate']['label'] }}
                    </button>
                    <button @click="handleRowAction('delete', selectedRow, $event)"
                        class="flex w-full items-center px-4 py-2.5 text-sm text-gray-700 transition-colors hover:bg-green-50 rounded-md my-0.5">
                        {!! $actionOptions['delete']['icon'] !!}
                        {{ $actionOptions['delete']['label'] }}
                    </button>
                @else
                    <!-- Default actions for non-admin types -->
                    <button @click="handleRowAction('view', selectedRow, $event)"
                        class="flex w-full items-center px-4 py-2.5 text-sm text-gray-700 transition-colors hover:bg-green-50 rounded-md my-0.5">
                        {!! $actionOptions['view']['icon'] !!}
                        {{ $actionOptions['view']['label'] }}
                    </button>


                    <button @click="handleRowAction('approve', selectedRow, $event)"
                        class="flex w-full items-center px-4 py-2.5 text-sm text-gray-700 transition-colors hover:bg-green-50 rounded-md my-0.5">
                        {!! $actionOptions['approve']['icon'] !!}
                        {{ $actionOptions['approve']['label'] }}
                    </button>

                    <button @click="handleRowAction('delete', selectedRow, $event)"
                        class="flex w-full items-center px-4 py-2.5 text-sm text-gray-700 transition-colors hover:bg-green-50 rounded-md my-0.5">
                        {!! $actionOptions['delete']['icon'] !!}
                        {{ $actionOptions['delete']['label'] }}
                    </button>
                @endif
            </div>
        </div>
    </div>

    <div class="relative w-full table-container">
        <table class="w-full caption-bottom text-sm">
            @if ($caption)
                <caption class="mt-4 text-sm text-gray-500 animate-fade-in">{{ $caption }}</caption>
            @endif
            <thead class="top-0 backdrop-blur-sm bg-white/95 z-10">
                <tr class="border-b transition-colors hover:bg-gray-50/50">
                    @if ($showCheckboxes)
                        <th class="h-12 px-4 text-left align-middle font-medium text-gray-500 w-[50px]">
                            <div class="checkbox-wrapper">
                                <button type="button" @click.stop="toggleSelectAll($event)"
                                    class="relative inline-flex h-4 w-4 shrink-0 rounded-sm border border-gray-300 transition-all duration-200 hover:border-green-500 hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-500/50"
                                    :class="{ 'bg-green-50 border-green-500': selectAll }">
                                    <span class="absolute inset-0 m-auto transition-opacity"
                                        :class="{ 'opacity-100': selectAll, 'opacity-0': !selectAll }">
                                        <svg class="h-3.5 w-3.5 text-green-600" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    </span>
                                </button>
                            </div>
                        </th>
                    @endif
                    @foreach ($headers as $header)
                        @php
                            $header = is_array($header) ? $header : ['key' => $header, 'label' => $header];
                            $width = $header['width'] ?? 'auto';
                            $key = $header['key'] ?? $header['label'];
                        @endphp
                        <x-table.header-cell :label="$header['label']" />
                    @endforeach
                    @if ($showActions)
                        <x-table.header-cell label="Actions" text_alignment="right"/>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($rows as $index => $row)
                    <tr @click="toggleRow({{ $index }}, $event)"
                        class="group relative cursor-pointer transition-all duration-300 hover:bg-green-50/80 hover:shadow-sm hover:-translate-y-0.5"
                        :class="{ 'bg-green-50/60': isSelected({{ $index }}) }">

                        @if ($showCheckboxes)
                            <td class="p-4 align-middle">
                                <div class="checkbox-wrapper">
                                    <button type="button" @click.stop="toggleCheckbox({{ $index }}, $event)"
                                        class="relative inline-flex h-4 w-4 shrink-0 rounded-sm border border-gray-300 transition-all duration-200 hover:border-green-500 hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-500/50"
                                        :class="{ 'bg-green-50 border-green-500': isSelected({{ $index }}) }">
                                        <span class="absolute inset-0 m-auto transition-opacity"
                                            :class="{ 'opacity-100': isSelected({{ $index }}), 'opacity-0': !isSelected(
                                                    {{ $index }}) }">
                                            <svg class="h-3.5 w-3.5 text-green-600" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                        </span>
                                    </button>
                                </div>
                            </td>
                        @endif

                        @foreach ($headers as $header)
                            @php
                                $header = is_array($header) ? $header : ['key' => $header, 'label' => $header];
                                $key = $header['key'] ?? $header['label'];
                                $value = $row[$key] ?? null;
                            @endphp
                            <td
                                class="p-4 align-middle @if ($key === 'name' || $key === 'owner' || $key === 'vehicle' || $key === 'rfid_tag') font-medium group-hover:text-green-700 @else text-gray-600 @endif">
                                @if ($key === 'status' && isset($row['status']) && is_array($row['status']))

                                    <x-badge type="status" :label="$row['status']['label'] ?? $value" />

                                @elseif($key === 'role' && $type === 'admin')
                                    <x-badge type="role" :label="$value" />
                                @else
                                    {{ $value }}
                                @endif
                            </td>
                        @endforeach
                        @if ($showActions)
                            <td class="p-4 align-middle text-right">
                                <x-row-action-menu :index="$index"/>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('actionDropdown', () => ({
            open: false,
            toggle() {
                this.open = !this.open
            }
        }))
    })
</script>
<!-- Add SweetAlert2 library -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="js/sweet-alerts.js"></script>
<script src="{{ asset('js/components/application-table.js') }}"></script>
