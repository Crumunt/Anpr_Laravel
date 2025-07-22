<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ApplicationTableHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vehicle\Vehicle;
use Illuminate\Http\Request;
use Faker\Factory as Faker;

class DashboardController extends Controller
{
    //
    private $faker;

    function __construct()
    {
        $this->faker = Faker::create();
    }

    public function index()
    {
        $cardData = $this->getCardData();
        $users = $this->getUsers();
        $vehicles = $this->getVehicles();
        $gatePasses = $this->getGatePasses();

        return view('admin.dashboard', compact('cardData', 'users', 'vehicles', 'gatePasses'));

    }

    private function getCardData()
    {

        return [
            [
                'title' => 'Total Applicants',
                'totalNumber' => number_format($this->getTableCount()),
                'percent' => $this->getPercentage(),
                'description' => 'Total registered applicants',
                'icon' => 'users'
            ],
            [
                'title' => 'Active Gate Pass Stickers',
                'totalNumber' => number_format($this->getTableCount()),
                'percent' => $this->getPercentage(),
                'description' => 'Currently Active RFID Tags',
                'icon' => 'rfid'
            ],
            [
                'title' => 'Registered Vehicles',
                'totalNumber' => number_format($this->getTableCount()),
                'percent' => $this->getPercentage(),
                'description' => 'Total registered vehicles',
                'icon' => 'car'
            ],
            [
                'title' => 'Pending Approvals',
                'totalNumber' => number_format($this->getTableCount()),
                'percent' => $this->getPercentage(),
                'description' => 'Applications awaiting review',
                'icon' => 'approval'
            ],
        ];

    }

    private function getTableCount()
    {
        return $this->faker->numberBetween(100, 2590);
    }

    private function getPercentage()
    {
        return $this->faker->numberBetween(-50, 16);
    }

    private function getUsers()
    {
        $users = User::with('vehicles', 'statuses')
                    ->whereHas('roles', function($q) {
                        $q->where('id', '>', 4);
                    })->paginate(10);
        $userDetails = [];

        foreach ($users as $user) {
            $userDetails[] = [
                'id' => $user->user_id ?? '-',
                'name' => ApplicationTableHelper::getFullNameAttribute($user->first_name, $user->middle_name, $user->last_name),
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'status' => ['label' => ucfirst($user->statuses->status_name)],
                'submitted_date' => $user->created_at,
                'vehicles' => count($user->vehicles)
            ];
        }

        return $userDetails;
    }

    private function getVehicles()
    {
        $vehicles = Vehicle::with('user')->paginate(10);

        $rows = [];
        foreach($vehicles as $vehicle) {
            $rows[] = [
                'vehicle' => ApplicationTableHelper::getVehicleName($vehicle->vehicle_make, $vehicle->vehicle_model),
                'owner' => ApplicationTableHelper::getFullNameAttribute($vehicle->user->first_name, $vehicle->user->middle_name, $vehicle->user->last_name),
                'registration_date' => $vehicle->created_at
            ];
        }

        return $rows;
    }

    private function getGatePasses()
    {
        $vehicles = Vehicle::with('user', 'status')->paginate(10);

        $rows = [];
        foreach ($vehicles as $vehicle) {
            $rows[] = [
                'gate_pass' => $vehicle->assigned_gate_pass,
                'status' => ['label' => ucfirst( $vehicle->status->status_name)],
                'assigned_to' => ApplicationTableHelper::getFullNameAttribute($vehicle->user->first_name, $vehicle->user->middle_name, $vehicle->user->last_name)
            ];
        }

        return $rows;
    }
}
