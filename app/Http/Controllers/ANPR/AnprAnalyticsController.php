<?php

namespace App\Http\Controllers\ANPR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnprAnalyticsController extends Controller
{
    /**
     * Display the analytics page.
     */
    public function index(Request $request)
    {
        $stats = [
            'total_scans_today' => rand(200, 400),
            'total_scans_week' => rand(1500, 2500),
            'avg_daily_scans' => rand(180, 300),
            'peak_hour' => '8:00 AM - 9:00 AM',
        ];

        $hourlyData = $this->getHourlyData();
        $gateDistribution = $this->getGateDistribution();
        $vehicleTypes = $this->getVehicleTypeDistribution();

        return view('anpr.analytics.index', compact('stats', 'hourlyData', 'gateDistribution', 'vehicleTypes'));
    }

    /**
     * Get hourly scan data.
     */
    protected function getHourlyData(): array
    {
        $data = [];
        for ($i = 6; $i <= 22; $i++) {
            $hour = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
            $data[] = [
                'hour' => $hour,
                'entries' => rand(5, 50),
                'exits' => rand(5, 45),
            ];
        }
        return $data;
    }

    /**
     * Get gate distribution data.
     */
    protected function getGateDistribution(): array
    {
        return [
            ['gate' => 'Main Gate', 'count' => rand(100, 200), 'percentage' => 60],
            ['gate' => 'Back Gate', 'count' => rand(40, 80), 'percentage' => 25],
            ['gate' => 'Service Gate', 'count' => rand(20, 40), 'percentage' => 15],
        ];
    }

    /**
     * Get vehicle type distribution.
     */
    protected function getVehicleTypeDistribution(): array
    {
        return [
            ['type' => 'Car', 'count' => rand(100, 150), 'percentage' => 55],
            ['type' => 'Motorcycle', 'count' => rand(50, 80), 'percentage' => 30],
            ['type' => 'Truck', 'count' => rand(15, 30), 'percentage' => 10],
            ['type' => 'Other', 'count' => rand(5, 15), 'percentage' => 5],
        ];
    }
}
