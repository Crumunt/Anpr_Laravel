<div>
    {{-- Date Range Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Analytics Dashboard</h3>
                <p class="text-sm text-gray-600 mt-1">Generate reports and view system statistics</p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                {{-- Date Range Select --}}
                <select
                    wire:model.live="dateRange"
                    class="px-4 py-2.5 text-sm border border-gray-200 rounded-lg bg-white focus:ring-2 focus:ring-green-500 focus:border-green-500"
                >
                    <option value="24hours">Last 24 Hours</option>
                    <option value="7days">Last 7 Days</option>
                    <option value="30days">Last 30 Days</option>
                    <option value="90days">Last 90 Days</option>
                    <option value="custom">Custom Range</option>
                </select>

                {{-- Custom Date Range --}}
                @if($dateRange === 'custom')
                    <input
                        type="date"
                        wire:model.live="customStartDate"
                        class="px-4 py-2.5 text-sm border border-gray-200 rounded-lg bg-white focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    >
                    <span class="text-gray-500">to</span>
                    <input
                        type="date"
                        wire:model.live="customEndDate"
                        class="px-4 py-2.5 text-sm border border-gray-200 rounded-lg bg-white focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    >
                @endif

                {{-- Gate Filter --}}
                <select
                    wire:model.live="gateFilter"
                    class="px-4 py-2.5 text-sm border border-gray-200 rounded-lg bg-white focus:ring-2 focus:ring-green-500 focus:border-green-500"
                >
                    <option value="all">All Gates</option>
                    @foreach($availableGates as $gate)
                        <option value="{{ $gate }}">{{ $gate }}</option>
                    @endforeach
                </select>

                {{-- Direction Filter --}}
                <select
                    wire:model.live="locationFilter"
                    class="px-4 py-2.5 text-sm border border-gray-200 rounded-lg bg-white focus:ring-2 focus:ring-green-500 focus:border-green-500"
                >
                    <option value="all">All Directions</option>
                    @foreach($availableLocations as $location)
                        <option value="{{ $location }}">{{ $location }}</option>
                    @endforeach
                </select>

                {{-- Refresh Button --}}
                <button
                    wire:click="refreshData"
                    class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                >
                    <i class="fas fa-sync-alt mr-2"></i>
                    Refresh
                </button>

                {{-- Generate Report Button --}}
                <button
                    wire:click="openReportModal"
                    class="px-4 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-green-600 to-green-700 rounded-lg hover:from-green-700 hover:to-green-800 transition-all"
                >
                    <i class="fas fa-file-alt mr-2"></i>
                    Generate Report
                </button>
            </div>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-6">
        {{-- Total Detections --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 lg:p-6 hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-car text-blue-600 text-lg"></i>
                </div>
                <span class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Total</span>
            </div>
            <p class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($stats['total_detections']) }}</p>
            <p class="text-sm text-gray-600 font-medium">Total Detections</p>
        </div>

        {{-- Flagged Vehicles --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 lg:p-6 hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-flag text-red-600 text-lg"></i>
                </div>
                <span class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Flagged</span>
            </div>
            <p class="text-3xl font-bold text-red-600 mb-1">{{ number_format($stats['flagged_count']) }}</p>
            <p class="text-sm text-gray-600 font-medium">Flagged Vehicles</p>
        </div>

        {{-- Unique Plates --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 lg:p-6 hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-id-card text-purple-600 text-lg"></i>
                </div>
                <span class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Unique</span>
            </div>
            <p class="text-3xl font-bold text-purple-600 mb-1">{{ number_format($stats['unique_plates']) }}</p>
            <p class="text-sm text-gray-600 font-medium">Unique Plates</p>
        </div>

        {{-- Avg Confidence --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 lg:p-6 hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-line text-green-600 text-lg"></i>
                </div>
                <span class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Avg</span>
            </div>
            <p class="text-3xl font-bold text-green-600 mb-1">{{ $stats['avg_confidence'] }}%</p>
            <p class="text-sm text-gray-600 font-medium">Avg Confidence</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Hourly Activity Chart --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                <h3 class="text-lg font-semibold text-gray-900">Hourly Activity</h3>
                <p class="text-sm text-gray-600 mt-1">Detections per hour</p>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @php
                        $maxHourly = max(array_column($hourlyData, 'count')) ?: 1;
                    @endphp
                    @foreach(array_slice($hourlyData, 0, 12) as $hour)
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-medium text-gray-500 w-12">{{ $hour['hour'] }}:00</span>
                            <div class="flex-1 bg-gray-100 rounded-full h-6 relative overflow-hidden">
                                <div
                                    class="h-6 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-end pr-2"
                                    style="width: {{ ($hour['count'] / $maxHourly) * 100 }}%; min-width: {{ $hour['count'] > 0 ? '40px' : '0' }}"
                                >
                                    @if($hour['count'] > 0)
                                        <span class="text-xs font-semibold text-white">{{ $hour['count'] }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if(count($hourlyData) > 12)
                    <button
                        wire:click="$toggle('showAllHours')"
                        class="mt-4 text-sm text-blue-600 hover:text-blue-800 font-medium"
                    >
                        {{ $showAllHours ?? false ? 'Show Less' : 'Show All Hours' }}
                    </button>
                @endif
            </div>
        </div>

        {{-- Daily Activity Chart --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                <h3 class="text-lg font-semibold text-gray-900">Daily Activity</h3>
                <p class="text-sm text-gray-600 mt-1">Detections per day</p>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @php
                        $maxDaily = max(array_column($dailyData, 'count')) ?: 1;
                    @endphp
                    @foreach($dailyData as $day)
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-medium text-gray-500 w-20">{{ \Carbon\Carbon::parse($day['date'])->format('M d') }}</span>
                            <div class="flex-1 bg-gray-100 rounded-full h-6 relative overflow-hidden">
                                <div
                                    class="h-6 rounded-full bg-gradient-to-r from-green-500 to-green-600 flex items-center justify-end pr-2"
                                    style="width: {{ ($day['count'] / $maxDaily) * 100 }}%; min-width: {{ $day['count'] > 0 ? '40px' : '0' }}"
                                >
                                    @if($day['count'] > 0)
                                        <span class="text-xs font-semibold text-white">{{ $day['count'] }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Gate Distribution --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                <h3 class="text-lg font-semibold text-gray-900">Gate Distribution</h3>
                <p class="text-sm text-gray-600 mt-1">Detections by gate type</p>
            </div>
            <div class="p-6">
                @php
                    $totalGateDetections = array_sum(array_column($gateDistribution, 'count')) ?: 1;
                    $gateColors = ['entry' => 'blue', 'exit' => 'green', 'parking' => 'purple'];
                @endphp
                <div class="space-y-4">
                    @forelse($gateDistribution as $gate)
                        @php
                            $percentage = round(($gate['count'] / $totalGateDetections) * 100);
                            $color = $gateColors[$gate['gate_type']] ?? 'gray';
                        @endphp
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700 capitalize">{{ $gate['gate_type'] }} Gate</span>
                                <span class="text-sm font-semibold text-gray-900">{{ number_format($gate['count']) }} ({{ $percentage }}%)</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-3">
                                <div
                                    class="h-3 rounded-full bg-{{ $color }}-500"
                                    style="width: {{ $percentage }}%"
                                ></div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-chart-pie text-3xl mb-3 text-gray-300"></i>
                            <p>No gate data available</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Flagged Summary --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                <h3 class="text-lg font-semibold text-gray-900">Flagged Summary</h3>
                <p class="text-sm text-gray-600 mt-1">Overview of flagged vehicle statistics</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-red-50 rounded-lg p-4 text-center">
                        <p class="text-3xl font-bold text-red-600">{{ $flaggedSummary['total'] }}</p>
                        <p class="text-sm text-gray-600">Total Flagged</p>
                    </div>
                    <div class="bg-amber-50 rounded-lg p-4 text-center">
                        <p class="text-3xl font-bold text-amber-600">{{ $flaggedSummary['pending'] }}</p>
                        <p class="text-sm text-gray-600">Pending Review</p>
                    </div>
                    <div class="bg-green-50 rounded-lg p-4 text-center">
                        <p class="text-3xl font-bold text-green-600">{{ $flaggedSummary['resolved'] }}</p>
                        <p class="text-sm text-gray-600">Resolved</p>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-4 text-center">
                        <p class="text-3xl font-bold text-blue-600">{{ number_format($flaggedSummary['flag_rate'], 1) }}%</p>
                        <p class="text-sm text-gray-600">Flag Rate</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Top Plates --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Most Detected Plates</h3>
                    <p class="text-sm text-gray-600 mt-1">Frequently detected vehicle plates</p>
                </div>
                <select
                    wire:model.live="topPlatesLimit"
                    class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg bg-white focus:ring-2 focus:ring-green-500 focus:border-green-500"
                >
                    <option value="5">Top 5</option>
                    <option value="10">Top 10</option>
                    <option value="20">Top 20</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Rank</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Plate Number</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Detections</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Last Seen</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Avg Confidence</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($topPlates as $index => $plate)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $index < 3 ? 'bg-gradient-to-r from-amber-400 to-amber-500 text-white' : 'bg-gray-100 text-gray-600' }} font-bold text-sm">
                                    {{ $index + 1 }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-800 tracking-wide">{{ $plate['plate_number'] }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                    {{ number_format($plate['count']) }} detections
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($plate['last_seen'])->format('M d, Y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                        <div
                                            class="h-2 rounded-full {{ $plate['avg_confidence'] >= 90 ? 'bg-green-500' : ($plate['avg_confidence'] >= 75 ? 'bg-yellow-500' : 'bg-red-500') }}"
                                            style="width: {{ $plate['avg_confidence'] }}%"
                                        ></div>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">{{ number_format($plate['avg_confidence'], 1) }}%</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                        <i class="fas fa-chart-bar text-3xl text-gray-300"></i>
                                    </div>
                                    <p class="text-gray-500 font-medium">No detection data available</p>
                                    <p class="text-gray-400 text-sm mt-1">Detections will appear here</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Report Generation Modal --}}
    @if($showReportModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeReportModal"></div>

                <div class="relative inline-block bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full">
                    <div class="bg-white px-6 py-5">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Generate Report</h3>
                            <button wire:click="closeReportModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <form wire:submit="generateReport">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Report Type</label>
                                <div class="space-y-2">
                                    <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                        <input
                                            type="radio"
                                            wire:model="reportForm.type"
                                            value="summary"
                                            class="text-green-600 focus:ring-green-500"
                                        >
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-700">Summary Report</p>
                                            <p class="text-xs text-gray-500">Overview statistics and key metrics</p>
                                        </div>
                                    </label>
                                    <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                        <input
                                            type="radio"
                                            wire:model="reportForm.type"
                                            value="detailed"
                                            class="text-green-600 focus:ring-green-500"
                                        >
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-700">Detailed Report</p>
                                            <p class="text-xs text-gray-500">Complete list of all detections</p>
                                        </div>
                                    </label>
                                    <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                        <input
                                            type="radio"
                                            wire:model="reportForm.type"
                                            value="flagged"
                                            class="text-green-600 focus:ring-green-500"
                                        >
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-700">Flagged Vehicles Report</p>
                                            <p class="text-xs text-gray-500">Only flagged vehicle records</p>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Export Format</label>
                                <div class="grid grid-cols-3 gap-2">
                                    <label class="flex items-center justify-center p-3 border rounded-lg cursor-pointer transition-colors {{ ($reportForm['format'] ?? 'csv') === 'csv' ? 'bg-green-50 border-green-500 text-green-700' : 'border-gray-200 hover:bg-gray-50' }}">
                                        <input
                                            type="radio"
                                            wire:model="reportForm.format"
                                            value="csv"
                                            class="sr-only"
                                        >
                                        <i class="fas fa-file-csv mr-2"></i>
                                        CSV
                                    </label>
                                    <label class="flex items-center justify-center p-3 border rounded-lg cursor-pointer transition-colors {{ ($reportForm['format'] ?? '') === 'pdf' ? 'bg-green-50 border-green-500 text-green-700' : 'border-gray-200 hover:bg-gray-50' }}">
                                        <input
                                            type="radio"
                                            wire:model="reportForm.format"
                                            value="pdf"
                                            class="sr-only"
                                        >
                                        <i class="fas fa-file-pdf mr-2"></i>
                                        PDF
                                    </label>
                                    <label class="flex items-center justify-center p-3 border rounded-lg cursor-pointer transition-colors {{ ($reportForm['format'] ?? '') === 'excel' ? 'bg-green-50 border-green-500 text-green-700' : 'border-gray-200 hover:bg-gray-50' }}">
                                        <input
                                            type="radio"
                                            wire:model="reportForm.format"
                                            value="excel"
                                            class="sr-only"
                                        >
                                        <i class="fas fa-file-excel mr-2"></i>
                                        Excel
                                    </label>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs text-gray-500 mb-1">Start Date</label>
                                        <input
                                            type="date"
                                            wire:model="reportForm.start_date"
                                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        >
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-500 mb-1">End Date</label>
                                        <input
                                            type="date"
                                            wire:model="reportForm.end_date"
                                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        >
                                    </div>
                                </div>
                            </div>

                            <div class="mb-6">
                                <label class="flex items-center">
                                    <input
                                        type="checkbox"
                                        wire:model="reportForm.include_images"
                                        class="rounded border-gray-300 text-green-600 focus:ring-green-500"
                                    >
                                    <span class="ml-2 text-sm text-gray-700">Include detection images (PDF only)</span>
                                </label>
                            </div>

                            <div class="flex items-center justify-end space-x-3">
                                <button
                                    type="button"
                                    wire:click="closeReportModal"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-green-600 to-green-700 rounded-lg hover:from-green-700 hover:to-green-800 transition-all"
                                >
                                    <i class="fas fa-download mr-2"></i>
                                    Generate & Download
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
