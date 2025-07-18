<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ApplicationTableHelper;
use App\Http\Controllers\Controller;
use App\Models\Vehicle\Vehicle;
use Illuminate\Http\Request;
use Faker\Factory as Faker;

class GatePassController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    private $faker;

    function __construct()
    {
        $this->faker = Faker::create();
    }

    public function index()
    {
        //
        $gatePasses = $this->getGatePasses();

        return view('admin.gate_passes.index', compact('gatePasses'));
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
