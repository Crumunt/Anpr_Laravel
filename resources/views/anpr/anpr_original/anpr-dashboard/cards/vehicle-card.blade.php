@props([
    'license_plate' => '',
    'status' => 'authorized',
    'type' => 'regular',
    'type_color' => 'green',
    'icon' => 'car-side',
    'make_model' => '',
    'color' => '',
    'time' => '',
    'time_ago' => '',
    'location' => '',
    'camera' => '',
    'owner_name' => '',
    'owner_role' => ''
])

<tr class="hover:bg-{{ $status === 'authorized' ? 'green' : ($status === 'pending' ? 'amber' : 'red') }}-50 transition-colors"
    data-status="{{ $status }}"
    data-type="{{ $type }}"
    data-license-plate="{{ $license_plate }}"
    data-make-model="{{ $make_model }}"
    data-color="{{ $color }}"
    data-time="{{ $time }}"
    data-time-ago="{{ $time_ago }}"
    data-location="{{ $location }}"
    data-camera="{{ $camera }}"
    data-owner-name="{{ $owner_name }}"
    data-owner-role="{{ $owner_role }}"
>
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="flex items-center">
            <div class="flex-shrink-0 h-10 w-10 rounded-md bg-{{ $status === 'authorized' ? 'green' : ($status === 'pending' ? 'amber' : 'red') }}-100 flex items-center justify-center {{ $status === 'authorized' ? 'clsu-text' : ($status === 'pending' ? 'text-amber-600' : 'text-red-600') }}">
                <i class="fas fa-{{ $icon }}" aria-hidden="true"></i>
            </div>
            <div class="ml-4">
                <div class="flex items-center">
                    <div class="text-sm font-medium text-gray-900">{{ $license_plate }}</div>
                    <span class="ml-2 px-1.5 py-0.5 bg-{{ $type_color }}-100 text-{{ $type_color }}-800 text-xs rounded">{{ ucfirst($type) }}</span>
                </div>
                <div class="text-xs text-gray-500 mt-0.5">{{ $make_model }} • {{ $color }}</div>
            </div>
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="text-sm font-medium text-gray-900">{{ $time }}</div>
        <div class="text-xs text-gray-500 flex items-center mt-0.5">
            <i class="far fa-clock mr-1 text-gray-400" aria-hidden="true"></i> {{ $time_ago }}
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="text-sm font-medium text-gray-900">{{ $location }}</div>
        <div class="text-xs text-gray-500 flex items-center mt-0.5">
            <i class="fas fa-video mr-1 text-gray-400" aria-hidden="true"></i> {{ $camera }}
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="flex items-center">
            <div class="flex-shrink-0 h-7 w-7 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                <i class="fas fa-user text-xs" aria-hidden="true"></i>
            </div>
            <div class="ml-3">
                <div class="text-sm font-medium text-gray-900">{{ $owner_name }}</div>
                <div class="text-xs text-gray-500">{{ $owner_role }}</div>
            </div>
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $status === 'authorized' ? 'green' : ($status === 'pending' ? 'amber' : 'red') }}-100 text-{{ $status === 'authorized' ? 'green' : ($status === 'pending' ? 'amber' : 'red') }}-800">
            <span class="w-1.5 h-1.5 bg-{{ $status === 'authorized' ? 'green' : ($status === 'pending' ? 'amber' : 'red') }}-600 rounded-full mr-1.5 mt-1"></span> {{ ucfirst($status) }}
        </span>
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
        <div class="flex space-x-1.5 justify-end">
            <button class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 p-1.5 rounded-lg transition-colors tooltip" aria-label="View details" onclick="viewVehicleDetails('{{ $license_plate }}')">
                <i class="fas fa-eye" aria-hidden="true"></i>
                <span class="tooltip-text">View details</span>
            </button>
            @if($status === 'pending')
                <button class="text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 p-1.5 rounded-lg transition-colors tooltip" aria-label="Approve" onclick="approveVehicle('{{ $license_plate }}')">
                    <i class="fas fa-check" aria-hidden="true"></i>
                    <span class="tooltip-text">Approve</span>
                </button>
                <button class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-1.5 rounded-lg transition-colors tooltip" aria-label="Deny" onclick="denyVehicleInTable('{{ $license_plate }}')">
                    <i class="fas fa-times" aria-hidden="true"></i>
                    <span class="tooltip-text">Deny</span>
                </button>
            @else
                <button class="text-amber-600 hover:text-amber-900 bg-amber-50 hover:bg-amber-100 p-1.5 rounded-lg transition-colors tooltip" aria-label="Flag vehicle" onclick="flagVehicleInTable('{{ $license_plate }}')">
                    <i class="fas fa-flag" aria-hidden="true"></i>
                    <span class="tooltip-text">Flag vehicle</span>
                </button>
                <button class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-1.5 rounded-lg transition-colors tooltip" aria-label="Report issue" onclick="reportIssue('{{ $license_plate }}')">
                    <i class="fas fa-exclamation-triangle" aria-hidden="true"></i>
                    <span class="tooltip-text">Report issue</span>
                </button>
            @endif
        </div>
    </td>
</tr>