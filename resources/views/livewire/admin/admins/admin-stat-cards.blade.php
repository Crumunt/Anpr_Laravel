<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
    <x-dashboard.card title="Total Admins" totalNumber="{{ $dashboardData['total']['count'] ?? 0 }}"
        percent="{{ $dashboardData['total']['percentage'] ?? 0 }}" description="Total administrators" icon="users" />
    <x-dashboard.card title="Super Admins" color="purple" totalNumber="{{ $dashboardData['super_admin']['count'] ?? 0 }}"
        percent="{{ $dashboardData['super_admin']['percentage'] ?? 0 }}" description="System super administrators" icon="shield" />
    <x-dashboard.card title="Admins" color="blue" totalNumber="{{ $dashboardData['admin']['count'] ?? 0 }}"
        percent="{{ $dashboardData['admin']['percentage'] ?? 0 }}" description="Regular administrators"
        icon="user" />
</div>
