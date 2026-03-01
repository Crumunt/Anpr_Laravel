<div wire:poll.{{ $refreshInterval }}s="refresh">
    {{-- 24-Hour Metric Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 p-4 md:p-8">

        {{-- Total Vehicles Detected Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">
                        Vehicles Detected
                    </p>
                    <p class="text-sm text-gray-400 mb-3">Last 24 Hours</p>
                    <h3 class="text-3xl font-bold text-gray-800">
                        {{ number_format($totalVehicles) }}
                    </h3>
                    <div class="flex items-center mt-3">
                        @if($percentChanges['vehicles'] >= 0)
                            <span class="flex items-center text-sm text-green-600">
                                <i class="fas fa-arrow-up mr-1 text-xs"></i>
                                {{ abs($percentChanges['vehicles']) }}%
                            </span>
                        @else
                            <span class="flex items-center text-sm text-red-600">
                                <i class="fas fa-arrow-down mr-1 text-xs"></i>
                                {{ abs($percentChanges['vehicles']) }}%
                            </span>
                        @endif
                        <span class="text-gray-400 text-sm ml-2">from yesterday</span>
                    </div>
                </div>
                <div class="flex-shrink-0 ml-4">
                    <div class="w-14 h-14 rounded-xl flex items-center justify-center"
                         style="background: linear-gradient(135deg, #e6f7ef, #d1f0e2);">
                        <i class="fas fa-car text-2xl" style="color: #1a5632;"></i>
                    </div>
                </div>
            </div>
            {{-- Progress bar --}}
            <div class="mt-4">
                <div class="w-full bg-gray-100 rounded-full h-1.5">
                    <div class="h-1.5 rounded-full transition-all duration-500"
                         style="width: {{ min(100, ($totalVehicles / max(1, $totalVehicles + 50)) * 100) }}%; background-color: #1a5632;">
                    </div>
                </div>
            </div>
        </div>

        {{-- Flagged Plates Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">
                        Active Flags
                    </p>
                    <p class="text-sm text-gray-400 mb-3">Flagged Vehicles</p>
                    <h3 class="text-3xl font-bold text-gray-800">
                        {{ number_format($flaggedPlates) }}
                    </h3>
                    <div class="flex items-center mt-3">
                        @if($percentChanges['flagged'] >= 0)
                            <span class="flex items-center text-sm text-red-600">
                                <i class="fas fa-arrow-up mr-1 text-xs"></i>
                                {{ abs($percentChanges['flagged']) }}%
                            </span>
                        @else
                            <span class="flex items-center text-sm text-green-600">
                                <i class="fas fa-arrow-down mr-1 text-xs"></i>
                                {{ abs($percentChanges['flagged']) }}%
                            </span>
                        @endif
                        <span class="text-gray-400 text-sm ml-2">from yesterday</span>
                    </div>
                    @if($highPriorityAlerts > 0)
                        <p class="text-xs text-red-600 mt-2 font-medium">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $highPriorityAlerts }} high priority
                        </p>
                    @endif
                </div>
                <div class="flex-shrink-0 ml-4">
                    <div class="w-14 h-14 rounded-xl flex items-center justify-center"
                         style="background: linear-gradient(135deg, #ffe6e6, #ffcccc);">
                        <i class="fas fa-flag text-2xl text-red-600"></i>
                    </div>
                </div>
            </div>
            {{-- Progress bar --}}
            <div class="mt-4">
                <div class="w-full bg-gray-100 rounded-full h-1.5">
                    <div class="bg-red-500 h-1.5 rounded-full transition-all duration-500"
                         style="width: {{ min(100, $flaggedPlates * 5) }}%;">
                    </div>
                </div>
            </div>
        </div>

        {{-- Unique Plates Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">
                        Unique Plates
                    </p>
                    <p class="text-sm text-gray-400 mb-3">Last 24 Hours</p>
                    <h3 class="text-3xl font-bold text-gray-800">
                        {{ number_format($uniquePlates) }}
                    </h3>
                    <div class="flex items-center mt-3">
                        @if($percentChanges['unique'] >= 0)
                            <span class="flex items-center text-sm text-green-600">
                                <i class="fas fa-arrow-up mr-1 text-xs"></i>
                                {{ abs($percentChanges['unique']) }}%
                            </span>
                        @else
                            <span class="flex items-center text-sm text-red-600">
                                <i class="fas fa-arrow-down mr-1 text-xs"></i>
                                {{ abs($percentChanges['unique']) }}%
                            </span>
                        @endif
                        <span class="text-gray-400 text-sm ml-2">from yesterday</span>
                    </div>
                </div>
                <div class="flex-shrink-0 ml-4">
                    <div class="w-14 h-14 rounded-xl flex items-center justify-center"
                         style="background: linear-gradient(135deg, #e6e6ff, #ccccff);">
                        <i class="fas fa-id-card text-2xl text-indigo-600"></i>
                    </div>
                </div>
            </div>
            {{-- Progress bar --}}
            <div class="mt-4">
                <div class="w-full bg-gray-100 rounded-full h-1.5">
                    <div class="bg-indigo-500 h-1.5 rounded-full transition-all duration-500"
                         style="width: {{ $totalVehicles > 0 ? min(100, ($uniquePlates / $totalVehicles) * 100) : 0 }}%;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
