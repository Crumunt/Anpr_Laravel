<?php

namespace App\Http\Controllers\ANPR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicle\Vehicle;

class AnprDashboardController extends Controller
{
    /**
     * Display the ANPR dashboard.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Get statistics for the dashboard
        $stats = $this->getDashboardStats();

        // Get recent vehicle scans (mock data for now)
        $recentScans = $this->getRecentScans();

        // Get alerts count
        $alertsCount = $this->getAlertsCount();

        return view('anpr.dashboard.index', compact('user', 'stats', 'recentScans', 'alertsCount'));
    }

    /**
     * Get dashboard statistics.
     */
    protected function getDashboardStats(): array
    {
        $totalVehicles = Vehicle::count();
        $approvedVehicles = Vehicle::whereHas('status', fn($q) => $q->where('code', 'approved'))->count();

        return [
            'total_vehicles' => $totalVehicles,
            'approved_vehicles' => $approvedVehicles,
            'scans_today' => rand(150, 300), // Mock data
            'active_cameras' => 3,
            'alerts_today' => rand(5, 15), // Mock data
            'flagged_vehicles' => rand(2, 8), // Mock data
        ];
    }

    /**
     * Get recent vehicle scans.
     */
    protected function getRecentScans(): array
    {
        // Mock data for recent scans
        return [
            [
                'plate' => 'ABC 1234',
                'time' => now()->subMinutes(2)->format('H:i:s'),
                'gate' => 'Main Gate',
                'direction' => 'Entry',
                'status' => 'Authorized',
            ],
            [
                'plate' => 'XYZ 5678',
                'time' => now()->subMinutes(5)->format('H:i:s'),
                'gate' => 'Back Gate',
                'direction' => 'Exit',
                'status' => 'Authorized',
            ],
            [
                'plate' => 'DEF 9012',
                'time' => now()->subMinutes(8)->format('H:i:s'),
                'gate' => 'Main Gate',
                'direction' => 'Entry',
                'status' => 'Flagged',
            ],
            [
                'plate' => 'GHI 3456',
                'time' => now()->subMinutes(12)->format('H:i:s'),
                'gate' => 'Main Gate',
                'direction' => 'Entry',
                'status' => 'Authorized',
            ],
            [
                'plate' => 'JKL 7890',
                'time' => now()->subMinutes(15)->format('H:i:s'),
                'gate' => 'Back Gate',
                'direction' => 'Exit',
                'status' => 'Unknown',
            ],
        ];
    }

    /**
     * Get alerts count.
     */
    protected function getAlertsCount(): array
    {
        return [
            'total' => rand(20, 50),
            'critical' => rand(2, 8),
            'warning' => rand(5, 15),
            'info' => rand(10, 25),
        ];
    }
}
