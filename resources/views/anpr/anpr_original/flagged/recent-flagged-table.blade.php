{{-- resources/views/components/anpr/flagged/recent-flagged-table.blade.php --}}
@props([
    'headers', // array of ['key' => string, 'label' => string]
    'rows',    // array of vehicle data
])

<div class="overflow-x-auto">
    <table id="flagged-vehicles-table" class="min-w-full divide-y divide-gray-200">
        <thead>
            <tr class="bg-gray-50">
                @foreach($headers as $header)
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <button class="flex items-center focus:outline-none" onclick="sortTable({{ $loop->index }})">
                            {{ $header['label'] }}
                            <i class="fas fa-sort ml-1 text-gray-400" aria-hidden="true"></i>
                        </button>
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200" id="flagged-vehicles-records">
            @foreach($rows as $row)
                <tr class="hover:bg-{{ $row['status'] === 'active' ? 'red' : ($row['status'] === 'pending' ? 'amber' : ($row['status'] === 'monitoring' ? 'blue' : 'green')) }}-50 transition-colors" data-status="{{ $row['status'] }}" data-reason="{{ $row['reason'] }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 rounded-md bg-{{ $row['reason'] === 'suspicious' ? 'amber' : ($row['reason'] === 'unauthorized' ? 'red' : ($row['reason'] === 'expired' ? 'orange' : ($row['reason'] === 'investigation' ? 'purple' : 'blue'))) }}-100 flex items-center justify-center text-{{ $row['reason'] === 'suspicious' ? 'amber' : ($row['reason'] === 'unauthorized' ? 'red' : ($row['reason'] === 'expired' ? 'orange' : ($row['reason'] === 'investigation' ? 'purple' : 'blue'))) }}-600">
                                <i class="fas fa-{{ $row['type'] === 'SUV' ? 'car-alt' : ($row['type'] === 'Pickup' ? 'truck-pickup' : ($row['type'] === 'Van' ? 'shuttle-van' : 'car-side')) }}" aria-hidden="true"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $row['license_plate'] }}</div>
                                <div class="flex items-center mt-1">
                                    <span class="priority-indicator priority-{{ $row['priority'] }} mr-1.5"></span>
                                    <span class="text-xs text-gray-500">{{ ucfirst($row['priority']) }} Priority</span>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $row['model'] }}</div>
                        <div class="text-xs text-gray-500 mt-0.5">{{ $row['color'] }} • {{ $row['type'] }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="flag-badge {{ $row['reason'] }}">{{ $row['reason_label'] }}</span>
                        <div class="text-xs text-gray-500 mt-1.5">{{ $row['reason_description'] }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-7 w-7 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                                <i class="fas fa-user text-xs" aria-hidden="true"></i>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900">{{ $row['flagged_by'] }}</div>
                                <div class="text-xs text-gray-500">{{ $row['flagged_by_role'] }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $row['date_flagged'] }}</div>
                        <div class="text-xs text-gray-500 flex items-center mt-0.5">
                            <i class="far fa-clock mr-1 text-gray-400" aria-hidden="true"></i> {{ $row['time_flagged'] }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="status-badge {{ $row['status'] }}">
                            <span class="w-1.5 h-1.5 bg-{{ $row['status'] === 'active' ? 'red' : ($row['status'] === 'pending' ? 'amber' : ($row['status'] === 'monitoring' ? 'blue' : 'green')) }}-600 rounded-full mr-1.5 mt-1"></span> {{ ucfirst($row['status']) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex space-x-1.5 justify-end">
                            @foreach($row['actions'] as $action)
                                <button class="{{ $action['color'] }} p-1.5 rounded-lg transition-colors tooltip" aria-label="{{ $action['label'] }}" onclick="{{ $action['onclick'] }}">
                                    <i class="fas fa-{{ $action['icon'] }}" aria-hidden="true"></i>
                                    <span class="tooltip-text">{{ $action['tooltip'] }}</span>
                                </button>
                            @endforeach
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>