<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApplicantVehicleController extends Controller
{
    /**
     * Show applicant's vehicles.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return redirect('/')->with('error', 'Please login to view vehicles.');
        }

        // Load vehicles with status
        $vehicles = $user->vehicles()->with('status')->orderBy('created_at', 'desc')->paginate(10);

        $vehiclesData = $vehicles->map(function($vehicle) {
            return [
                'id' => $vehicle->id,
                'license_plate' => $vehicle->license_plate,
                'vehicle_type' => $vehicle->vehicle_type,
                'make' => $vehicle->vehicle_make,
                'model' => $vehicle->vehicle_model,
                'year' => $vehicle->vehicle_year,
                'gate_pass' => $vehicle->assigned_gate_pass,
                'status' => $vehicle->status_badge,
                'registered_date' => $vehicle->created_at->format('M d, Y'),
                'registration_expiry' => $vehicle->created_at->addYear()->format('M d, Y'),
            ];
        });

        return view('applicant.vehicles.index', compact('user', 'vehicles', 'vehiclesData'));
    }

    /**
     * Show vehicle details.
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();
        if (!$user) {
            return redirect('/')->with('error', 'Please login to view vehicle.');
        }

        $vehicle = $user->vehicles()->with('status')->findOrFail($id);

        return view('applicant.vehicles.show', compact('user', 'vehicle'));
    }
}
