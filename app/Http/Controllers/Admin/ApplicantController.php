<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ApplicationDisplayHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicantIndexRequest;
use App\Http\Resources\ApplicantResource;
use App\Models\User;
use App\Services\ActivityLogService;
use App\Services\ApplicantService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ApplicantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected ApplicantService $applicantService;

    public function __construct(ApplicantService $applicantService)
    {
        $this->applicantService = $applicantService;
    }

    public function index()
    {
        return view("admin.users.applicants.index", [
            "routeName" => "admin.applicant",
        ]);
    }

    /**
     * Display the archived applicants listing.
     */
    public function archived()
    {
        return view("admin.users.applicants.archived", [
            "routeName" => "admin.applicant.archived",
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::with(
            relations: ["details", "vehicles", "applications", "documents"],
        )->findOrFail($id);

        $applicant_details = $this->applicantService->formatApplicantForDetail(
            $user,
        );

        $extracted_data = $this->extractApplicantData($applicant_details);

        // Get formatted activity logs for this user
        $activities = ActivityLogService::getFormattedActivities($user);

        $breadcrumbs = [
            ["label" => "Dashboard", "url" => route("admin.dashboard")],
            ["label" => "Applicants", "url" => route("admin.applicant")],
            ["label" => $user->details?->full_name],
        ];

        return view("admin.users.applicants.show", [
            ...$extracted_data,
            "breadcrumbs" => $breadcrumbs,
            "activities" => $activities,
        ]);
    }

    private function extractApplicantData(array $applicant_details): array
    {
        return [
            "applicant_details" => $applicant_details,
            "personal_information" => $applicant_details["personal_information"] ?? [],
            "address_information" => $applicant_details['address_information'] ?? [],
            "vehicle_details" => $applicant_details["vehicle_details"] ?? [],
            "application_details" =>
                $applicant_details["application_details"] ?? [],
            "application_documents" => $applicant_details["documents"],
        ];
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
