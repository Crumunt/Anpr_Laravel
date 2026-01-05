@props([
    'headers', // array of ['key' => string, 'label' => string]
    'rows',    // array of alert data
])
<div class="overflow-x-auto">
    <table id="alerts-table" class="min-w-full divide-y divide-gray-200">
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
        <tbody class="bg-white divide-y divide-gray-200" id="alert-records">
            @foreach($rows as $row)
                <tr class="hover:bg-{{ $row['priority'] === 'critical' ? 'red' : ($row['priority'] === 'high' ? 'orange' : ($row['priority'] === 'medium' ? 'amber' : ($row['priority'] === 'low' ? 'blue' : 'green'))) }}-50 transition-colors" data-priority="{{ $row['priority'] }}" data-type="{{ $row['type'] }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">#{{ $row['alert_id'] }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $row['time'] }}</div>
                        <div class="text-xs text-gray-500 flex items-center mt-0.5">
                            <i class="far fa-clock mr-1 text-gray-400" aria-hidden="true"></i>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="type-badge {{ $row['type'] }}">{{ $row['type_label'] }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 rounded-md bg-{{ $row['type'] === 'unauthorized' ? 'red' : ($row['type'] === 'suspicious' ? 'amber' : ($row['type'] === 'system' ? 'purple' : ($row['type'] === 'rfid' ? 'blue' : 'gray'))) }}-100 flex items-center justify-center text-{{ $row['type'] === 'unauthorized' ? 'red' : ($row['type'] === 'suspicious' ? 'amber' : ($row['type'] === 'system' ? 'purple' : ($row['type'] === 'rfid' ? 'blue' : 'gray'))) }}-600">
                                <i class="fas {{ $row['type'] === 'unauthorized' ? 'fa-car-alt' : ($row['type'] === 'suspicious' ? 'fa-user-secret' : ($row['type'] === 'system' ? 'fa-exclamation-circle' : ($row['type'] === 'rfid' ? 'fa-id-card' : 'fa-tools'))) }}" aria-hidden="true"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ \Illuminate\Support\Str::limit($row['details'], 40) }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">Created by: John Doe</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ explode(' (', $row['location'])[0] }}</div>
                        <div class="text-xs text-gray-500 flex items-center mt-0.5">
                            <i class="fas fa-video mr-1 text-gray-400" aria-hidden="true"></i> {{ \Illuminate\Support\Str::contains($row['location'], '(') ? trim(str_replace(')', '', explode('(', $row['location'])[1])) : 'N/A' }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="status-badge {{ $row['priority'] }}">
                            <span class="w-1.5 h-1.5 bg-{{ $row['priority'] === 'critical' ? 'red' : ($row['priority'] === 'high' ? 'orange' : ($row['priority'] === 'medium' ? 'amber' : 'blue')) }}-600 rounded-full mr-1.5 mt-1"></span> {{ ucfirst($row['priority']) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex space-x-1.5 justify-end">
                            @foreach($row['actions'] as $action)
                                <button class="{{ $action['color'] }} p-1.5 rounded-lg transition-colors tooltip" aria-label="{{ $action['label'] }}" onclick="{!! $action['onclick'] !!}">
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