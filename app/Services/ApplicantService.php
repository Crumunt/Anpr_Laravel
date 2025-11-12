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

    public function getApplicants(array $filters, int $perPage = 10)
    {
        // SELECTING ONLY REQUIRED DATA FOR SPEED
        return User::select(['id', 'email', 'first_name', 'middle_name', 'last_name', 'suffix', 'created_at'])
            ->with([
                'details:user_id,clsu_id,phone_number,status_id',
                'details.status:id,status_name'
            ])
            ->applicant()
            ->withCount('vehicles')
            ->when($filters['status'] ?? null, fn($q) => $q->withStatusCode($filters['status']))
            ->when($filters['search'] ?? null, fn($q) => $q->searchTerm($filters['search']))
            ->when($filters['applicant_types'] ?? null, fn($q) => $q->applicantType($filters['applicant_types']))
            ->when(
                $filters['sort_by'] ?? null,
                fn($q) => $q->sortApplicant($filters['sort_by']),
                fn($q) => $q->orderBy('created_at', 'desc')
            )
            ->paginate($perPage);
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

    private function getPercentage($model)
    {

        $cloneModel = (clone $model);

        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();
        $startOfThisMonth = Carbon::now()->startOfMonth();
        $endOfThisMonth = Carbon::now()->endOfMonth();

        $percentageChange = 0;
        $lastMonthCount = 0;
        $currentMonthCount = 0;


        $lastMonthCount += $cloneModel->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();
        $currentMonthCount += $cloneModel->whereBetween('created_at', [$startOfThisMonth, $endOfThisMonth])->count();



        if ($lastMonthCount > 0) {
            $percentageChange = (($currentMonthCount - $lastMonthCount) / max($lastMonthCount, 1)) * 100;
        } else {
            $percentageChange = $currentMonthCount > 0 ? 100 : 0;
        }

        return round($percentageChange, 2);
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
