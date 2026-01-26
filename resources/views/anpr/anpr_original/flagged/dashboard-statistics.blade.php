@props([
    'totalFlagged' => ['count' => 36, 'change' => '8%', 'isIncrease' => true],
    'activeFlags' => ['count' => 18, 'change' => '12%', 'isIncrease' => true],
    'pendingReview' => ['count' => 7, 'change' => '3%', 'isIncrease' => false],
    'resolved' => ['count' => 11, 'change' => '15%', 'isIncrease' => true]
])
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 p-4 sm:p-6 lg:p-8">
    <x-anpr.flagged.stat-card
        title="Total Flagged"
        :count="$totalFlagged['count']"
        :change="$totalFlagged['change']"
        :isIncrease="$totalFlagged['isIncrease']"
        icon="fa-flag"
        color="amber"
    />
    <x-anpr.flagged.stat-card
        title="Active Flags"
        :count="$activeFlags['count']"
        :change="$activeFlags['change']"
        :isIncrease="$activeFlags['isIncrease']"
        icon="fa-exclamation-circle"
        color="red"
    />
    <x-anpr.flagged.stat-card
        title="Pending Review"
        :count="$pendingReview['count']"
        :change="$pendingReview['change']"
        :isIncrease="$pendingReview['isIncrease']"
        icon="fa-clock"
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