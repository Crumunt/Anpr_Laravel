@php
    // Get recent scans from view data or use empty array
    $recentScans = $recentScans ?? [];
@endphp

<div class="px-4 md:px-8 pb-4 md:pb-8">
    <x-anpr.anpr-dashboard.ui.data-table 
        title="Recent Vehicles"
        subtitle="Last 24 hours activity"
        search_placeholder="Search plates..."
        :total_entries="count($recentScans)"
    >
        @if(count($recentScans) > 0)
            @foreach($recentScans as $scan)
                @php
                    // Safely extract values, ensuring they're strings
                    $scanStatus = is_array($scan['status'] ?? null) ? 'unknown' : strtolower($scan['status'] ?? 'unknown');
                    $statusMap = [
                        'authorized' => ['status' => 'authorized', 'type' => 'regular', 'type_color' => 'green', 'icon' => 'car-side'],
                        'flagged' => ['status' => 'unauthorized', 'type' => 'unknown', 'type_color' => 'red', 'icon' => 'car-alt'],
                        'unknown' => ['status' => 'pending', 'type' => 'unknown', 'type_color' => 'orange', 'icon' => 'question-circle'],
                    ];
                    $scanStatusData = $statusMap[$scanStatus] ?? $statusMap['unknown'];
                    
                    $plate = is_array($scan['plate'] ?? null) ? 'N/A' : (string)($scan['plate'] ?? 'N/A');
                    $time = is_array($scan['time'] ?? null) ? 'N/A' : (string)($scan['time'] ?? 'N/A');
                    $gate = is_array($scan['gate'] ?? null) ? 'N/A' : (string)($scan['gate'] ?? 'N/A');
                @endphp
                <x-anpr.anpr-dashboard.cards.vehicle-card 
                    license_plate="{{ $plate }}"
                    status="{{ $scanStatusData['status'] }}"
                    type="{{ $scanStatusData['type'] }}"
                    type_color="{{ $scanStatusData['type_color'] }}"
                    icon="{{ $scanStatusData['icon'] }}"
                    make_model="Vehicle"
                    color="Unknown"
                    time="{{ $time }}"
                    time_ago="Just now"
                    location="{{ $gate }}"
                    camera="Camera #1"
                    owner_name="Unknown"
                    owner_role="No Record Found"
                />
            @endforeach
        @else
            <tr>
                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                    <i class="fas fa-barcode text-4xl mb-3 block" aria-hidden="true"></i>
                    <p class="text-sm">No recent vehicle scans found</p>
                </td>
            </tr>
        @endif
    </x-anpr.anpr-dashboard.ui.data-table>
</div>
