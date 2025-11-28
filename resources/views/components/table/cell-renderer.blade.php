@props(['value', 'index', 'isBold', 'rowCount'])

<td class="px-4 py-4 align-middle {{ $isBold ? 'font-semibold text-gray-900' : 'text-gray-700' }}">
    @if(is_array($value) && array_key_exists('tooltip', $value))
        <div x-data="{ open: false }" class="relative inline-flex items-center gap-2">
            <!-- Overview Text -->
            <span class="font-medium text-gray-900">
                {{ $value['overview'] }}
            </span>

            <!-- Info Icon with Hover Area -->
            <div class="relative inline-block">
                <button
                    @mouseenter="open = true"
                    @mouseleave="open = false"
                    @focus="open = true"
                    @blur="open = false"
                    type="button"
                    class="inline-flex items-center justify-center w-5 h-5 text-gray-400 hover:text-green-600 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500/50 rounded-full"
                    aria-label="View status details">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </button>

                <!-- Tooltip Card -->
                <div x-show="open"
                    x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-1"
                    class="absolute left-1/2 -translate-x-1/2 {{ $index == 0 ? 'top-full mt-2' : 'bottom-full mb-2' }} z-[9999] pointer-events-none"
                    style="min-width: 200px;">

                    <!-- Tooltip Content -->
                    <div class="bg-gradient-to-br from-gray-800 to-gray-900 text-white rounded-lg shadow-xl border border-gray-700">
                        <!-- Header -->
                        <div class="px-3 py-2 bg-gray-700/50 border-b border-gray-600">
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-300">Status Breakdown</p>
                        </div>

                        <!-- Status Items -->
                        <div class="px-3 py-2.5 space-y-2">
                            <!-- Active Status -->
                            <div class="flex items-center justify-between group">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-green-400 ring-2 ring-green-400/30"></span>
                                    <span class="text-sm text-gray-300">Active</span>
                                </div>
                                <span class="text-sm font-semibold text-white">{{ $value['tooltip']['active'] }}</span>
                            </div>

                            <!-- Pending Status -->
                            <div class="flex items-center justify-between group">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-yellow-400 ring-2 ring-yellow-400/30"></span>
                                    <span class="text-sm text-gray-300">Pending</span>
                                </div>
                                <span class="text-sm font-semibold text-white">{{ $value['tooltip']['pending'] }}</span>
                            </div>

                            <!-- Rejected Status -->
                            <div class="flex items-center justify-between group">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-red-400 ring-2 ring-red-400/30"></span>
                                    <span class="text-sm text-gray-300">Rejected</span>
                                </div>
                                <span class="text-sm font-semibold text-white">{{ $value['tooltip']['rejected'] }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Tooltip Arrow -->
                    <div class="absolute left-1/2 -translate-x-1/2 {{ $index == 0 ? 'bottom-full rotate-180' : 'top-full'}}">
                        <div class="border-8 border-transparent border-t-gray-900"></div>
                    </div>
                </div>
            </div>
        </div>
    @elseif(is_array($value) && array_key_exists('badge_label', $value))
        <x-ui.badge :badge="$value" />
    @elseif(filter_var($value, FILTER_VALIDATE_URL))
        <a href="{{ $value }}"
           target="_blank"
           class="text-green-600 hover:text-green-700 hover:underline inline-flex items-center gap-1">
            View
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
            </svg>
        </a>
    @elseif(is_bool($value))
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $value ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
            {{ $value ? 'Yes' : 'No' }}
        </span>
    @else
        <span class="line-clamp-2">{{ $value }}</span>
    @endif
</td>
