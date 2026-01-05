<?php

namespace App\Http\Controllers\ANPR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnprFlaggedController extends Controller
{
    public function index()
    {
        // Example data for demonstration
        $vehicles = [
            [
                'licensePlate' => 'PQR-987',
                'model' => 'Mitsubishi Montero',
                'color' => 'Black',
                'type' => 'SUV',
                'priority' => 'high',
                'reason' => 'suspicious',
                'reasonLabel' => 'Suspicious Activity',
                'description' => 'Multiple unauthorized access attempts',
                'flaggedBy' => 'John Doe',
                'flaggedByRole' => 'Security Officer',
                'dateFlagged' => 'Mar 28, 2025',
                'timeFlagged' => '14:15:02',
                'status' => 'active',
            ],
            [
                'licensePlate' => 'ABC-123',
                'model' => 'Toyota Vios',
                'color' => 'White',
                'type' => 'Sedan',
                'priority' => 'medium',
                'reason' => 'expired',
                'reasonLabel' => 'Expired Gate Pass',
                'description' => 'Gate pass expired 30 days ago',
                'flaggedBy' => 'System',
                'flaggedByRole' => 'Automated',
                'dateFlagged' => 'Mar 25, 2025',
                'timeFlagged' => '09:30:00',
                'status' => 'active',
            ],
            [
                'licensePlate' => 'XYZ-456',
                'model' => 'Honda Civic',
                'color' => 'Red',
                'type' => 'Sedan',
                'priority' => 'low',
                'reason' => 'unregistered',
                'reasonLabel' => 'Unregistered Vehicle',
                'description' => 'Vehicle not in database',
                'flaggedBy' => 'Maria Santos',
                'flaggedByRole' => 'Security Officer',
                'dateFlagged' => 'Mar 20, 2025',
                'timeFlagged' => '11:45:00',
                'status' => 'resolved',
            ],
        ];

        return view('anpr.flagged.index', [
            'vehicles' => $vehicles,
        ]);
    }
}

