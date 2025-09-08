<?php

namespace App\Services;

use App\Http\Resources\ApplicantResource;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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
        return User::with(['vehicles', 'roles', 'details.status'])
            ->applicant()
            ->withCount('vehicles')
            ->when($filters['status'], fn($q) => $q->withStatusCode($filters['status']))
            ->when($filters['search'], fn($q) => $q->searchTerm($filters['search']))
            ->when($filters['applicant_types'], fn($q) => $q->applicantType($filters['applicant_types']))
            ->when(
                $filters['sort_by'],
                fn($q) => $q->sortApplicant($filters['sort_by']),
                fn($q) => $q->orderBy('created_at', 'desc')
            )
            ->paginate($perPage);
    }

    public function formatApplicantsForDisplay(LengthAwarePaginator $users)
    {
        return ApplicantResource::collection($users);
    }

}
