<div wire:poll.60s="loadStats">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Applicants Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Applicants</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($stats['applicants']['total'] ?? 0) }}</p>
                    <div class="flex items-center mt-2">
                        @if(($stats['applicants']['trend'] ?? 'up') === 'up')
                            <span class="inline-flex items-center text-sm text-green-600">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                                </svg>
                                {{ abs($stats['applicants']['percent_change'] ?? 0) }}%
                            </span>
                        @else
                            <span class="inline-flex items-center text-sm text-red-600">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                </svg>
                                {{ abs($stats['applicants']['percent_change'] ?? 0) }}%
                            </span>
                        @endif
                        <span class="text-xs text-gray-500 ml-2">vs last month</span>
                    </div>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-xs text-gray-500">
                    <span class="font-semibold text-gray-700">{{ $stats['applicants']['new_this_month'] ?? 0 }}</span> new this month
                </p>
            </div>
        </div>

        <!-- Registered Vehicles Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Registered Vehicles</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($stats['vehicles']['total'] ?? 0) }}</p>
                    <div class="flex items-center mt-2">
                        @if(($stats['vehicles']['trend'] ?? 'up') === 'up')
                            <span class="inline-flex items-center text-sm text-green-600">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                                </svg>
                                {{ abs($stats['vehicles']['percent_change'] ?? 0) }}%
                            </span>
                        @else
                            <span class="inline-flex items-center text-sm text-red-600">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                </svg>
                                {{ abs($stats['vehicles']['percent_change'] ?? 0) }}%
                            </span>
                        @endif
                        <span class="text-xs text-gray-500 ml-2">vs last month</span>
                    </div>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-xs text-gray-500">
                    <span class="font-semibold text-gray-700">{{ $stats['vehicles']['new_this_month'] ?? 0 }}</span> registered this month
                </p>
            </div>
        </div>

        <!-- Pending Applications Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Pending Applications</p>
                    <p class="text-3xl font-bold text-amber-600 mt-1">{{ number_format($stats['applications']['pending'] ?? 0) }}</p>
                    <div class="flex items-center mt-2">
                        <span class="inline-flex items-center text-sm text-gray-600">
                            {{ $stats['applications']['approval_rate'] ?? 0 }}% approval rate
                        </span>
                    </div>
                </div>
                <div class="p-3 bg-amber-100 rounded-full">
                    <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100">
                <div class="flex justify-between text-xs">
                    <span class="text-green-600"><span class="font-semibold">{{ $stats['applications']['approved'] ?? 0 }}</span> approved</span>
                    <span class="text-red-600"><span class="font-semibold">{{ $stats['applications']['rejected'] ?? 0 }}</span> rejected</span>
                </div>
            </div>
        </div>

        <!-- Team Members Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Team Members</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($stats['admins']['total'] ?? 0) }}</p>
                    <div class="flex items-center mt-2">
                        @if(($stats['admins']['trend'] ?? 'up') === 'up')
                            <span class="inline-flex items-center text-sm text-green-600">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                                </svg>
                                {{ abs($stats['admins']['percent_change'] ?? 0) }}%
                            </span>
                        @else
                            <span class="inline-flex items-center text-sm text-red-600">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                </svg>
                                {{ abs($stats['admins']['percent_change'] ?? 0) }}%
                            </span>
                        @endif
                        <span class="text-xs text-gray-500 ml-2">vs last month</span>
                    </div>
                </div>
                <div class="p-3 bg-purple-100 rounded-full">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-xs text-gray-500">
                    <span class="font-semibold text-green-600">{{ $stats['admins']['active'] ?? 0 }}</span> active members
                </p>
            </div>
        </div>
    </div>
</div>
