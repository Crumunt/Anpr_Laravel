<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ApplicationTableHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vehicle\Vehicle;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;
use Faker\Factory as Faker;
use Illuminate\Database\Eloquent\Model;

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
        $userModel = User::find(1);
        $vehicleModel = Vehicle::find(1);

        return [
            [
                'title' => 'Total Applicants',
                'totalNumber' => number_format($this->getTableCount('applicant')),
                'percent' => $this->getPercentage($userModel),
                'description' => 'Total registered applicants',
                'icon' => 'users'
            ],
            [
                'title' => 'New Applicants this Month',
                'totalNumber' => number_format($this->getTableCount('new_applicant')),
                'percent' => $this->getPercentage($userModel),
                'description' => 'Applicants who registered this month',
                'icon' => 'rfid'
            ],
            [
                'title' => 'Registered Vehicles',
                'totalNumber' => number_format($this->getTableCount('vehicle')),
                'percent' => $this->getPercentage($vehicleModel),
                'description' => 'Total registered vehicles',
                'icon' => 'car'
            ],
            [
                'title' => 'Pending Approvals',
                'totalNumber' => number_format($this->getTableCount('pending')),
                'percent' => $this->getPercentage($userModel, $vehicleModel),
                'description' => 'Applications awaiting review',
                'icon' => 'approval'
            ],
        ];

    }

    private function getTableCount($tbl)
    {
        $count = 0;
        $userModel = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['student', 'faculty', 'staff']);
        });

        switch ($tbl) {
            case 'applicant':
                $count = $userModel->distinct()->count();
                break;

            case 'new_applicant':
                $count = $userModel->whereMonth('created_at', date('m'))->distinct()->count();
                break;

            case 'vehicle':
                $count = Vehicle::count(); // You don't need to eager load for count
                break;

            case 'pending':
                $pendingVehicles = Vehicle::where('status_id', 3)->count();

                $pendingUsers = User::whereHas('roles')
                    ->where('id', '>', 4)
                    ->whereHas('statuses', function ($q) {
                        $q->where('status_id', 3);
                    })
                    ->count();

                $count = $pendingVehicles + $pendingUsers;
                break;
        }
        return $count;

        // return $this->faker->numberBetween(100, 2590);
    }

    private function getPercentage($tblModel, $supplementaryModel = null)
    {

        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();
        $startOfThisMonth = Carbon::now()->startOfMonth();
        $endOfThisMonth = Carbon::now()->endOfMonth();

        $percentageChange = 0;
        $lastMonthCount = 0;
        $currentMonthCount = 0;

        $mainQuery = $tblModel::query();


        if ($tblModel == User::class) {
            $mainQuery->whereHas('roles', function ($q) {
                $q->whereIn('name', ['student', 'faculty', 'staff']);
            });

            if ($supplementaryModel) {
                $mainQuery->whereHas('statuses', function ($q) {
                    $q->where('status_id', 3);
                });

                $lastMonthCount += $supplementaryModel->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();
                $currentMonthCount += $supplementaryModel->whereBetween('created_at', [$startOfThisMonth, $endOfThisMonth])->count();
            }
        }


        $lastMonthCount += $tblModel->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();
        $currentMonthCount += $tblModel->whereBetween('created_at', [$startOfThisMonth, $endOfThisMonth])->count();



        if ($lastMonthCount > 0) {
            $percentageChange = (($currentMonthCount - $lastMonthCount) / max($lastMonthCount, 1)) * 100;
        } else {
            $percentageChange = $currentMonthCount > 0 ? 100 : 0;
        }

        return $percentageChange;
    }

    private function getUsers()
    {
        $users = User::with('vehicles', 'statuses')
            ->whereHas('roles', function ($q) {
                $q->where('id', '>', 4);
            })->paginate(10);
        $userDetails = [];

        foreach ($users as $user) {
            $userDetails[] = [
                'id' => $user->id,
                'user_id' => $user->user_id ?? '-',
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
        foreach ($vehicles as $vehicle) {
            $rows[] = [
                'id' => $vehicle->id,
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
                'id' => $vehicle->id,
                'gate_pass' => $vehicle->assigned_gate_pass,
                'status' => ['label' => ucfirst($vehicle->status->status_name)],
                'assigned_to' => ApplicationTableHelper::getFullNameAttribute($vehicle->user->first_name, $vehicle->user->middle_name, $vehicle->user->last_name)
            ];
        }

        return $rows;
    }
}
