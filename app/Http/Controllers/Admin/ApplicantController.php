<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ApplicationDisplayHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicantIndexRequest;
use App\Http\Resources\ApplicantResource;
use App\Models\User;
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

    public function index(ApplicantIndexRequest $request)
    {
        try {
            $users = $this->applicantService->getApplicants(
                $request->validated(),
                $request->validated('per_page', 10)
            );

            $dashboardCardData = $this->applicantService->getDashboardCounts();
            $userDetails = $this->applicantService->formatApplicantsForList($users);

            // AJAX REQUEST WAS MADE?
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'rows' => view('components.table.partials.data-rows', [
                        'rows' => $userDetails,
                        'showCheckboxes' => true,
                        'showActions' => true,
                        'headers' => ApplicationDisplayHelper::headerHelper('user_applicant', ''),
                        'type' => 'applicant',
                    ])->render(),
                    'pagination' => view('components.pagination', ['pagination' => $users])->render()
                ]);
            }

            // DEFAULT NO AJAX REQUEST
            // return view('admin.users.applicants.index', compact('userDetails', 'users'));
            return view('users.index', [
                'userDetails' => $userDetails,
                'dashboardData' => $dashboardCardData,
                'pagination' => $users,
                'routeName' => 'admin.applicant',
                'pageTitle' => 'Applicant Management',
                'showAdmin' => false,
                'tableCaption' => 'All Applicants'
            ]);
        } catch (ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'toast' => view('components.toast', [
                        'type' => 'error',
                        'message' => 'Failed to load data. Please try again.'
                    ])->render()
                ], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            Log::error('Failed to load applicant data', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                // 'user_id' => auth()->id(),
                'request_data' => $request->validated(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'toast' => view('components.toast', [
                        'type' => 'error',
                        'message' => 'Failed to load data. Please try again.'
                    ])->render()
                ], 500);
            }
            return redirect()->back()->with('error', 'Failed to load data. Please try again.');
        }
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
        $user = User::with(relations: ['details', 'vehicles'])->findOrFail($id);

        $applicant_details = $this->applicantService->formatApplicantForDetail($user);

        $extracted_data = $this->extractApplicantData($applicant_details);

        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Applicants', 'url' => route('admin.applicant')],
            ['label' => $user->full_name]
        ];

        return view('admin.users.applicants.show', [
            ...$extracted_data, 
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    private function extractApplicantData(array $applicant_details): array
    {
        return [
            'applicant_details' => $applicant_details,
            'user_details' => $applicant_details['user_details'] ?? [],
            'vehicle_details' => $applicant_details['vehicle_details'] ?? [],
            'gate_pass_details' => $applicant_details['gate_pass_details'] ?? [],
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

    /**
     * Lightweight search for applicants/users used by vehicle owner selection.
     */
    public function search(Request $request)
    {
        $q = $request->get('q', '');
        if (trim($q) === '') {
            return response()->json(['data' => []]);
        }

        $users = User::query()
            ->with('details')
            ->searchTerm($q)
            ->limit(10)
            ->get()
            ->map(function (User $user) {
                return [
                    'id' => $user->id,
                    'name' => $user->full_name,
                    'clsu_id' => $user->details?->clsu_id,
                    'email' => $user->email,
                ];
            })
            ->values();

        return response()->json([
            'data' => $users,
        ]);
    }

}
