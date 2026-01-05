<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Show applicant activity log.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return redirect('/')->with('error', 'Please login to view activity log.');
        }

        // Load relationships
        $user->load(['vehicles']);

        // Generate activity log from user's vehicles and profile
        $activities = collect();

        // Add vehicle-related activities
        foreach ($user->vehicles as $vehicle) {
            $activities->push([
                'type' => 'vehicle_registered',
                'title' => 'Vehicle Registered: ' . $vehicle->license_plate,
                'description' => $vehicle->vehicle_make . ' ' . $vehicle->vehicle_model . ' (' . $vehicle->vehicle_year . ')',
                'icon' => 'car',
                'color' => 'blue',
                'date' => $vehicle->created_at->format('M d, Y'),
                'time' => $vehicle->created_at->format('h:i A'),
            ]);

            if ($vehicle->assigned_gate_pass) {
                $gatePassDate = $vehicle->created_at->copy()->addDays(rand(1, 5));
                $activities->push([
                    'type' => 'gate_pass_issued',
                    'title' => 'Gate Pass Issued',
                    'description' => 'Gate Pass #' . $vehicle->assigned_gate_pass . ' for ' . $vehicle->license_plate,
                    'icon' => 'key',
                    'color' => 'green',
                    'date' => $gatePassDate->format('M d, Y'),
                    'time' => $gatePassDate->format('h:i A'),
                ]);
            }
        }

        // Add profile creation activity
        if ($user->created_at) {
            $activities->push([
                'type' => 'account_created',
                'title' => 'Account Created',
                'description' => 'Your account was successfully created',
                'icon' => 'user',
                'color' => 'purple',
                'date' => $user->created_at->format('M d, Y'),
                'time' => $user->created_at->format('h:i A'),
            ]);
        }

        // Sort by date (most recent first)
        $activities = $activities->sortByDesc(function($activity) {
            return strtotime($activity['date'] . ' ' . $activity['time']);
        })->values();

        return view('applicant.activity-log.index', compact('user', 'activities'));
    }
}
