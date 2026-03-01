<?php

namespace App\Http\Controllers\ANPR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnprDashboardController extends Controller
{
    /**
     * Display the ANPR dashboard.
     *
     * Data is now handled by Livewire components:
     * - DashboardCards: 24-hour metrics
     * - RecentVehiclesTable: Paginated vehicle records with filtering
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Alerts count for header notification badge
        $alertsCount = $this->getAlertsCount();

        return view('anpr.dashboard.index', compact('user', 'alertsCount'));
    }

    /**
     * Get alerts count for the header notification badge.
     */
    protected function getAlertsCount(): array
    {
        // TODO: Replace with real alert data when alert system is implemented
        return [
            'total' => rand(20, 50),
            'critical' => rand(2, 8),
            'warning' => rand(5, 15),
            'info' => rand(10, 25),
        ];
    }
}
