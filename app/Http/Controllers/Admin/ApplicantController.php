<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ApplicationDisplayHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vehicle\Vehicle;
use Illuminate\Http\Request;

class ApplicantController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {

        $users = User::with(['vehicles', 'roles', 'details.status'])
            ->applicant()
            ->when($request->filled('status'), fn($q) => $q->withStatusCode($request->status))
            ->when($request->filled('search'), fn($q) => $q->searchTerm($request->search))
            ->when($request->filled('applicant_types'), fn($q) => $q->applicantType($request->input('applicant_types')))
            ->when(
                $request->filled('sort_by'),
                fn($q) => $q->sortApplicant($request->sort_by),
                fn($q) => $q->orderBy('created_at', 'desc')
            )
            ->paginate(10);

        $userDetails = $users->map(
            fn($user) =>
            [
                'id' => $user->id,
                'user_id' => $user->details?->clsu_id ?? '-',
                'name' => ApplicationDisplayHelper::getFullNameAttribute(
                    $user->first_name,
                    $user->middle_name,
                    $user->last_name
                ),
                'email' => $user->email,
                'phone_number' => ApplicationDisplayHelper::formatPhoneNumber($user->details?->phone_number),
                'status' => [
                    'label' => ucfirst($user->details?->status?->status_name)
                ],
                'submitted_date' => $user->created_at->format('F d, Y'),
                'vehicles' => $user->vehicles->count()
            ]
        );

        // AJAX REQUEST WAS MADE?
        if ($request->ajax()) {
            return response()->json([
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
        return view('admin.users.applicants.index', compact('userDetails', 'users'));
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
