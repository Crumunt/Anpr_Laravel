<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ApplicationTableHelper;
use App\Http\Controllers\Controller;
use App\Models\Vehicle\Vehicle;
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

        $vehicles = Vehicle::with('user')->paginate(10);

        $rows = [];
        foreach($vehicles as $vehicle) {
            $rows[] = [
                'id' => $vehicle->id,
                'vehicle' => ApplicationTableHelper::getVehicleName($vehicle->vehicle_make, $vehicle->vehicle_model),
                'owner' => ApplicationTableHelper::getFullNameAttribute($vehicle->user->first_name, $vehicle->user->middle_name, $vehicle->user->last_name),
                'registration_date' => $vehicle->created_at
            ];
        }

        return ['rows' => $rows, 'vehicles' => $vehicles];
    }
}
