<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\User;
use App\Models\Vehicle\Vehicle;
use App\Models\Application;
use Illuminate\Support\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;

class DashboardStatCards extends Component
{
    public array $stats = [];

    public function mount(): void
    {
        $this->loadStats();
    }

    #[On('refreshDashboard')]
    public function loadStats(): void
    {
        $this->stats = [
            'applicants' => $this->getApplicantStats(),
            'vehicles' => $this->getVehicleStats(),
            'applications' => $this->getApplicationStats(),
            'admins' => $this->getAdminStats(),
        ];
    }

    private function getApplicantStats(): array
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        $totalApplicants = User::whereHas('roles', fn($q) => $q->where('name', 'applicant'))->count();
        $newThisMonth = User::whereHas('roles', fn($q) => $q->where('name', 'applicant'))
            ->where('created_at', '>=', $currentMonth)
            ->count();
        $lastMonthCount = User::whereHas('roles', fn($q) => $q->where('name', 'applicant'))
            ->whereBetween('created_at', [$lastMonth, $lastMonthEnd])
            ->count();

        $percentChange = $lastMonthCount > 0
            ? round((($newThisMonth - $lastMonthCount) / $lastMonthCount) * 100, 1)
            : ($newThisMonth > 0 ? 100 : 0);

        return [
            'total' => $totalApplicants,
            'new_this_month' => $newThisMonth,
            'percent_change' => $percentChange,
            'trend' => $percentChange >= 0 ? 'up' : 'down',
        ];
    }

    private function getVehicleStats(): array
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        $totalVehicles = Vehicle::count();
        $newThisMonth = Vehicle::where('created_at', '>=', $currentMonth)->count();
        $lastMonthCount = Vehicle::whereBetween('created_at', [$lastMonth, $lastMonthEnd])->count();

        $percentChange = $lastMonthCount > 0
            ? round((($newThisMonth - $lastMonthCount) / $lastMonthCount) * 100, 1)
            : ($newThisMonth > 0 ? 100 : 0);

        return [
            'total' => $totalVehicles,
            'new_this_month' => $newThisMonth,
            'percent_change' => $percentChange,
            'trend' => $percentChange >= 0 ? 'up' : 'down',
        ];
    }

    private function getApplicationStats(): array
    {
        $pending = Application::whereHas('status', fn($q) => $q->where('code', 'pending'))->count();
        $approved = Application::whereHas('status', fn($q) => $q->where('code', 'approved'))->count();
        $rejected = Application::whereHas('status', fn($q) => $q->where('code', 'rejected'))->count();
        $total = Application::count();

        return [
            'total' => $total,
            'pending' => $pending,
            'approved' => $approved,
            'rejected' => $rejected,
            'approval_rate' => $total > 0 ? round(($approved / $total) * 100, 1) : 0,
        ];
    }

    private function getAdminStats(): array
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        // Get admins (excluding security and applicant roles)
        $totalAdmins = User::whereHas('roles', fn($q) => $q->whereNotIn('name', ['applicant', 'security']))->count();
        $activeAdmins = User::whereHas('roles', fn($q) => $q->whereNotIn('name', ['applicant', 'security']))
            ->where('is_active', true)
            ->count();
        $newThisMonth = User::whereHas('roles', fn($q) => $q->whereNotIn('name', ['applicant', 'security']))
            ->where('created_at', '>=', $currentMonth)
            ->count();
        $lastMonthCount = User::whereHas('roles', fn($q) => $q->whereNotIn('name', ['applicant', 'security']))
            ->whereBetween('created_at', [$lastMonth, $lastMonthEnd])
            ->count();

        $percentChange = $lastMonthCount > 0
            ? round((($newThisMonth - $lastMonthCount) / $lastMonthCount) * 100, 1)
            : ($newThisMonth > 0 ? 100 : 0);

        return [
            'total' => $totalAdmins,
            'active' => $activeAdmins,
            'new_this_month' => $newThisMonth,
            'percent_change' => $percentChange,
            'trend' => $percentChange >= 0 ? 'up' : 'down',
        ];
    }

    public function render()
    {
        return view('livewire.admin.dashboard.dashboard-stat-cards');
    }
}
