<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
