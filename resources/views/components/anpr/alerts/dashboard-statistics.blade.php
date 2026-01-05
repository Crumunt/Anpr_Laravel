@props([
    'totalAlerts' => ['count' => 42, 'change' => '12%', 'isIncrease' => true],
    'critical' => ['count' => 8, 'change' => '33%', 'isIncrease' => true],
    'high' => ['count' => 14, 'change' => '8%', 'isIncrease' => true],
    'medium' => ['count' => 12, 'change' => '5%', 'isIncrease' => false],
    'resolved' => ['count' => 26, 'change' => '18%', 'isIncrease' => true]
])
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 p-4 sm:p-6 lg:p-8">
    <x-anpr.flagged.stat-card
        title="Total Alerts"
        :count="$totalAlerts['count']"
        :change="$totalAlerts['change']"
        :isIncrease="$totalAlerts['isIncrease']"
        icon="fa-exclamation-triangle"
        color="amber"
    />
    <x-anpr.flagged.stat-card
        title="Critical"
        :count="$critical['count']"
        :change="$critical['change']"
        :isIncrease="$critical['isIncrease']"
        icon="fa-radiation"
        color="red"
    />
    <x-anpr.flagged.stat-card
        title="High Priority"
        :count="$high['count']"
        :change="$high['change']"
        :isIncrease="$high['isIncrease']"
        icon="fa-exclamation-circle"
        color="orange"
    />
    <x-anpr.flagged.stat-card
        title="Medium Priority"
        :count="$medium['count']"
        :change="$medium['change']"
        :isIncrease="$medium['isIncrease']"
        icon="fa-exclamation"
        color="amber"
    />
    <x-anpr.flagged.stat-card
        title="Resolved"
        :count="$resolved['count']"
        :change="$resolved['change']"
        :isIncrease="$resolved['isIncrease']"
        icon="fa-check-circle"
        color="green"
    />
</div> 