<?php

namespace App\Http\Controllers\ANPR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnprAlertsController extends Controller
{
    /**
     * Display the alerts page.
     */
    public function index(Request $request)
    {
        $stats = [
            [
                'label' => 'Total Alerts',
                'value' => 42,
                'color' => 'text-gray-900',
                'bg' => 'bg-gray-50',
                'icon' => 'fas fa-exclamation-triangle',
                'trend' => 'up',
                'trendValue' => '12%'
            ],
            [
                'label' => 'Critical',
                'value' => 8,
                'color' => 'text-red-600',
                'bg' => 'bg-red-50',
                'icon' => 'fas fa-radiation',
                'trend' => 'up',
                'trendValue' => '33%'
            ],
            [
                'label' => 'Warning',
                'value' => 14,
                'color' => 'text-amber-600',
                'bg' => 'bg-amber-50',
                'icon' => 'fas fa-exclamation-circle',
                'trend' => 'up',
                'trendValue' => '8%'
            ],
            [
                'label' => 'Resolved',
                'value' => 26,
                'color' => 'text-green-600',
                'bg' => 'bg-green-50',
                'icon' => 'fas fa-check-circle',
                'trend' => 'up',
                'trendValue' => '18%'
            ],
        ];

        $alerts = $this->getMockAlerts();

        return view('anpr.alerts.index', compact('stats', 'alerts'));
    }

    /**
     * Get mock alerts data.
     */
    protected function getMockAlerts(): array
    {
        return [
            [
                'id' => 1,
                'plate' => 'ABC 1234',
                'type' => 'Unauthorized Access',
                'priority' => 'critical',
                'time' => now()->subMinutes(5)->format('H:i:s'),
                'gate' => 'Main Gate',
                'status' => 'unresolved',
            ],
            [
                'id' => 2,
                'plate' => 'XYZ 5678',
                'type' => 'Expired Gate Pass',
                'priority' => 'warning',
                'time' => now()->subMinutes(15)->format('H:i:s'),
                'gate' => 'Back Gate',
                'status' => 'unresolved',
            ],
            [
                'id' => 3,
                'plate' => 'DEF 9012',
                'type' => 'Flagged Vehicle',
                'priority' => 'critical',
                'time' => now()->subMinutes(30)->format('H:i:s'),
                'gate' => 'Main Gate',
                'status' => 'investigating',
            ],
            [
                'id' => 4,
                'plate' => 'GHI 3456',
                'type' => 'Multiple Entry Attempts',
                'priority' => 'warning',
                'time' => now()->subHours(1)->format('H:i:s'),
                'gate' => 'Main Gate',
                'status' => 'resolved',
            ],
        ];
    }
}
