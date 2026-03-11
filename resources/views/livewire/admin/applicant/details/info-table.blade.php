<div x-data="{
    selectedRows: [],
    selectAll: false,
    showFilters: false
}" class="space-y-4">
    <x-admin.applicant.details.card-body :canEdit="false" :cardTitle="$cardTitle">

        {{-- Header Actions --}}
        <x-slot name="tableButton">
            <div class="flex items-center gap-3">
                @if($canCreate)
                <button
                    @click="$dispatch('open-add-modal')"
                    id="add-rfid-btn"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Add Application</span>
                </button>
                @endif
            </div>
        </x-slot>

        {{-- Table Container with overflow handling --}}
        <div class="my-5 mx-5" x-data="{ scrollTop: 0 }">
            <div class="rounded-xl border border-gray-200 shadow-sm bg-white overflow-hidden">
                {{-- Table Info Bar with Enhanced Styling --}}
                <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100/50 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-blue-500 flex items-center justify-center shadow-sm">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">
                                    {{ count($rows) }} {{ count($rows) === 1 ? 'Application' : 'Applications' }}
                                </p>
                                <p class="text-xs text-gray-500">Total records displayed</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Table with Horizontal Scroll --}}
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50/80 backdrop-blur-sm">
                            <tr>
                                @foreach($headers as $header)
                                <th scope="col" class="group px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <button class="flex items-center gap-2 hover:text-gray-900 transition-colors focus:outline-none focus:text-gray-900">
                                        <span>{{ $header }}</span>
                                        <svg class="w-3.5 h-3.5 text-gray-400 opacity-0 group-hover:opacity-100 transition-all duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                        </svg>
                                    </button>
                                </th>
                                @endforeach

                                @if($canApprove || $canDelete || $canCreate)
                                <th scope="col" class="sticky right-0 bg-gray-50/95 backdrop-blur-sm px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider shadow-[-8px_0_12px_-4px_rgba(0,0,0,0.08)] z-10 border-l border-gray-200">
                                    Actions
                                </th>
                                @endif
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($items as $rowIndex => $row)
                            <tr class="hover:bg-blue-50/30 transition-all duration-150 group cursor-pointer">
                                @foreach($row as $key => $cell)
                                @if(!in_array($key, ['actions', 'vehicle_id', 'application_id', 'application_status', 'expiration_status', 'days_until_expiration', 'time_until_expiration', 'is_expired', 'is_expiring_soon', 'can_renew', 'approved_at', 'validity_years', 'renewed_from_vehicle_id', 'renewal_requested_at', 'registration_date', 'previous_gate_pass', 'gate_pass_assignment_count', 'has_pending_renewal']))
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($key === 'status')
                                    <x-ui.badge :label="$cell" />

                                    @elseif($key === 'gate_pass_number')
                                    {{-- Display gate pass number with previous pass and assignment count --}}
                                    <div class="flex flex-col gap-1">
                                        <div class="flex items-center gap-2">
                                            @if($cell !== 'Not yet assigned')
                                            <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-green-700 bg-green-50 px-2.5 py-1 rounded-md">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                                </svg>
                                                {{ $cell }}
                                            </span>
                                            @if(($row['gate_pass_assignment_count'] ?? 0) > 1)
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-700" title="Gate pass has been reassigned {{ $row['gate_pass_assignment_count'] - 1 }} time(s)">
                                                <svg class="w-3 h-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                </svg>
                                                {{ $row['gate_pass_assignment_count'] }}x
                                            </span>
                                            @endif
                                            @else
                                            <span class="text-sm text-gray-400 italic">{{ $cell }}</span>
                                            @endif
                                        </div>
                                        @if($row['previous_gate_pass'] ?? null)
                                        <div class="flex items-center gap-1 text-xs text-gray-500">
                                            <span class="text-gray-400">Previously:</span>
                                            <span class="font-mono text-gray-600 line-through">{{ $row['previous_gate_pass'] }}</span>
                                        </div>
                                        @endif
                                    </div>

                                    @elseif($key === 'application_number')
                                    {{-- Display formatted application number with icon --}}
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-900 bg-gray-100 px-2.5 py-1 rounded-md">
                                            <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                            </svg>
                                            {{ $cell }}
                                        </span>
                                    </div>

                                    @elseif($key === 'name' || $loop->first)
                                    <div class="flex items-center gap-3">
                                        @if(isset($cell['avatar']))
                                        <div class="flex-shrink-0 relative">
                                            <img
                                                class="h-11 w-11 rounded-full object-cover ring-2 ring-white shadow-md transition-transform duration-200 group-hover:scale-105"
                                                src="{{ $cell['avatar'] }}"
                                                alt="{{ $cell['name'] ?? 'Avatar' }}"
                                                loading="lazy">
                                            <div class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 bg-green-400 border-2 border-white rounded-full"></div>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-semibold text-gray-900 truncate group-hover:text-blue-600 transition-colors">
                                                {{ $cell['name'] ?? $cell }}
                                            </p>
                                            @if(isset($cell['email']))
                                            <p class="text-xs text-gray-500 truncate mt-0.5">{{ $cell['email'] }}</p>
                                            @endif
                                        </div>
                                        @else
                                        <span class="text-sm font-medium text-gray-900">{{ $cell }}</span>
                                        @endif
                                    </div>

                                    @elseif($key === 'date')
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-900">{{ $cell }}</span>
                                        @if(isset($row['time']))
                                        <span class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $row['time'] }}
                                        </span>
                                        @endif
                                    </div>

                                    @elseif($key === 'amount' || $key === 'price')
                                    <span class="text-sm font-semibold {{ isset($cell['change']) && $cell['change'] > 0 ? 'text-green-600' : (isset($cell['change']) && $cell['change'] < 0 ? 'text-red-600' : 'text-gray-900') }}">
                                        {{ is_array($cell) ? $cell['value'] : $cell }}
                                    </span>

                                    @elseif($key === 'expires_at')
                                    @if($cell)
                                        <div class="flex flex-col gap-1">
                                            <span class="text-sm text-gray-700">{{ $cell }}</span>
                                            @if(isset($row['expiration_status']))
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $row['expiration_status']['class'] ?? 'bg-gray-100 text-gray-800' }}">
                                                    @if($row['is_expired'] ?? false)
                                                        <i class="fas fa-exclamation-circle mr-1"></i>Expired
                                                    @elseif($row['is_expiring_soon'] ?? false)
                                                        <i class="fas fa-clock mr-1"></i>{{ $row['time_until_expiration'] ?? ($row['days_until_expiration'] ?? 0) . ' days left' }}
                                                    @else
                                                        <i class="fas fa-check-circle mr-1"></i>Active
                                                    @endif
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400">Not set</span>
                                    @endif

                                    @elseif($key === 'is_renewal')
                                    @if($row['has_pending_renewal'] ?? false)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800">
                                            <i class="fas fa-hourglass-half mr-1"></i>Pending Renewal
                                        </span>
                                    @elseif($cell)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-cyan-100 text-cyan-800">
                                            <i class="fas fa-sync mr-1"></i>Renewed
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-400">-</span>
                                    @endif

                                    @else
                                    <span class="text-sm text-gray-700">{{ $cell }}</span>
                                    @endif
                                </td>
                                @endif
                                @endforeach

                                @if($canApprove || $canDelete || $canCreate)
                                <td class="sticky right-0 bg-white group-hover:bg-blue-50/30 px-6 py-4 whitespace-nowrap text-right text-sm font-medium shadow-[-8px_0_12px_-4px_rgba(0,0,0,0.08)] z-10 border-l border-gray-100 transition-colors duration-150">
                                    @if ($type === 'applicant')
                                    @if($canApprove || $canDelete)
                                    <div x-data="{ open: false }" class="relative inline-block text-left">
                                        <button
                                            x-ref="menuButton"
                                            @click="open = !open"
                                            @keydown.escape.window="open = false"
                                            type="button"
                                            class="inline-flex items-center justify-center w-9 h-9 text-gray-400 hover:text-gray-700 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200"
                                            aria-label="More options"
                                            aria-haspopup="true"
                                            :aria-expanded="open">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                            </svg>
                                        </button>

                                        {{-- Enhanced Dropdown Menu --}}
                                        <template x-teleport="body">
                                            <div
                                                x-show="open"
                                                @click.outside="open = false"
                                                x-cloak
                                                x-transition:enter="transition ease-out duration-200"
                                                x-transition:enter-start="transform opacity-0 scale-95 translate-y-2"
                                                x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                                                x-transition:leave="transition ease-in duration-150"
                                                x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
                                                x-transition:leave-end="transform opacity-0 scale-95 translate-y-2"
                                                x-anchor.bottom-end.offset.8="$refs.menuButton"
                                                class="w-72 rounded-xl shadow-2xl bg-white ring-1 ring-black/10 divide-y divide-gray-100 overflow-hidden"
                                                style="position: fixed; z-index: 9999;"
                                                role="menu"
                                                aria-orientation="vertical">

                                                @if($canApprove)
                                                <div class="p-1.5">
                                                    {{-- Approve --}}
                                                    <button
                                                        wire:click="applicationApprove('{{ $row['application_id'] ?? '' }}')"
                                                        wire:loading.attr="disabled"
                                                        @click="open = false"
                                                        class="w-full text-left flex items-center px-3 py-3 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 rounded-lg group/item"
                                                        role="menuitem">
                                                        <span class="flex items-center justify-center w-10 h-10 mr-3 rounded-lg bg-green-100 group-hover/item:bg-green-200 group-hover/item:scale-110 transition-all duration-200">
                                                            <svg
                                                                wire:loading.remove
                                                                wire:target="applicationApprove('{{ $row['application_id'] ?? '' }}')"
                                                                class="w-5 h-5 text-green-600"
                                                                fill="none"
                                                                stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                            <svg
                                                                wire:loading
                                                                wire:target="applicationApprove('{{ $row['application_id'] ?? '' }}')"
                                                                class="animate-spin w-5 h-5 text-green-600"
                                                                fill="none"
                                                                viewBox="0 0 24 24">
                                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                            </svg>
                                                        </span>
                                                        <div class="flex-1">
                                                            <p class="font-semibold" wire:loading.remove wire:target="applicationApprove('{{ $row['application_id'] ?? '' }}')">Approve Application</p>
                                                            <p class="font-semibold" wire:loading wire:target="applicationApprove('{{ $row['application_id'] ?? '' }}')">Approving...</p>
                                                            <p class="text-xs text-gray-500 mt-0.5">Grant access to this applicant</p>
                                                        </div>
                                                    </button>

                                                    {{-- Reject --}}
                                                    <button
                                                        wire:click="rejectApplication('{{ $row['application_id'] ?? '' }}')"
                                                        wire:loading.attr="disabled"
                                                        @click="open = false"
                                                        class="w-full text-left flex items-center px-3 py-3 text-sm text-gray-700 hover:bg-red-50 hover:text-red-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 rounded-lg group/item"
                                                        role="menuitem">
                                                        <span class="flex items-center justify-center w-10 h-10 mr-3 rounded-lg bg-red-100 group-hover/item:bg-red-200 group-hover/item:scale-110 transition-all duration-200">
                                                            <svg
                                                                wire:loading.remove
                                                                wire:target="rejectApplication('{{ $row['application_id'] ?? '' }}')"
                                                                class="w-5 h-5 text-red-600"
                                                                fill="none"
                                                                stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                            <svg
                                                                wire:loading
                                                                wire:target="rejectApplication('{{ $row['application_id'] ?? '' }}')"
                                                                class="animate-spin w-5 h-5 text-red-600"
                                                                fill="none"
                                                                viewBox="0 0 24 24">
                                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                            </svg>
                                                        </span>
                                                        <div class="flex-1">
                                                            <p class="font-semibold" wire:loading.remove wire:target="rejectApplication('{{ $row['application_id'] ?? '' }}')">Reject Application</p>
                                                            <p class="font-semibold" wire:loading wire:target="rejectApplication('{{ $row['application_id'] ?? '' }}')">Rejecting...</p>
                                                            <p class="text-xs text-gray-500 mt-0.5">Deny access for this applicant</p>
                                                        </div>
                                                    </button>
                                                </div>
                                                @endif

                                                @if($canDelete)
                                                <div class="p-1.5">
                                                    {{-- Delete --}}
                                                    <button
                                                        wire:click="deleteApplication('{{ $row['application_id'] ?? '' }}')"
                                                        wire:confirm="Are you sure you want to delete this application? This action cannot be undone."
                                                        wire:loading.attr="disabled"
                                                        @click="open = false"
                                                        class="w-full text-left flex items-center px-3 py-3 text-sm text-red-600 hover:bg-red-50 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 rounded-lg group/item"
                                                        role="menuitem">
                                                        <span class="flex items-center justify-center w-10 h-10 mr-3 rounded-lg bg-red-100 group-hover/item:bg-red-200 group-hover/item:scale-110 transition-all duration-200">
                                                            <svg
                                                                wire:loading.remove
                                                                wire:target="deleteApplication('{{ $row['application_id'] ?? '' }}')"
                                                                class="w-5 h-5 text-red-600"
                                                                fill="none"
                                                                stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                            <svg
                                                                wire:loading
                                                                wire:target="deleteApplication('{{ $row['application_id'] ?? '' }}')"
                                                                class="animate-spin w-5 h-5 text-red-600"
                                                                fill="none"
                                                                viewBox="0 0 24 24">
                                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                            </svg>
                                                        </span>
                                                        <div class="flex-1">
                                                            <p class="font-semibold" wire:loading.remove wire:target="deleteApplication('{{ $row['application_id'] ?? '' }}')">Delete Application</p>
                                                            <p class="font-semibold" wire:loading wire:target="deleteApplication('{{ $row['application_id'] ?? '' }}')">Deleting...</p>
                                                            <p class="text-xs text-gray-500 mt-0.5">Permanently remove this record</p>
                                                        </div>
                                                    </button>
                                                </div>
                                                @endif
                                            </div>
                                        </template>
                                    </div>
                                    @else
                                    {{-- View Only - No Actions Available --}}
                                    <span class="text-xs text-gray-400 italic">View only</span>
                                    @endif

                                    @elseif ($type === 'vehicle')
                                    @php
                                        $appStatus = $row['application_status'] ?? 'pending';
                                        $hasPendingRenewal = $row['has_pending_renewal'] ?? false;
                                        $canAssignPass = $appStatus === 'approved' && $canCreate && !$hasPendingRenewal;
                                    @endphp

                                    @if($hasPendingRenewal)
                                    {{-- Show locked state when renewal is pending --}}
                                    <span class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-amber-600 bg-amber-50 rounded-lg cursor-not-allowed border border-amber-200" title="Gate pass reassignment locked while renewal is pending">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                        <span>Renewal Pending</span>
                                    </span>
                                    @elseif($canAssignPass)
                                    <button
                                        @click="$dispatch('open-assign-modal', { applicationId: '{{ $row['vehicle_id'] ?? '' }}', vehicleNumber: '{{ $row['gate_pass_number'] === 'Not yet assigned' ? '' : $row['gate_pass_number'] }}' })"
                                        class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                        @if($row['gate_pass_number'] !== 'Not yet assigned')
                                        <span>Re-assign</span>
                                        @else
                                        <span>Assign Pass</span>
                                        @endif
                                    </button>
                                    @else
                                    {{-- Show disabled state or status message when application not approved --}}
                                    <span class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed" title="Application must be approved before assigning a gate pass">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                        <span>{{ $appStatus === 'rejected' ? 'Rejected' : 'Pending Approval' }}</span>
                                    </span>
                                    @endif
                                    @endif
                                </td>
                                @endif
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ count($headers) + ($canApprove || $canDelete || $canCreate ? 1 : 0) }}" class="px-6 py-20">
                                    <div class="flex flex-col items-center justify-center text-center">
                                        <div class="w-24 h-24 mb-5 rounded-2xl bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center shadow-lg">
                                            <svg class="w-12 h-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <h3 class="text-xl font-bold text-gray-900 mb-2">No applications found</h3>
                                        <p class="text-sm text-gray-500 mb-6 max-w-md leading-relaxed">
                                            There are no applications to display at the moment. Try adjusting your filters or create a new application to get started.
                                        </p>
                                        @if($canCreate)
                                        <button class="inline-flex items-center gap-2 px-5 py-3 text-sm font-semibold text-white bg-gradient-to-r from-green-600 to-green-700 rounded-lg hover:from-green-700 hover:to-green-800 shadow-md hover:shadow-lg transition-all duration-200 transform hover:scale-105">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                            <span>Create New Application</span>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Enhanced Pagination Footer with Scroll Fix --}}
                @livewire('table.partials.pagination', [
                        'targetTable' => $tableId
                    ], key('pagination-' . $tableId))
            </div>
        </div>

        {{-- Custom Scrollbar Styles --}}
        <style>
            .custom-scrollbar::-webkit-scrollbar {
                height: 8px;
            }

            .custom-scrollbar::-webkit-scrollbar-track {
                background: #f1f5f9;
                border-radius: 4px;
            }

            .custom-scrollbar::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 4px;
                transition: background 0.2s;
            }

            .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                background: #94a3b8;
            }

            [x-cloak] {
                display: none !important;
            }
        </style>

    </x-admin.applicant.details.card-body>


    @if($type === 'applicant')
    <div
        x-data="{
            addModalOpen: false,
            errors: {}
        }"
        @open-add-modal.window="
            addModalOpen = true;
            $wire.currentStep = 1;
            errors = {};
        "
        @keydown.escape.window="addModalOpen = false">

        <template x-teleport="body">
            <div
                x-show="addModalOpen"
                x-cloak
                style="display: none;"
                class="fixed inset-0 z-[9999] overflow-y-auto"
                aria-labelledby="modal-title"
                role="dialog"
                aria-modal="true">

        {{-- Backdrop --}}
        <div
            x-show="addModalOpen"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity"
            @click="addModalOpen = false"
            aria-hidden="true"></div>

        {{-- Modal Container --}}
        <div class="flex min-h-full items-center justify-center p-4">
            <div
                x-show="addModalOpen"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                @click.stop
                class="relative w-full max-w-3xl transform overflow-hidden rounded-2xl bg-white shadow-2xl transition-all">

                {{-- Modal Header --}}
                <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 px-6 py-5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-white/20 backdrop-blur-sm">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-white" id="modal-title">
                                    Add Application
                                </h3>
                                <p class="text-sm text-emerald-100 mt-0.5">
                                    Step <span x-text="$wire.currentStep"></span> of 3:
                                    <span x-text="$wire.currentStep === 1 ? 'Vehicle Details' : $wire.currentStep === 2 ? 'Upload Documents' : 'Review'"></span>
                                </p>
                            </div>
                        </div>
                        <button
                            wire:click="resetForm"
                            @click="addModalOpen = false"
                            type="button"
                            class="rounded-lg p-2 text-white/80 hover:bg-white/20 hover:text-white transition-colors focus:outline-none focus:ring-2 focus:ring-white/50"
                            aria-label="Close modal">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Progress Bar --}}
                <div class="px-6 pt-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-medium" :class="$wire.currentStep >= 1 ? 'text-emerald-600' : 'text-gray-400'">Vehicle Info</span>
                        <span class="text-xs font-medium" :class="$wire.currentStep >= 2 ? 'text-emerald-600' : 'text-gray-400'">Documents</span>
                        <span class="text-xs font-medium" :class="$wire.currentStep >= 3 ? 'text-emerald-600' : 'text-gray-400'">Review</span>
                    </div>
                    <div class="flex gap-2">
                        <div class="h-1 flex-1 rounded transition-colors" :class="$wire.currentStep >= 1 ? 'bg-emerald-600' : 'bg-gray-200'"></div>
                        <div class="h-1 flex-1 rounded transition-colors" :class="$wire.currentStep >= 2 ? 'bg-emerald-600' : 'bg-gray-200'"></div>
                        <div class="h-1 flex-1 rounded transition-colors" :class="$wire.currentStep >= 3 ? 'bg-emerald-600' : 'bg-gray-200'"></div>
                    </div>
                </div>

                <form wire:submit.prevent="submitForm">
                    {{-- Modal Body --}}
                    <div class="px-6 py-6 max-h-[60vh] overflow-y-auto">

                        {{-- Step 1: Vehicle Details --}}
                        <div x-show="$wire.currentStep === 1" x-transition>
                            <div class="mb-6">
                                <div class="flex items-start gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-lg">
                                    <svg class="w-5 h-5 text-emerald-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-emerald-900">Vehicle Information</p>
                                        <p class="text-sm text-emerald-700 mt-1">
                                            Please provide accurate details about the vehicle for this application.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                                        Vehicle Type <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        wire:model="vehicle_type"
                                        placeholder="e.g., Sedan, SUV, Motorcycle"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm px-4 py-3 transition-colors">
                                    @error('vehicle_type') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                                        Plate Number <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        wire:model="plate_number"
                                        placeholder="ABC 1234"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm px-4 py-3 transition-colors">
                                    @error('plate_number') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                                        Make <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        wire:model="make"
                                        placeholder="e.g., Toyota, Honda"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm px-4 py-3 transition-colors">
                                    @error('make') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                                        Model <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        wire:model="model"
                                        placeholder="e.g., Vios, Civic"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm px-4 py-3 transition-colors">
                                    @error('model') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                                        Color <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        wire:model="color"
                                        placeholder="e.g., White, Black"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm px-4 py-3 transition-colors">
                                    @error('color') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                                        Year <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="number"
                                        wire:model="year"
                                        placeholder="2024"
                                        min="1900"
                                        max="2100"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm px-4 py-3 transition-colors">
                                    @error('year') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Step 2: Documents --}}
                        <div x-show="$wire.currentStep === 2" x-transition>

                            {{-- Error Summary --}}
                            @if($errors->any() && $currentStep === 2)
                            <div class="mb-6">
                                <div class="flex items-start gap-3 p-4 bg-red-50 border border-red-200 rounded-lg">
                                    <svg class="w-5 h-5 text-red-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-red-900">Please upload all required documents</p>
                                        <ul class="text-sm text-red-700 mt-2 list-disc list-inside">
                                            @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="mb-6">
                                <div class="flex items-start gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-lg">
                                    <svg class="w-5 h-5 text-emerald-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-emerald-900">Required Documents</p>
                                        <p class="text-sm text-emerald-700 mt-1">
                                            Upload all necessary documents. Accepted formats: PDF, PNG, JPG (max 10MB each)
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-6">
                                {{-- Vehicle Registration --}}
                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                                        Vehicle Registration <span class="text-red-500">*</span>
                                    </label>
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 hover:border-emerald-500 transition-colors">
                                        <input type="file" wire:model="vehicle_registration" id="vehicleRegistration" class="hidden" accept=".pdf,.png,.jpg,.jpeg" multiple>
                                        <label for="vehicleRegistration" class="cursor-pointer flex flex-col items-center gap-2">
                                            <svg class="h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <p class="text-sm font-medium text-gray-700">Click to upload vehicle registration</p>
                                            <p class="text-xs text-gray-500">PDF, PNG, JPG up to 10MB</p>
                                        </label>
                                    </div>

                                    {{-- Loading Spinner --}}
                                    <div wire:loading wire:target="vehicle_registration" class="mt-3">
                                        <div class="flex items-center gap-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                            <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <span class="text-sm font-medium text-blue-700">Uploading vehicle registration...</span>
                                        </div>
                                    </div>

                                    @error('vehicle_registration') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                                    @if($vehicle_registration)
                                    <div class="mt-3 space-y-2" wire:loading.remove wire:target="vehicle_registration">
                                        @foreach ($vehicle_registration as $index => $file)
                                        <div class="flex items-center justify-between gap-2 p-2 bg-emerald-50 border border-emerald-200 rounded-lg">
                                            <div class="flex items-center gap-2">
                                                <svg class="h-5 w-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                                <span class="text-sm text-gray-700 truncate">{{ $file->getClientOriginalName() }}</span>
                                            </div>
                                            <button type="button" wire:click="removeVehicleFile('vehicle_registration', {{ $index }})" class="text-red-500 hover:text-red-700 p-1 rounded hover:bg-red-50 transition-colors" title="Remove file">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>

                                {{-- Driver's License --}}
                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                                        Driver's License <span class="text-red-500">*</span>
                                    </label>
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 hover:border-emerald-500 transition-colors">
                                        <input type="file" wire:model="license" id="license" class="hidden" accept=".pdf,.png,.jpg,.jpeg" multiple>
                                        <label for="license" class="cursor-pointer flex flex-col items-center gap-2">
                                            <svg class="h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <p class="text-sm font-medium text-gray-700">Click to upload driver's license</p>
                                            <p class="text-xs text-gray-500">PDF, PNG, JPG up to 10MB</p>
                                        </label>
                                    </div>

                                    {{-- Loading Spinner --}}
                                    <div wire:loading wire:target="license" class="mt-3">
                                        <div class="flex items-center gap-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                            <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <span class="text-sm font-medium text-blue-700">Uploading driver's license...</span>
                                        </div>
                                    </div>

                                    @error('license') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                                    @if($license)
                                    <div class="mt-3 space-y-2" wire:loading.remove wire:target="license">
                                        @foreach ($license as $index => $file)
                                        <div class="flex items-center justify-between gap-2 p-2 bg-emerald-50 border border-emerald-200 rounded-lg">
                                            <div class="flex items-center gap-2">
                                                <svg class="h-5 w-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                                <span class="text-sm text-gray-700 truncate">{{ $file->getClientOriginalName() }}</span>
                                            </div>
                                            <button type="button" wire:click="removeVehicleFile('license', {{ $index }})" class="text-red-500 hover:text-red-700 p-1 rounded hover:bg-red-50 transition-colors" title="Remove file">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>

                                {{-- Proof of Identification --}}
                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                                        Proof of Identification <span class="text-red-500">*</span>
                                    </label>
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 hover:border-emerald-500 transition-colors">
                                        <input type="file" wire:model="proof_of_identification" id="proofOfId" class="hidden" accept=".pdf,.png,.jpg,.jpeg" multiple>
                                        <label for="proofOfId" class="cursor-pointer flex flex-col items-center gap-2">
                                            <svg class="h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <p class="text-sm font-medium text-gray-700">Click to upload proof of identification</p>
                                            <p class="text-xs text-gray-500">CLSU ID, National ID, Passport - PDF, PNG, JPG up to 10MB</p>
                                        </label>
                                    </div>

                                    {{-- Loading Spinner --}}
                                    <div wire:loading wire:target="proof_of_identification" class="mt-3">
                                        <div class="flex items-center gap-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                            <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <span class="text-sm font-medium text-blue-700">Uploading proof of identification...</span>
                                        </div>
                                    </div>

                                    @error('proof_of_identification') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                                    @if($proof_of_identification)
                                    <div class="mt-3 space-y-2" wire:loading.remove wire:target="proof_of_identification">
                                        @foreach ($proof_of_identification as $index => $file)
                                        <div class="flex items-center justify-between gap-2 p-2 bg-emerald-50 border border-emerald-200 rounded-lg">
                                            <div class="flex items-center gap-2">
                                                <svg class="h-5 w-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                                <span class="text-sm text-gray-700 truncate">{{ $file->getClientOriginalName() }}</span>
                                            </div>
                                            <button type="button" wire:click="removeVehicleFile('proof_of_identification', {{ $index }})" class="text-red-500 hover:text-red-700 p-1 rounded hover:bg-red-50 transition-colors" title="Remove file">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Step 3: Review --}}
                        <div x-show="$wire.currentStep === 3" x-transition>
                            <div class="mb-6">
                                <div class="flex items-start gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-lg">
                                    <svg class="w-5 h-5 text-emerald-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-emerald-900">Review Information</p>
                                        <p class="text-sm text-emerald-700 mt-1">
                                            Please review all details before submitting the application.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                {{-- Vehicle Information --}}
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <h4 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                        <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                                        </svg>
                                        Vehicle Information
                                    </h4>
                                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-3">
                                        <div>
                                            <dt class="text-xs text-gray-500 font-medium">Vehicle Type</dt>
                                            <dd class="text-sm text-gray-900 mt-1">{{ $vehicle_type ?? 'N/A' }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-xs text-gray-500 font-medium">Plate Number</dt>
                                            <dd class="text-sm text-gray-900 mt-1">{{ $plate_number ?? 'N/A' }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-xs text-gray-500 font-medium">Make</dt>
                                            <dd class="text-sm text-gray-900 mt-1">{{ $make ?? 'N/A' }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-xs text-gray-500 font-medium">Model</dt>
                                            <dd class="text-sm text-gray-900 mt-1">{{ $model ?? 'N/A' }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-xs text-gray-500 font-medium">Color</dt>
                                            <dd class="text-sm text-gray-900 mt-1">{{ $color ?? 'N/A' }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-xs text-gray-500 font-medium">Year</dt>
                                            <dd class="text-sm text-gray-900 mt-1">{{ $year ?? 'N/A' }}</dd>
                                        </div>
                                    </dl>
                                </div>

                                {{-- Documents --}}
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <h4 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                        <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Uploaded Documents
                                    </h4>
                                    <ul class="space-y-2">
                                        @if($vehicle_registration)
                                        <li class="text-sm">
                                            <span class="font-medium text-gray-700">Vehicle Registration:</span>
                                            <span class="text-gray-600"> {{ count($vehicle_registration) }} file(s)</span>
                                        </li>
                                        @endif
                                        @if($license)
                                        <li class="text-sm">
                                            <span class="font-medium text-gray-700">Driver's License:</span>
                                            <span class="text-gray-600"> {{ count($license) }} file(s)</span>
                                        </li>
                                        @endif
                                        @if($proof_of_identification)
                                        <li class="text-sm">
                                            <span class="font-medium text-gray-700">Proof of ID:</span>
                                            <span class="text-gray-600"> {{ count($proof_of_identification) }} file(s)</span>
                                        </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="bg-gray-50 px-6 py-4 flex items-center justify-between gap-3 border-t border-gray-200">
                        <div>
                            <button
                                x-show="$wire.currentStep > 1"
                                @click.prevent="$wire.prevStep()"
                                type="button"
                                class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all shadow-sm">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                    <span>Back</span>
                                </span>
                            </button>
                        </div>
                        <div class="flex gap-2">
                            <button
                                wire:click="resetForm"
                                @click="addModalOpen = false"
                                type="button"
                                class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all shadow-sm">
                                Cancel
                            </button>
                            <button
                                x-show="$wire.currentStep < 3"
                                wire:click="nextStep"
                                type="button"
                                class="px-5 py-2.5 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all shadow-sm hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed"
                                wire:loading.attr="disabled"
                                wire:target="nextStep">
                                <span class="flex items-center gap-2">
                                    <span wire:loading.remove wire:target="nextStep">Next</span>
                                    <span wire:loading wire:target="nextStep">Validating...</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" wire:loading.remove wire:target="nextStep">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" wire:loading wire:target="nextStep">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </span>
                            </button>
                            <button
                                @click="addModalOpen = false"
                                x-show="$wire.currentStep === 3"
                                type="submit"
                                class="px-5 py-2.5 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all shadow-sm hover:shadow-md">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Submit Application</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
        </template>
    </div>
    {{-- Assign Vehicle Modal --}}
    @elseif ($type === 'vehicle')
    <div
        x-data="{
                assignModalOpen: false,
                applicationId: '',
                vehicleNumber: '',
                errors: {}
            }"
        @open-assign-modal.window="
                assignModalOpen = true;
                applicationId = $event.detail.applicationId;
                vehicleNumber = $event.detail.vehicleNumber;
                errors = {};
            "
        x-show="assignModalOpen"
        x-cloak
        style="display: none;"
        class="fixed inset-0 z-[9999] overflow-y-auto"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true"
        @keydown.escape.window="assignModalOpen = false">

        {{-- Backdrop --}}
        <div
            x-show="assignModalOpen"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity"
            @click="assignModalOpen = false"
            aria-hidden="true"></div>

        {{-- Modal Container --}}
        <div class="flex min-h-full items-center justify-center p-4">
            <div
                x-show="assignModalOpen"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                @click.stop
                class="relative w-full max-w-lg transform overflow-hidden rounded-2xl bg-white shadow-2xl transition-all">

                {{-- Modal Header --}}
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-white/20 backdrop-blur-sm">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-white" id="modal-title">
                                    Assign Vehicle Number
                                </h3>
                                <p class="text-sm text-blue-100 mt-0.5">
                                    Assign a gate pass number to this application
                                </p>
                            </div>
                        </div>
                        <button
                            @click="assignModalOpen = false"
                            type="button"
                            class="rounded-lg p-2 text-white/80 hover:bg-white/20 hover:text-white transition-colors focus:outline-none focus:ring-2 focus:ring-white/50"
                            aria-label="Close modal">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Modal Body --}}
                <div class="px-6 py-6">
                    <div class="mb-6">
                        <div class="flex items-start gap-3 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-blue-900">Important</p>
                                <p class="text-sm text-blue-700 mt-1">
                                    This number will be permanently assigned to the applicant's vehicle and cannot be changed later.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="vehicle-number-input" class="block text-sm font-semibold text-gray-900 mb-2">
                            Gate Pass Number <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input
                                type="text"
                                id="vehicle-number-input"
                                x-model="vehicleNumber"
                                placeholder="e.g., ABC-1234"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm px-4 py-3 pr-10 transition-colors"
                                @keydown.enter.prevent="
                                        if (vehicleNumber.trim()) {
                                            $wire.assignVehicleNumber(applicationId, vehicleNumber);
                                            assignModalOpen = false;
                                        }
                                    "
                                x-ref="vehicleInput"
                                @input="errors = {}">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                            </div>
                        </div>
                        <p class="mt-2 text-xs text-gray-500 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Enter the unique vehicle identification number</span>
                        </p>
                        <div x-show="errors.vehicleNumber" class="mt-2 text-sm text-red-600" x-cloak>
                            <p x-text="errors.vehicleNumber"></p>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="bg-gray-50 px-6 py-4 flex items-center justify-between gap-3 border-t border-gray-200">
                    <button
                        @click="assignModalOpen = false"
                        type="button"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all shadow-sm">
                        Cancel
                    </button>
                    <button
                        @click="
                                if (vehicleNumber.trim()) {
                                    console.log('happening', applicationId, vehicleNumber)
                                    $wire.assignVehicleNumber(applicationId, vehicleNumber);
                                    assignModalOpen = false;
                                } else {
                                    errors = { vehicleNumber: 'Please enter a vehicle number' };
                                }
                            "
                        :disabled="!vehicleNumber.trim()"
                        type="button"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-sm hover:shadow-md disabled:hover:shadow-sm">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Assign Number</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Vehicle Application Modal --}}
    <div
        x-data="{
            vehicleAddModalOpen: false,
            errors: {}
        }"
        @open-add-modal.window="
            vehicleAddModalOpen = true;
            $wire.currentStep = 1;
            errors = {};
        "
        @close-vehicle-add-modal.window="vehicleAddModalOpen = false"
        @keydown.escape.window="vehicleAddModalOpen = false">

        <template x-teleport="body">
            <div
                x-show="vehicleAddModalOpen"
                x-cloak
                style="display: none;"
                class="fixed inset-0 z-[9999] overflow-y-auto"
                aria-labelledby="modal-title"
                role="dialog"
                aria-modal="true">

        {{-- Backdrop --}}
        <div
            x-show="vehicleAddModalOpen"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity"
            @click="vehicleAddModalOpen = false"
            aria-hidden="true"></div>

        {{-- Modal Container --}}
        <div class="flex min-h-full items-center justify-center p-4">
            <div
                x-show="vehicleAddModalOpen"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                @click.stop
                class="relative w-full max-w-3xl transform overflow-hidden rounded-2xl bg-white shadow-2xl transition-all">

                {{-- Modal Header --}}
                <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 px-6 py-5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-white/20 backdrop-blur-sm">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-white" id="modal-title">
                                    Add New Application
                                </h3>
                                <p class="text-sm text-emerald-100 mt-0.5">
                                    Step <span x-text="$wire.currentStep"></span> of 3:
                                    <span x-text="$wire.currentStep === 1 ? 'Vehicle Details' : $wire.currentStep === 2 ? 'Upload Documents' : 'Review'"></span>
                                </p>
                            </div>
                        </div>
                        <button
                            wire:click="resetForm"
                            @click="vehicleAddModalOpen = false"
                            type="button"
                            class="rounded-lg p-2 text-white/80 hover:bg-white/20 hover:text-white transition-colors focus:outline-none focus:ring-2 focus:ring-white/50"
                            aria-label="Close modal">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Applicant Type Info --}}
                @if($applicantType)
                <div class="px-6 pt-4">
                    <div class="flex items-center gap-2 px-3 py-2 bg-blue-50 border border-blue-200 rounded-lg text-sm">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="text-blue-700">Applicant Type: <strong>{{ $applicantType->label }}</strong></span>
                    </div>
                </div>
                @endif

                {{-- Progress Bar --}}
                <div class="px-6 pt-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-medium" :class="$wire.currentStep >= 1 ? 'text-emerald-600' : 'text-gray-400'">Vehicle Info</span>
                        <span class="text-xs font-medium" :class="$wire.currentStep >= 2 ? 'text-emerald-600' : 'text-gray-400'">Documents</span>
                        <span class="text-xs font-medium" :class="$wire.currentStep >= 3 ? 'text-emerald-600' : 'text-gray-400'">Review</span>
                    </div>
                    <div class="flex gap-2">
                        <div class="h-1 flex-1 rounded transition-colors" :class="$wire.currentStep >= 1 ? 'bg-emerald-600' : 'bg-gray-200'"></div>
                        <div class="h-1 flex-1 rounded transition-colors" :class="$wire.currentStep >= 2 ? 'bg-emerald-600' : 'bg-gray-200'"></div>
                        <div class="h-1 flex-1 rounded transition-colors" :class="$wire.currentStep >= 3 ? 'bg-emerald-600' : 'bg-gray-200'"></div>
                    </div>
                </div>

                <form wire:submit.prevent="submitVehicleApplication">
                    {{-- Modal Body --}}
                    <div class="px-6 py-6 max-h-[60vh] overflow-y-auto">

                        {{-- Step 1: Vehicle Details --}}
                        <div x-show="$wire.currentStep === 1" x-transition>
                            <div class="mb-6">
                                <div class="flex items-start gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-lg">
                                    <svg class="w-5 h-5 text-emerald-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-emerald-900">Vehicle Information</p>
                                        <p class="text-sm text-emerald-700 mt-1">
                                            Please provide accurate details about the vehicle for this application.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                                        Vehicle Type <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        wire:model="vehicle_type"
                                        placeholder="e.g., Sedan, SUV, Motorcycle"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm px-4 py-3 transition-colors">
                                    @error('vehicle_type') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                                        Plate Number <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        wire:model="plate_number"
                                        placeholder="ABC 1234"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm px-4 py-3 transition-colors">
                                    @error('plate_number') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                                        Make <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        wire:model="make"
                                        placeholder="e.g., Toyota, Honda"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm px-4 py-3 transition-colors">
                                    @error('make') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                                        Model <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        wire:model="model"
                                        placeholder="e.g., Vios, Civic"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm px-4 py-3 transition-colors">
                                    @error('model') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                                        Color <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        wire:model="color"
                                        placeholder="e.g., White, Black"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm px-4 py-3 transition-colors">
                                    @error('color') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                                        Year <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="number"
                                        wire:model="year"
                                        placeholder="{{ date('Y') }}"
                                        min="1900"
                                        max="{{ date('Y') + 1 }}"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm px-4 py-3 transition-colors">
                                    @error('year') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Step 2: Documents (Dynamic based on applicant type) --}}
                        <div x-show="$wire.currentStep === 2" x-transition>
                            {{-- Error Summary --}}
                            @if($errors->any() && $currentStep === 2)
                            <div class="mb-6">
                                <div class="flex items-start gap-3 p-4 bg-red-50 border border-red-200 rounded-lg">
                                    <svg class="w-5 h-5 text-red-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-red-900">Please upload all required documents</p>
                                        <ul class="text-sm text-red-700 mt-2 list-disc list-inside">
                                            @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="mb-6">
                                <div class="flex items-start gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-lg">
                                    <svg class="w-5 h-5 text-emerald-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-emerald-900">Required Documents</p>
                                        <p class="text-sm text-emerald-700 mt-1">
                                            Upload all necessary documents based on your applicant type.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-6">
                                @foreach($requiredDocuments as $index => $document)
                                @php
                                    $docName = $document['name'] ?? 'document_' . $index;
                                    $docLabel = $document['label'] ?? ucwords(str_replace('_', ' ', $docName));
                                    $docDescription = $document['description'] ?? '';
                                    $acceptedFormats = $document['accepted_formats'] ?? 'pdf,jpg,jpeg,png';
                                    $maxSize = $document['max_file_size'] ?? 10240;
                                    $isRequired = $document['is_required'] ?? true;
                                    $acceptHtml = '.' . implode(',.', explode(',', $acceptedFormats));
                                @endphp
                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                                        {{ $docLabel }} @if($isRequired)<span class="text-red-500">*</span>@endif
                                    </label>
                                    @if($docDescription)
                                    <p class="text-xs text-gray-500 mb-2">{{ $docDescription }}</p>
                                    @endif
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 hover:border-emerald-500 transition-colors">
                                        <input
                                            type="file"
                                            wire:model="documentFiles.{{ $docName }}"
                                            id="doc-{{ $docName }}"
                                            class="hidden"
                                            accept="{{ $acceptHtml }}"
                                            multiple>
                                        <label for="doc-{{ $docName }}" class="cursor-pointer flex flex-col items-center gap-2">
                                            <svg class="h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <p class="text-sm font-medium text-gray-700">Click to upload {{ strtolower($docLabel) }}</p>
                                            <p class="text-xs text-gray-500">{{ strtoupper(str_replace(',', ', ', $acceptedFormats)) }} up to {{ round($maxSize / 1024) }}MB</p>
                                        </label>
                                    </div>

                                    {{-- Loading Spinner --}}
                                    <div wire:loading wire:target="documentFiles.{{ $docName }}" class="mt-3">
                                        <div class="flex items-center gap-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                            <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <span class="text-sm font-medium text-blue-700">Uploading {{ strtolower($docLabel) }}...</span>
                                        </div>
                                    </div>

                                    @error("documentFiles.{$docName}") <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                                    @error("documentFiles.{$docName}.*") <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror

                                    @if(isset($documentFiles[$docName]) && is_array($documentFiles[$docName]) && count($documentFiles[$docName]) > 0)
                                    <div class="mt-3 space-y-2" wire:loading.remove wire:target="documentFiles.{{ $docName }}">
                                        @foreach ($documentFiles[$docName] as $fileIndex => $file)
                                        <div class="flex items-center justify-between gap-2 p-2 bg-emerald-50 border border-emerald-200 rounded-lg">
                                            <div class="flex items-center gap-2">
                                                <svg class="h-5 w-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                                <span class="text-sm text-gray-700">{{ $file->getClientOriginalName() }}</span>
                                            </div>
                                            <button
                                                type="button"
                                                wire:click="removeDocumentFile('{{ $docName }}', {{ $fileIndex }})"
                                                class="text-red-500 hover:text-red-700 p-1 rounded hover:bg-red-50 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Step 3: Review --}}
                        <div x-show="$wire.currentStep === 3" x-transition>
                            <div class="mb-6">
                                <div class="flex items-start gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-lg">
                                    <svg class="w-5 h-5 text-emerald-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-emerald-900">Review Information</p>
                                        <p class="text-sm text-emerald-700 mt-1">
                                            Please review all details before submitting the application.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                {{-- Vehicle Information --}}
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <h4 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                        <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                                        </svg>
                                        Vehicle Information
                                    </h4>
                                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-3">
                                        <div>
                                            <dt class="text-xs text-gray-500 font-medium">Vehicle Type</dt>
                                            <dd class="text-sm text-gray-900 mt-1">{{ $vehicle_type ?? 'N/A' }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-xs text-gray-500 font-medium">Plate Number</dt>
                                            <dd class="text-sm text-gray-900 mt-1">{{ $plate_number ?? 'N/A' }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-xs text-gray-500 font-medium">Make</dt>
                                            <dd class="text-sm text-gray-900 mt-1">{{ $make ?? 'N/A' }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-xs text-gray-500 font-medium">Model</dt>
                                            <dd class="text-sm text-gray-900 mt-1">{{ $model ?? 'N/A' }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-xs text-gray-500 font-medium">Color</dt>
                                            <dd class="text-sm text-gray-900 mt-1">{{ $color ?? 'N/A' }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-xs text-gray-500 font-medium">Year</dt>
                                            <dd class="text-sm text-gray-900 mt-1">{{ $year ?? 'N/A' }}</dd>
                                        </div>
                                    </dl>
                                </div>

                                {{-- Documents --}}
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <h4 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                        <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Uploaded Documents
                                    </h4>
                                    <ul class="space-y-2">
                                        @foreach($requiredDocuments as $document)
                                        @php
                                            $docName = $document['name'] ?? '';
                                            $docLabel = $document['label'] ?? ucwords(str_replace('_', ' ', $docName));
                                            $fileCount = isset($documentFiles[$docName]) && is_array($documentFiles[$docName]) ? count($documentFiles[$docName]) : 0;
                                        @endphp
                                        <li class="text-sm flex items-center gap-2">
                                            @if($fileCount > 0)
                                            <svg class="h-4 w-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                            @else
                                            <svg class="h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                            </svg>
                                            @endif
                                            <span class="font-medium text-gray-700">{{ $docLabel }}:</span>
                                            <span class="text-gray-600">{{ $fileCount }} file(s)</span>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="bg-gray-50 px-6 py-4 flex items-center justify-between gap-3 border-t border-gray-200">
                        <div>
                            <button
                                x-show="$wire.currentStep > 1"
                                @click.prevent="$wire.prevStep()"
                                type="button"
                                class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all shadow-sm">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                    <span>Back</span>
                                </span>
                            </button>
                        </div>
                        <div class="flex gap-2">
                            <button
                                wire:click="resetForm"
                                @click="vehicleAddModalOpen = false"
                                type="button"
                                class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all shadow-sm">
                                Cancel
                            </button>
                            <button
                                x-show="$wire.currentStep < 3"
                                wire:click="nextVehicleStep"
                                type="button"
                                class="px-5 py-2.5 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all shadow-sm hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed"
                                wire:loading.attr="disabled"
                                wire:target="nextVehicleStep">
                                <span class="flex items-center gap-2">
                                    <span wire:loading.remove wire:target="nextVehicleStep">Next</span>
                                    <span wire:loading wire:target="nextVehicleStep">Validating...</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" wire:loading.remove wire:target="nextVehicleStep">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" wire:loading wire:target="nextVehicleStep">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </span>
                            </button>
                            <button
                                x-show="$wire.currentStep === 3"
                                type="submit"
                                class="px-5 py-2.5 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all shadow-sm hover:shadow-md"
                                wire:loading.attr="disabled"
                                wire:target="submitVehicleApplication">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" wire:loading.remove wire:target="submitVehicleApplication">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" wire:loading wire:target="submitVehicleApplication">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span wire:loading.remove wire:target="submitVehicleApplication">Submit Application</span>
                                    <span wire:loading wire:target="submitVehicleApplication">Submitting...</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
        </template>
    </div>
    @endif
</div>

@script
<script>
    document.addEventListener('livewire:navigated', () => {
        const table = document.getElementById('{{ $this->type }}-table');
        if (table) table.scrollIntoView({
            behavior: 'smooth'
        });
    });

    // Toast notifications
    Livewire.on('toast', (data) => {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            showCloseButton: true,
            timer: data.timeout || 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        Toast.fire({
            icon: data.type,
            title: data.message,
            customClass: {
                popup: 'rounded-lg shadow-xl',
                title: 'text-sm font-medium'
            }
        });
    });

    // Alert dialogs
    Livewire.on('alert', (data) => {
        Swal.fire({
            title: data.title || (data.type === 'success' ? 'Success!' : data.type === 'error' ? 'Error!' : 'Notice'),
            text: data.message,
            icon: data.type,
            confirmButtonText: data.confirmText || 'OK',
            confirmButtonColor: data.type === 'success' ? '#10b981' : data.type === 'error' ? '#ef4444' : '#3b82f6',
            customClass: {
                popup: 'rounded-xl',
                confirmButton: 'rounded-lg px-6 py-2.5 font-medium'
            }
        });
    });

    // Auto-focus modal input when opened
    document.addEventListener('alpine:init', () => {
        Alpine.magic('focusVehicleInput', () => {
            return () => {
                setTimeout(() => {
                    const input = document.getElementById('vehicle-number-input');
                    if (input) input.focus();
                }, 100);
            };
        });
    });
</script>
@endscript
