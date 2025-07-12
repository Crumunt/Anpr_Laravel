<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Faker\Factory as Faker;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    private $faker;

    function __construct() {
        $this->faker = Faker::create();
    }

    public function index()
    {
        //
        $vehicles = $this->getVehicles();

        return view('admin.vehicles.index', compact('vehicles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
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
}
