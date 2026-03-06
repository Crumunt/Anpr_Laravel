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
            'expiring_soon' => $vehicles->filter(function($vehicle) {
                // Only count active vehicles that are expiring soon
                return $vehicle->status && strtolower($vehicle->status->code ?? '') === 'active' && $vehicle->isExpiringSoon();
            })->count(),
            'expired' => $vehicles->filter(function($vehicle) {
                // Only count vehicles that were active but are now expired
                return $vehicle->status && strtolower($vehicle->status->code ?? '') === 'active' && $vehicle->isExpired();
            })->count(),
        ];

        // Get recent vehicles with expiration info
        $recentVehicles = $vehicles->take(5)->map(function($vehicle) {
            return [
                'id' => $vehicle->id,
                'license_plate' => $vehicle->plate_number ?? 'N/A',
                'make_model' => trim(($vehicle->make ?? '') . ' ' . ($vehicle->model ?? '')),
                'year' => $vehicle->year ?? '',
                'status' => $vehicle->status?->status_name ?? 'Pending',
                'status_code' => $vehicle->status?->code ?? 'pending',
                'gate_pass' => $vehicle->assigned_gate_pass,
                'registered_date' => $vehicle->created_at?->format('M d, Y') ?? 'N/A',
                'expires_at' => $vehicle->expires_at?->format('M d, Y'),
                'days_until_expiration' => $vehicle->days_until_expiration,
                'time_until_expiration' => $vehicle->time_until_expiration,
                'expiration_status' => $vehicle->expiration_status,
                'is_expiring_soon' => $vehicle->isExpiringSoon(),
                'is_expired' => $vehicle->isExpired(),
                'can_renew' => $vehicle->canRenew(),
                'is_renewal' => $vehicle->is_renewal,
            ];
        });

        return view('applicant.dashboard.index', compact('user', 'stats', 'recentVehicles'));
    }
}
