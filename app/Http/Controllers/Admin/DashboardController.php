<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
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
        $users = User::with('vehicles', 'statuses')->where('role_id', '>', 4)->paginate(10);
        $userDetails = [];

        foreach ($users as $user) {
            $userDetails[] = [
                'id' => $user->user_id ?? '-',
                'name' => $this->getFullNameAttribute($user->first_name, $user->middle_name, $user->last_name),
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'status' => ['label' => ucfirst($user->statuses->status_name)],
                'submitted_date' => $user->created_at,
                'vehicles' => count($user->vehicles)
            ];
        }

        return $userDetails;
    }

    private function getFullNameAttribute($first_name, $middle_name, $last_name)
    {
        $middle_initial = $this->getMiddleInitialsAttribute($middle_name);
        return $first_name . ' ' . $middle_initial . ' ' . $last_name;
    }

    private function getMiddleInitialsAttribute($middle_name)
    {
        if (!$middle_name)
            return '';

        // Extract each part (in case of compound middle names)
        $parts = preg_split('/\s+/', $middle_name);
        $initials = array_map(fn($part) => strtoupper(mb_substr($part, 0, 1)) . '.', $parts);

        return implode('', $initials); // e.g., "R.J."
    }

    private function getVehicles()
    {
        $rows = [];

        $vehicles = [
            'Toyota Fortuner',
            'Honda Civic',
            'Mitsubishi Montero Sport',
            'Hyundai Tucson',
            'Ford Ranger',
            'Nissan Navara',
            'Kia Stonic',
            'Isuzu D-Max',
            'Chevrolet Trailblazer',
            'Mazda CX-5',
        ];

        foreach (range(1, 5) as $i) {
            $rows[] = [
                'vehicle' => $this->faker->randomElement($vehicles),
                'owner' => $this->faker->name(),
                'registration_date' => $this->faker->date()
            ];
        }

        return $rows;
    }

    private function getGatePasses()
    {
        $rows = [];

        $status = [
            ['label' => 'Active'],
            ['label' => 'Inactive'],
            ['label' => 'Pending'],
            ['label' => 'Default'],
        ];

        foreach (range(1, 5) as $i) {
            $rows[] = [
                'gate_pass' => $this->faker->unique()->randomNumber(6),
                'status' => $this->faker->randomElement($status),
                'assigned_to' => $this->faker->name()
            ];
        }

        return $rows;
    }
}
