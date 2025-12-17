<?php

namespace App\Services;

use App\Http\Resources\ApplicantResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ApplicantService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getApplicants(array $filters)
    {
        // SELECTING ONLY REQUIRED DATA FOR SPEED
        return User::select(['id', 'email', 'created_at'])
            ->applicant()
            ->withApplicationCounts()
            ->when($filters['status'] ?? null, fn($q) => $q->withStatusCode($filters['status']))
            ->when($filters['search'] ?? null, fn($q) => $q->searchTerm($filters['search']))
            ->when($filters['applicant_types'] ?? null, fn($q) => $q->applicantType($filters['applicant_types']))
            ->when(
                $filters['sort_by'] ?? null,
                fn($q) => $q->sortApplicant($filters['sort_by']),
                fn($q) => $q->orderBy('created_at','desc')
            );
    }

    public function getDashboardCounts()
    {

        $baseQuery = User::applicant();

        $counts = $baseQuery->applicantCount();

        $percentages = $this->calculatePercentages();

        return [
            'total' => [
                'count' => $counts->total,
                'percentage' => $percentages['total']
            ],
            'active' => [
                'count' => $counts->active,
                'percentage' => $percentages['active']
            ],
            'inactive' => [
                'count' => $counts->rejected,
                'percentage' => $percentages['rejected']
            ]
        ];
    }

    private function calculatePercentages()
    {
        $dateRanges = $this->getDateRanges();

        $lastMonthCounts = User::applicant()
            ->whereBetween('users.created_at', $dateRanges['last_month'])
            ->applicantCount();

        $currentMonthCounts = User::applicant()
            ->whereBetween('users.created_at', $dateRanges['current_month'])
            ->applicantCount();


        return [
            'total' => $this->calculatePercentageChange(
                $currentMonthCounts->total,
                $lastMonthCounts->total
            ),
            'active' => $this->calculatePercentageChange(
                $currentMonthCounts->active,
                $lastMonthCounts->active
            ),
            'rejected' => $this->calculatePercentageChange(
                $currentMonthCounts->rejected,
                $lastMonthCounts->rejected
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

    public function formatApplicantsForList(LengthAwarePaginator $users): Collection
    {
        return $users->getCollection()->map(function ($user) {
            return ApplicantResource::forList($user)->resolve();
        });
    }

    public function formatApplicantForDetail(User $user)
    {
        return ApplicantResource::forDetail($user)->resolve();
    }

    public function formatApplicantsForDisplay(LengthAwarePaginator $users)
    {
        // MAP DATA INTO A COLLECTION
        return $users->getCollection()->map(function ($user) {
            return (new ApplicantResource($user))->resolve();
        });
    }

}
