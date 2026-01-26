<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApplicantDashboardController extends Controller
{
    /**
     * Show applicant dashboard.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return redirect('/')->with('error', 'Please login to view dashboard.');
        }

        // Load relationships
        $user->load(['details', 'vehicles.status']);

        // Calculate statistics
        $vehicles = $user->vehicles;
        $stats = [
            'total_vehicles' => $vehicles->count(),
            'active_vehicles' => $vehicles->filter(function($vehicle) {
                return $vehicle->status && strtolower($vehicle->status->code ?? '') === 'active';
            })->count(),
            'pending_vehicles' => $vehicles->filter(function($vehicle) {
                return $vehicle->status && strtolower($vehicle->status->code ?? '') === 'pending_verification';
            })->count(),
            'active_gate_passes' => $vehicles->filter(function($vehicle) {
                return $vehicle->assigned_gate_pass && $vehicle->status && strtolower($vehicle->status->code ?? '') === 'active';
            })->count(),
        ];

        // Get recent vehicles
        $recentVehicles = $vehicles->take(5)->map(function($vehicle) {
            return [
                'id' => $vehicle->id,
                'license_plate' => $vehicle->plate_number ?? 'N/A',
                'make_model' => trim(($vehicle->make ?? '') . ' ' . ($vehicle->model ?? '')),
                'year' => $vehicle->year ?? '',
                'status' => $vehicle->status?->status_name ?? 'Pending',
                'gate_pass' => $vehicle->assigned_gate_pass,
                'registered_date' => $vehicle->created_at?->format('M d, Y') ?? 'N/A',
            ];
        });

        return view('applicant.dashboard.index', compact('user', 'stats', 'recentVehicles'));
    }
}
