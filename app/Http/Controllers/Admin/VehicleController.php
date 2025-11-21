<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ApplicationDisplayHelper;
use App\Http\Controllers\Controller;
use App\Models\Vehicle\Vehicle;
use Illuminate\Http\Request;
use Faker\Factory as Faker;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    private $faker;

    function __construct()
    {
        $this->faker = Faker::create();
    }

    public function index()
    {
        //
        $vehicles = $this->getVehicles();

        return view('admin.vehicles.index', compact('vehicles'));
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
        $validated = $request->validate([
            'owner_id' => ['required', 'exists:users,id'],
            'license_plate' => ['required', 'string', 'max:255'],
            'vehicle_type' => ['required', 'string', 'max:50'],
            'vehicle_make' => ['required', 'string', 'max:100'],
            'vehicle_model' => ['required', 'string', 'max:100'],
            'vehicle_year' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'assigned_gate_pass' => ['nullable', 'string', 'max:255'],
            'registration_date' => ['required', 'date'],
        ]);

        $status = \App\Models\Status::where('code', 'active')->first();

        $vehicle = Vehicle::create([
            'owner_id' => $validated['owner_id'],
            'license_plate' => $validated['license_plate'],
            'vehicle_type' => $validated['vehicle_type'],
            'vehicle_make' => $validated['vehicle_make'],
            'vehicle_model' => $validated['vehicle_model'],
            'vehicle_year' => $validated['vehicle_year'],
            'assigned_gate_pass' => $validated['assigned_gate_pass'] ?? '',
            'status_id' => $status?->id ?? \App\Models\Status::firstOrFail()->id,
        ]);

        // Optional: use registration_date for created_at override if needed
        if (!empty($validated['registration_date'])) {
            $vehicle->created_at = $validated['registration_date'];
            $vehicle->save();
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Vehicle created successfully.',
                'vehicle_id' => $vehicle->id,
            ]);
        }

        return redirect()->route('admin.vehicles')->with('success', 'Vehicle created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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

    private function getVehicles()
    {

        $vehicles = Vehicle::with('user')->paginate(10);

        $rows = [];
        foreach ($vehicles as $vehicle) {
            $rows[] = [
                'id' => $vehicle->id,
                'vehicle' => ApplicationDisplayHelper::getVehicleName($vehicle->vehicle_make, $vehicle->vehicle_model),
                'owner' => ApplicationDisplayHelper::getFullNameAttribute($vehicle->user->first_name, $vehicle->user->middle_name, $vehicle->user->last_name),
                'registration_date' => $vehicle->created_at
            ];
        }

        return ['rows' => $rows, 'vehicles' => $vehicles];
    }
}
