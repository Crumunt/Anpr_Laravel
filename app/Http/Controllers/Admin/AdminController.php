<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Faker\Factory as Faker;

class AdminController extends Controller
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
        $users = $this->getUsers();

        return view('admin.users.admins.index', compact('users'));
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

    private function getUsers()
    {
        $statusOptions = [
            [
                'label' => 'Active'
            ],
            [
                'label' => 'Inactive'
            ],
            [
                'label' => 'Disbaled'
            ]
        ];

        $permission = [
            'All Permissions',
            'Vehicles',
            'Applicants',
            'Admin',
            'None'
        ];

        $role = [
            'Super Admin',
            'Moderator',
            'Admin'
        ];

        $all = [];
        $active = [];
        $inactive = [];

        foreach (range(1, 5) as $i) {
            $all[] = [
                'id' => $this->faker->unique()->randomNumber(6),
                'name' => $this->faker->name,
                'email' => $this->faker->unique()->safeEmail,
                'phone' => $this->faker->phoneNumber,
                'role' => $this->faker->randomElement($role),
                'status' => $this->faker->randomElement($statusOptions),
                'lastLogin' => $this->faker->date(),
            ];
            $active[] = [
                'id' => $this->faker->unique()->randomNumber(6),
                'name' => $this->faker->name,
                'email' => $this->faker->unique()->safeEmail,
                'phone' => $this->faker->phoneNumber,
                'permissions' => $this->faker->randomElement($permission),
                'lastLogin' => $this->faker->date(),
                
            ];
            $inactive[] = [
                'id' => $this->faker->unique()->randomNumber(6),
                'name' => $this->faker->name,
                'email' => $this->faker->unique()->safeEmail,
                'phone' => $this->faker->phoneNumber,
                'inactiveSince' => $this->faker->date(),
                'lastLogin' => $this->faker->date(),
                
            ];
        }

        return ['all' => $all, 'active' => $active, 'inactive' => $inactive];
    }
}
