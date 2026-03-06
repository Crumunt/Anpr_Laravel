<div wire:poll.30s x-data="{ showAllActivities: false }">
    <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden transition-all duration-300 hover:shadow-lg">
        <!-- Enhanced Header -->
        <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-white to-gray-50 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <h2 class="text-xl font-bold text-gray-800">Activity Log</h2>
                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ count($activities) }} recent</span>
            </div>
            <div class="flex items-center space-x-2">
                <button
                    wire:click="loadActivities"
                    wire:loading.attr="disabled"
                    class="text-gray-400 hover:text-gray-600 cursor-pointer transition-colors p-1 rounded hover:bg-gray-100"
                    title="Refresh activities">
                    <svg wire:loading.remove wire:target="loadActivities" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <svg wire:loading wire:target="loadActivities" class="animate-spin h-5 w-5 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
                <div class="text-gray-400 hover:text-gray-600 cursor-pointer transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Enhanced Activity List with Timeline -->
        <div class="p-6">
            <ul class="space-y-6 relative">
                <div class="absolute left-4 top-3 bottom-3 w-0.5 bg-gray-200 z-0"></div>

                @forelse($activities as $index => $activity)
                    <li class="flex items-start relative z-10">
                        <!-- Enhanced Activity Icon -->
                        <div class="flex-shrink-0 h-9 w-9 rounded-full {{ $activity['bgColor'] }} flex items-center justify-center shadow-sm ring-4 ring-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="{{ $activity['iconColor'] }} h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                @if(strpos($activity['icon'], 'fa-user') !== false)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                @elseif(strpos($activity['icon'], 'fa-key') !== false)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0
                                          01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                @elseif(strpos($activity['icon'], 'fa-door-open') !== false)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0
                                          002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                @elseif(strpos($activity['icon'], 'fa-cog') !== false)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M10.325 4.317c.426-1.756 2.924-1.756
                                          3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94
                                          3.31.826 2.37 2.37a1.724 1.724 0 001.065
                                          2.572c1.756.426 1.756 2.924 0
                                          3.35a1.724 1.724 0 00-1.066
                                          2.573c.94 1.543-.826 3.31-2.37
                                          2.37a1.724 1.724 0 00-2.572
                                          1.065c-.426 1.756-2.924 1.756-3.35
                                          0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724
                                          1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924
                                          0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31
                                          2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                @elseif(strpos($activity['icon'], 'fa-exclamation') !== false)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54
                                          0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464
                                          0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                @elseif(strpos($activity['icon'], 'fa-check') !== false)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M5 13l4 4L19 7" />
                                @elseif(strpos($activity['icon'], 'fa-car') !== false)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M8 7h8m-8 5h8m-4-9v18M5 10h14l-1.405-4.216A2 2 0 0015.697 4H8.303a2 2 0 00-1.898 1.368L5 10z" />
                                @elseif(strpos($activity['icon'], 'fa-file') !== false)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                @elseif(strpos($activity['icon'], 'fa-sync') !== false)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                @elseif(strpos($activity['icon'], 'fa-archive') !== false)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M13 16h-1v-4h-1m1-4h.01M21
                                          12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                @endif
                            </svg>
                        </div>

                        <!-- Enhanced Activity Content -->
                        <div class="ml-4 flex-1">
                            <div class="bg-gray-50 rounded-lg p-3 border border-gray-100 shadow-sm hover:shadow transition-shadow duration-200">
                                <p class="text-sm font-medium text-gray-800">{!! $activity['description'] !!}</p>
                                <div class="mt-1 flex items-center space-x-2">
                                    <p class="text-xs text-gray-500" title="{{ $activity['exact_time'] ?? '' }}">{{ $activity['timestamp'] }}</p>
                                    @if(isset($activity['priority']) && $activity['priority'] === 'high')
                                        <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700">
                                            High Priority
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="text-center text-sm text-gray-500 py-4">
                        No activities have been recorded yet.
                    </li>
                @endforelse
            </ul>

            <!-- View All Button -->
            @if (count($activities) >= $limit)
                <div class="mt-6 flex justify-center">
                    <button
                        @click="showAllActivities = true"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                        View All Activity
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- All Activities Modal -->
    <div
        x-show="showAllActivities"
        x-cloak
        @keydown.escape.window="showAllActivities = false"
        class="fixed inset-0 z-[150] overflow-y-auto"
        style="display: none;">

        <!-- Backdrop -->
        <div
            x-show="showAllActivities"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
            @click="showAllActivities = false">
        </div>

        <!-- Modal Content -->
        <div class="flex min-h-full items-center justify-center p-4">
            <div
                x-show="showAllActivities"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[80vh] overflow-hidden">

                <!-- Modal Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-white to-gray-50">
                    <div class="flex items-center space-x-2">
                        <h3 class="text-xl font-bold text-gray-800">All Activity Logs</h3>
                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ count($activities) }} total</span>
                    </div>
                    <button
                        @click="showAllActivities = false"
                        class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Body with Scrollable Activities -->
                <div class="p-6 overflow-y-auto max-h-[60vh]">
                    <ul class="space-y-4 relative">
                        <div class="absolute left-4 top-3 bottom-3 w-0.5 bg-gray-200 z-0"></div>

                        @foreach($activities as $activity)
                            <li class="flex items-start relative z-10">
                                <!-- Activity Icon -->
                                <div class="flex-shrink-0 h-9 w-9 rounded-full {{ $activity['bgColor'] }} flex items-center justify-center shadow-sm ring-4 ring-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="{{ $activity['iconColor'] }} h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        @if(strpos($activity['icon'], 'fa-check') !== false)
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        @endif
                                    </svg>
                                </div>

                                <!-- Activity Content -->
                                <div class="ml-4 flex-1">
                                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-100 shadow-sm">
                                        <p class="text-sm font-medium text-gray-800">{!! $activity['description'] !!}</p>
                                        <p class="text-xs text-gray-500 mt-1" title="{{ $activity['exact_time'] ?? '' }}">{{ $activity['timestamp'] }}</p>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-end">
                    <button
                        @click="showAllActivities = false"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
