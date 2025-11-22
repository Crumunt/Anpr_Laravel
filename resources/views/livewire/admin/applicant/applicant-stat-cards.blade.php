<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
    <x-dashboard.card title="Total {{$userType}}" totalNumber="{{ $dashboardData['total']['count'] ?? 0 }}"
        percent="{{ $dashboardData['total']['percentage'] ?? 0 }}" description="Total system users" icon="users" />
    <x-dashboard.card title="Active {{$userType}}" totalNumber="{{ $dashboardData['active']['count'] ?? 0 }}"
        percent="{{ $dashboardData['active']['percentage'] ?? 0 }}" description="Currently active users" icon="check" />
    <x-dashboard.card title="Inactive {{$userType}}" totalNumber="{{ $dashboardData['inactive']['count'] ?? 0 }}"
        percent="{{ $dashboardData['inactive']['percentage'] ?? 0 }}" description="Currently inactive users"
        icon="ban" />
</div>

