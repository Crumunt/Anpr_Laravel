<?php

namespace App\Livewire\Admin\Dashboard;

use App\ApplicantType;
use App\Models\Application;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;

class ApplicationBreakdown extends Component
{
    public array $applicantTypes = [];
    public array $monthlyTrend = [];
    public array $admins = [];

    public function mount(): void
    {
        $this->loadData();
    }

    #[On('refreshDashboard')]
    public function loadData(): void
    {
        $this->applicantTypes = $this->getApplicantTypeBreakdown();
        $this->monthlyTrend = $this->getMonthlyTrend();
        $this->admins = $this->getAdminBreakdown();
    }

    private function getApplicantTypeBreakdown(): array
    {
        $results = Application::selectRaw('applicant_type, COUNT(*) as count')
            ->whereNotNull('applicant_type')
            ->groupBy('applicant_type')
            ->get();

        // Initialize all applicant types with 0 count
        $types = [];
        foreach (ApplicantType::cases() as $type) {
            $types[$type->value] = 0;
        }

        // Fill in actual counts
        foreach ($results as $item) {
            $key = is_object($item->applicant_type) ? $item->applicant_type->value : (string) $item->applicant_type;
            $types[$key] = $item->count;
        }

        $total = array_sum($types);

        return collect($types)->map(fn($count, $type) => [
            'type' => Str::headline($type),
            'count' => $count,
            'percentage' => $total > 0 ? round(($count / $total) * 100, 1) : 0,
        ])->values()->toArray();
    }

    private function getMonthlyTrend(): array
    {
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = Application::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $months[] = [
                'month' => $date->format('M'),
                'count' => $count,
            ];
        }

        return $months;
    }

    private function getAdminBreakdown(): array
    {
        return User::whereHas('roles', fn($q) => $q->whereNotIn('name', ['applicant', 'security']))
            ->with('roles')
            ->get()
            ->groupBy(fn($user) => $user->roles->first()?->name ?? 'unknown')
            ->map(fn($users, $role) => [
                'role' => ucwords(str_replace('_', ' ', $role)),
                'count' => $users->count(),
            ])
            ->values()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.admin.dashboard.application-breakdown');
    }
}
