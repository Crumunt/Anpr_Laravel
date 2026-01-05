@props([
    'searchPlaceholder' => 'Search license plates...',
    'statusOptions' => ['All Statuses', 'Active', 'Pending', 'Monitoring', 'Resolved'],
    'reasonOptions' => ['All Reasons', 'Suspicious Activity', 'Unauthorized Access', 'Expired Registration', 'Under Investigation', 'Parking Violation'],
])
<div class="px-4 sm:px-6 lg:px-8 pb-4 sm:pb-6 lg:pb-8">
    <div class="glass-card overflow-hidden">
        <div class="bg-gradient-to-r from-gray-50 to-white px-4 sm:px-6 py-4 border-b flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 mr-3">
                    <i class="fas fa-flag" aria-hidden="true"></i>
                </div>
                <div>
                    <h2 class="font-semibold text-gray-800 text-lg">Flagged Vehicles Management</h2>
                    <p class="text-sm text-gray-500">Monitor and manage flagged vehicles</p>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                <div class="relative">
                    <input type="text" id="flag-search" placeholder="{{ $searchPlaceholder }}" class="py-2 pl-10 pr-4 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 w-full sm:w-64" aria-label="Search flagged vehicles">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm" aria-hidden="true"></i>
                </div>
                <select id="status-filter" class="text-sm border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 py-2 pl-4 pr-8 bg-white w-full sm:w-40" aria-label="Filter by status">
                    @foreach($statusOptions as $option)
                        <option>{{ $option }}</option>
                    @endforeach
                </select>
                <select id="reason-filter" class="text-sm border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 py-2 pl-4 pr-8 bg-white w-full sm:w-40" aria-label="Filter by reason">
                    @foreach($reasonOptions as $option)
                        <option value="">{{ $option }}</option>
                    @endforeach
                </select>
                <button class="px-4 py-2 bg-amber-600 text-white rounded-lg text-sm font-medium hover:bg-amber-700 transition-colors flex items-center shadow-md" onclick="openFlagVehicleModal()">
                    <i class="fas fa-flag mr-2" aria-hidden="true"></i> Flag Vehicle
                </button>
            </div>
        </div>
        {{ $slot }}
    </div>
</div>