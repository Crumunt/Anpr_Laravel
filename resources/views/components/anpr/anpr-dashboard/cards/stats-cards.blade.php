@php
    // Get stats from view data or use defaults
    $stats = $stats ?? [];
    
    // Safely extract values, ensuring they're not arrays
    $totalVehicles = isset($stats['total_vehicles']) && !is_array($stats['total_vehicles']) ? (int)$stats['total_vehicles'] : 0;
    $approvedVehicles = isset($stats['approved_vehicles']) && !is_array($stats['approved_vehicles']) ? (int)$stats['approved_vehicles'] : 0;
    $scansToday = isset($stats['scans_today']) && !is_array($stats['scans_today']) ? (int)$stats['scans_today'] : 0;
    $activeCameras = isset($stats['active_cameras']) && !is_array($stats['active_cameras']) ? (int)$stats['active_cameras'] : 0;
    $alertsToday = isset($stats['alerts_today']) && !is_array($stats['alerts_today']) ? (int)$stats['alerts_today'] : 0;
    $flaggedVehicles = isset($stats['flagged_vehicles']) && !is_array($stats['flagged_vehicles']) ? (int)$stats['flagged_vehicles'] : 0;
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 p-4 md:p-8">
    <x-anpr.anpr-dashboard.cards.stat-card
        title="VEHICLES SCANNED"
        :count="$scansToday"
        icon="fa-car"
        iconGradient="linear-gradient(135deg, #e6f7ef, #d1f0e2)"
        iconColor="clsu-text"
        :percent="24"
        percentColor="text-green-600"
        percentIcon="fa-arrow-up"
        percentText="from yesterday"
        :progress="75"
        progressBarColor="clsu-bg"
        progressBg="bg-gray-100"
    />
    <x-anpr.anpr-dashboard.cards.stat-card
        title="ALERTS ISSUED"
        :count="$alertsToday"
        icon="fa-exclamation-triangle"
        iconGradient="linear-gradient(135deg, #fff7e6, #ffeacc)"
        iconColor="text-amber-600"
        :percent="12"
        percentColor="text-amber-600"
        percentIcon="fa-arrow-up"
        percentText="from yesterday"
        :progress="42"
        progressBarColor="bg-amber-500"
        progressBg="bg-gray-100"
    />
    <x-anpr.anpr-dashboard.cards.stat-card
        title="FLAGGED VEHICLES"
        :count="$flaggedVehicles"
        icon="fa-flag"
        iconGradient="linear-gradient(135deg, #ffe6e6, #ffcccc)"
        iconColor="text-red-600"
        :percent="5"
        percentColor="text-red-600"
        percentIcon="fa-arrow-up"
        percentText="from yesterday"
        :progress="18"
        progressBarColor="bg-red-500"
        progressBg="bg-gray-100"
    />
    <x-anpr.anpr-dashboard.cards.stat-card
        title="ACTIVE CAMERAS"
        :count="$activeCameras"
        icon="fa-video"
        iconGradient="linear-gradient(135deg, #e6e6ff, #ccccff)"
        iconColor="text-indigo-600"
        :percent="8"
        percentColor="text-indigo-600"
        percentIcon="fa-arrow-up"
        percentText="from yesterday"
        :progress="60"
        progressBarColor="bg-indigo-500"
        progressBg="bg-gray-100"
    />
</div>
