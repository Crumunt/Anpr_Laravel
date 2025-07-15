@props([
    'context' => '',
    'tab' => 'default',
    'rows' => [],
    'type' => 'applicant',
    'caption' => 'List of applicants and their status',
    'showCheckboxes' => true,
    'showStatus' => true,
    'showActions' => true,
    'actionOptions' => [
        'view' => [ 'label' => 'View Details' ],
        'edit' => [ 'label' => 'Edit' ],
        'approve' => [ 'label' => 'Approve' ],
        'delete' => [ 'label' => 'Delete' ],
        'reset-password' => [ 'label' => 'Reset Password' ],
        'deactivate' => [ 'label' => 'Deactivate' ],
    ],
    'bulkActions' => [
        'approve' => 'Approve Selected',
        'delete' => 'Delete Selected',
        'export' => 'Export Selected',
        'deactivate' => 'Deactivate Selected'
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
                        @php
                            $bulkActionBtns = [
                                ['key' => 'export', 'action' => 'bulk-export'],
                                $type !== 'admin'
                                    ? ['key' => 'approve', 'action' => 'approve']
                                    : ['key' => 'deactivate', 'action' => 'deactivate'],
                                ['key' => 'delete', 'action' => 'delete']
                            ];
                        @endphp
                        
                        @foreach ($bulkActionBtns as $btn)
                            @php $click = "executeBulkAction('{$btn['key']}', \$event)"; @endphp
                            
                            <x-ui.action-button
                                :click="$click"
                                :action="$btn['action']"
                                :label="$bulkActions[$btn['key']]"
                                variant="bulk"
                            />
                        
                        @endforeach
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
                    @foreach ($actionOptions as $action => $data)
                        @if (in_array($action,['view', 'approve']))
                            @continue
                        @endif
                        <x-ui.action-button 
                            :action="$action"
                            :label="$data['label']"
                            :click="'handleRowAction(\'' . $action . '\', selectedRow, $event)'"
                        />
                    
                    @endforeach
                @else
                <!-- Default actions for non-admin types -->
                    @foreach ($actionOptions as $action => $data)
                        @if (in_array($action,['edit', 'deactivate', 'reset_password']))
                            @continue
                        @endif
                        <x-ui.action-button 
                            :action="$action"
                            :label="$data['label']"
                            :click="'handleRowAction(\'' . $action . '\', selectedRow, $event)'"
                        />
                    @endforeach
                    
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
                            <x-table.checkbox-cell variant="bulk" />
                            </div>
                        </th>
                    @endif

                    @php
                        $headers = \App\Helpers\ApplicationTableHelper::headerHelper($context, $tab)
                    @endphp

                    @foreach ($headers as $header)
                        @php
                            $header = is_array($header) ? $header : ['key' => $header, 'label' => $header];
                        @endphp
                        <x-table.header-cell 
                            :label="$header['label']" 
                            :width="$header['width'] ?? 'auto'"
                        />
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
                            <x-table.data-cell>
                                <x-table.checkbox-cell :index="$index" :is-selected="'isSelected'"/>
                            </x-table.data-cell>
                        @endif

                        @foreach ($headers as $header)
                            @php
                                $key = $header['key'] ?? $header['label'];
                                $value = $row[$key] ?? null;
                                $isHighlight = in_array($key, ['id','owner','vehicle','gate_pass','vehicles'])
                            @endphp

                            <x-table.data-cell :class="$isHighlight ? 'font-medium group-hover:text-green-700' : 'text-gray-600'">

                                <x-table.cell-renderer :key="$key" :row="$row" :type="$type" :value="$value" />

                            </x-table.data-cell>
                            
                        @endforeach
                        @if ($showActions)
                            <x-table.data-cell class="text-right">
                                <x-row-action-menu :index="$index"/>
                            </x-table.data-cell>
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
