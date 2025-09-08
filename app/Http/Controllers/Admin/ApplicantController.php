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

    public function __construct(ApplicantService $applicantService) {
        $this->applicantService = $applicantService;
    }

    public function index(ApplicantIndexRequest $request)
    {
        try {
            $users = $this->applicantService->getApplicants(
                $request->validated(),
                $request->validated('per_page', 10)
            );

            $userDetails = $this->applicantService->formatApplicantsForDisplay($users);

            // AJAX REQUEST WAS MADE?
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'rows' => view('components.table.partials.data-rows', [
                        'rows' => $userDetails,
                        'showCheckboxes' => true,
                        'showActions' => true,
                        'headers' => ApplicationDisplayHelper::headerHelper('user_applicant', '')
                    ])->render(),
                    'pagination' => view('components.pagination', ['pagination' => $users])->render()
                ]);
            }

            // DEFAULT NO AJAX REQUEST
            // return view('admin.users.applicants.index', compact('userDetails', 'users'));
            return view('users.index', [
                'userDetails' => $userDetails,
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
        $user = User::with(['details', 'vehicles'])->findOrFail($id);

        $user_details = [
            'clsu_id' => $user->details?->clsu_id,
            'full_name' => $user->full_name,
            'name_initials' => $user->name_initial,
            'email_address' => $user->email,
            'phone_number' => $user->details?->phone_number,
            'license_number' => $user->details?->license_number,
            'applicant_type' => $user->details?->applicant_type,
            'curr_address' => $user->details?->current_address,
            'city_municipality' => $user->details?->city_municipality,
            'province' => $user->details?->province,
            'postal_code' => $user->details?->postal_code,
            'country' => $user->details?->country,
            'status_name' => $user->details?->status_name,
            'status_badge' => $user->details?->status_badge,
            'submitted_date' => $user->details?->created_at->format('F d, Y')
        ];

        $vehicle_details = $user->vehicles->map(fn($vehicle) => [
            'plate_number' => $vehicle->license_plate,
            'vehicle_make_model' => $vehicle->vehicle_make_model,
            'vehicle_year' => $vehicle->vehicle_year,
            'registration_date' => $vehicle->created_at->format('F d, Y'),
        ]);

        $gate_pass_details = $user->vehicles->map(fn($vehicle) => [
            'gate_pass' => $vehicle->assigned_gate_pass,
            'status' => $vehicle->status_badge,
            'date_issued' => $vehicle->created_at->format('F d, Y'),
            // expiry calculation to be added
            'expiry_date' => $vehicle->created_at->format('F d, Y'),
        ]);

        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Applicants', 'url' => route('admin.applicant')],
            ['label' => $user->full_name]
        ];

        return view('admin.users.applicants.show', compact('user_details', 'breadcrumbs', 'vehicle_details', 'gate_pass_details'));
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
