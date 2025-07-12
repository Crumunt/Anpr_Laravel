<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        $statusOptions = [
            [
                'label' => 'Pending'
            ],
            [
                'label' => 'Approved'
            ],
            [
                'label' => 'Rejected'
            ]
        ];
        $rows = [];

        foreach (range(1, 5) as $i) {
            $rows[] = [
                'id' => $this->faker->unique()->randomNumber(6),
                'name' => $this->faker->name,
                'email' => $this->faker->unique()->safeEmail,
                'phone' => $this->faker->phoneNumber,
                'status' => $this->faker->randomElement($statusOptions),
                'submitted_date' => $this->faker->date('Y-m-d', '2023-06-30'),
                'gate_pass' => $this->faker->numberBetween(0, 3),
                'vehicles' => $this->faker->numberBetween(0, 3)
            ];
        }

        return $rows;
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
