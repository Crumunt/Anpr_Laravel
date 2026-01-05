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
            // ... add more vehicles as needed
        ];
        $filters = [
            'search' => '',
            'status' => '',
            'reason' => '',
        ];
        return view('components.anpr.flagged', [
            'vehicles' => $vehicles,
            'filters' => $filters,
            'showDetailsModal' => false,
            'selectedVehicle' => null,
            'selectedFlag' => null,
            'incidents' => [],
            'comments' => [],
            'showFlagModal' => false,
            'flagForm' => [],
            'showConfirmDialog' => false,
            'confirmTitle' => '',
            'confirmMessage' => '',
            'showToast' => false,
            'toastTitle' => '',
            'toastMessage' => '',
            'toastType' => 'info',
        ]);
    }
}

