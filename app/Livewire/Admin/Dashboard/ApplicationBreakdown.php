<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\ApplicantType;
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
        $results = Application::selectRaw('applicant_type_id, COUNT(*) as count')
            ->whereNotNull('applicant_type_id')
            ->groupBy('applicant_type_id')
            ->get()
            ->keyBy('applicant_type_id');

        // Get all active applicant types from the database
        $allTypes = ApplicantType::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Build the types array with counts
        $types = [];
        foreach ($allTypes as $type) {
            $types[$type->id] = [
                'label' => $type->label,
                'count' => $results->get($type->id)?->count ?? 0,
            ];
        }

        $total = collect($types)->sum('count');

        return collect($types)->map(fn($data) => [
            'type' => $data['label'],
            'count' => $data['count'],
            'percentage' => $total > 0 ? round(($data['count'] / $total) * 100, 1) : 0,
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
