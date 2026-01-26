@props([
    'vehicles_scanned' => 1284,
    'alerts_issued' => 42,
    'flagged_vehicles' => 18,
    'rfid_passes' => 956
])

<div class="analytics-stats-grid">
    <x-anpr.anpr-dashboard.cards.stat-card
        title="VEHICLES SCANNED"
        :count="$vehicles_scanned"
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
        :count="$alerts_issued"
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
        :count="$flagged_vehicles"
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
        title="RFID PASSES"
        :count="$rfid_passes"
        icon="fa-id-card"
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