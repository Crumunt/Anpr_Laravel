<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
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
        $users = User::with(relations: ['details', 'roles'])
            ->whereHas('roles', function ($q) {
                $q->whereIn('name', ['admin_viewer','admin_editor', 'encoder','super_admin','security','maintenance']);
            })->paginate(10);

        $all = [];
        $active = [];
        $inactive = [];

        foreach ($users as $user) {
            $all[] = [
                'id' => $user->id,
                'user_id' => $user->details->clsu_id,
                'name' => $user->first_name,
                'email' => $user->email,
                'phone_number' => $user->details->phone_number,
                'role' => ucwords(str_replace('_',' ',$user->roles->first()?->name)),
                'status' => ['label' => $user->details?->status?->status_name],
                'lastLogin' => $this->faker->date(),
            ];
            $active[] = [
                'id' => $user->id,
                'user_id' => $user->details?->clsu_id,
                'name' => $user->first_name,
                'email' => $user->email,
                'phone_number' => $user->details?->phone_number,
                'lastLogin' => $this->faker->date(),

            ];
            $inactive[] = [
                'id' => $user->id,
                'user_id' => $user->details->id,
                'name' => $user->first_name,
                'email' => $user->email,
                'phone_number' => $user->details?->phone_number,
                'inactiveSince' => $this->faker->date(),
                'lastLogin' => $this->faker->date(),

            ];
        }

        return ['all' => $all, 'active' => $active, 'inactive' => $inactive, 'paginate' => $users];
    }
}
