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

@php
    // Ensure all props are strings, not arrays
    $license_plate = is_array($license_plate) ? ($license_plate['plate'] ?? 'N/A') : (string)$license_plate;
    $make_model = is_array($make_model) ? json_encode($make_model) : (string)$make_model;
    $color = is_array($color) ? json_encode($color) : (string)$color;
    $time = is_array($time) ? json_encode($time) : (string)$time;
    $time_ago = is_array($time_ago) ? json_encode($time_ago) : (string)$time_ago;
    $location = is_array($location) ? json_encode($location) : (string)$location;
    $camera = is_array($camera) ? json_encode($camera) : (string)$camera;
    $owner_name = is_array($owner_name) ? json_encode($owner_name) : (string)$owner_name;
    $owner_role = is_array($owner_role) ? json_encode($owner_role) : (string)$owner_role;

    // Status classes mapping (PHP 7.4 compatible)
    $hoverClass = 'hover:bg-gray-50';
    $bgClass = 'bg-gray-100';
    $textClass = 'text-gray-600';
    $badgeBgClass = 'bg-gray-100';
    $badgeTextClass = 'text-gray-800';
    $dotBgClass = 'bg-gray-600';

    switch($status) {
        case 'authorized':
            $hoverClass = 'hover:bg-green-50';
            $bgClass = 'bg-green-100';
            $textClass = 'text-green-600';
            $badgeBgClass = 'bg-green-100';
            $badgeTextClass = 'text-green-800';
            $dotBgClass = 'bg-green-600';
            break;
        case 'pending':
            $hoverClass = 'hover:bg-amber-50';
            $bgClass = 'bg-amber-100';
            $textClass = 'text-amber-600';
            $badgeBgClass = 'bg-amber-100';
            $badgeTextClass = 'text-amber-800';
            $dotBgClass = 'bg-amber-600';
            break;
        case 'unauthorized':
            $hoverClass = 'hover:bg-red-50';
            $bgClass = 'bg-red-100';
            $textClass = 'text-red-600';
            $badgeBgClass = 'bg-red-100';
            $badgeTextClass = 'text-red-800';
            $dotBgClass = 'bg-red-600';
            break;
    }

    $typeColorBg = 'bg-gray-100';
    $typeColorText = 'text-gray-800';
    switch($type_color) {
        case 'green':
            $typeColorBg = 'bg-green-100';
            $typeColorText = 'text-green-800';
            break;
        case 'blue':
            $typeColorBg = 'bg-blue-100';
            $typeColorText = 'text-blue-800';
            break;
        case 'red':
            $typeColorBg = 'bg-red-100';
            $typeColorText = 'text-red-800';
            break;
        case 'orange':
            $typeColorBg = 'bg-orange-100';
            $typeColorText = 'text-orange-800';
            break;
        case 'purple':
            $typeColorBg = 'bg-purple-100';
            $typeColorText = 'text-purple-800';
            break;
    }
@endphp

<tr class="{{ $hoverClass }} transition-colors"
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
            <div class="flex-shrink-0 h-10 w-10 rounded-md {{ $bgClass }} flex items-center justify-center {{ $status === 'authorized' ? 'clsu-text' : $textClass }}">
                <i class="fas fa-{{ $icon }}" aria-hidden="true"></i>
            </div>
            <div class="ml-4">
                <div class="flex items-center">
                    <div class="text-sm font-medium text-gray-900">{{ $license_plate }}</div>
                    <span class="ml-2 px-1.5 py-0.5 {{ $typeColorBg }} {{ $typeColorText }} text-xs rounded">{{ ucfirst($type) }}</span>
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
        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $badgeBgClass }} {{ $badgeTextClass }}">
            <span class="w-1.5 h-1.5 {{ $dotBgClass }} rounded-full mr-1.5 mt-1"></span> {{ ucfirst($status) }}
        </span>
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
        <div class="flex space-x-2 justify-end">
            <button class="inline-flex items-center px-3 py-1.5 text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors text-xs font-medium" aria-label="View details" onclick="viewVehicleDetails('{{ $license_plate }}')">
                <i class="fas fa-eye mr-1.5" aria-hidden="true"></i>
                View
            </button>
            @if($status === 'pending')
                <button class="inline-flex items-center px-3 py-1.5 text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 rounded-lg transition-colors text-xs font-medium" aria-label="Approve" onclick="approveVehicle('{{ $license_plate }}')">
                    <i class="fas fa-check mr-1.5" aria-hidden="true"></i>
                    Approve
                </button>
                <button class="inline-flex items-center px-3 py-1.5 text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 rounded-lg transition-colors text-xs font-medium" aria-label="Deny" onclick="denyVehicleInTable('{{ $license_plate }}')">
                    <i class="fas fa-times mr-1.5" aria-hidden="true"></i>
                    Deny
                </button>
            @else
                <button class="inline-flex items-center px-3 py-1.5 text-amber-600 hover:text-amber-900 bg-amber-50 hover:bg-amber-100 rounded-lg transition-colors text-xs font-medium" aria-label="Flag vehicle" onclick="flagVehicleInTable('{{ $license_plate }}')">
                    <i class="fas fa-flag mr-1.5" aria-hidden="true"></i>
                    Flag
                </button>
                <button class="inline-flex items-center px-3 py-1.5 text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 rounded-lg transition-colors text-xs font-medium" aria-label="Report issue" onclick="reportIssue('{{ $license_plate }}')">
                    <i class="fas fa-exclamation-triangle mr-1.5" aria-hidden="true"></i>
                    Report
                </button>
            @endif
        </div>
    </td>
</tr>
