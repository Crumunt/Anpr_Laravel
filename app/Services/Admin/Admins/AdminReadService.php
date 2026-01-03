<?php

namespace App\Services\Admin\Admins;

use App\Http\Resources\AdminResource;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class AdminReadService
{
    public function getAdmins(array $filters)
    {
        return User::select(['id', 'email', 'created_at', 'is_active'])
            ->admin()
            ->when($filters['search'] ?? null, fn($q) => $q->searchTerm($filters['search']))
            ->when($filters['role'] ?? null, fn($q) => $q->whereHas('roles', fn($r) => $r->where('name', $filters['role'])))
            ->when(
                $filters['sort_by'] ?? null,
                fn($q) => $q->sortApplicant($filters['sort_by']),
                fn($q) => $q->orderBy('created_at', 'desc')
            );
    }

    public function getDashboardCounts()
    {
        $baseQuery = User::admin();
        $counts = $baseQuery->adminCount();
        $percentages = $this->calculatePercentages();

        return [
            'total' => [
                'count' => $counts->total,
                'percentage' => $percentages['total']
            ],
            'super_admin' => [
                'count' => $counts->super_admin,
                'percentage' => $percentages['super_admin']
            ],
            'admin' => [
                'count' => $counts->admin,
                'percentage' => $percentages['admin']
            ]
        ];
    }

    private function calculatePercentages()
    {
        $dateRanges = $this->getDateRanges();

        $lastMonthCounts = User::admin()
            ->whereBetween('users.created_at', $dateRanges['last_month'])
            ->adminCount();

        $currentMonthCounts = User::admin()
            ->whereBetween('users.created_at', $dateRanges['current_month'])
            ->adminCount();

        return [
            'total' => $this->calculatePercentageChange(
                $currentMonthCounts->total,
                $lastMonthCounts->total
            ),
            'super_admin' => $this->calculatePercentageChange(
                $currentMonthCounts->super_admin,
                $lastMonthCounts->super_admin
            ),
            'admin' => $this->calculatePercentageChange(
                $currentMonthCounts->admin,
                $lastMonthCounts->admin
            ),
        ];
    }

    private function getDateRanges()
    {
        return [
            'last_month' => [
                Carbon::now()->subMonth()->startOfMonth(),
                Carbon::now()->subMonth()->endOfMonth()
            ],
            'current_month' => [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ]
        ];
    }

    private function calculatePercentageChange($current, $previous)
    {
        if ($previous > 0) {
            return round((($current - $previous) / $previous) * 100, 2);
        }
        return $current > 0 ? 100 : 0;
    }

    public function formatAdminsForList(LengthAwarePaginator $users): Collection
    {
        return $users->getCollection()->map(function ($user) {
            return AdminResource::forList($user)->resolve();
        });
    }
}
