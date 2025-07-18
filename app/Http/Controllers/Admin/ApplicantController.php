<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ApplicationTableHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Faker\Factory as Faker;

class ApplicantController extends Controller
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

        return view('admin.users.applicants.index', compact('userDetails'));
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
        // $statusOptions = [
        //     [
        //         'label' => 'Pending'
        //     ],
        //     [
        //         'label' => 'Approved'
        //     ],
        //     [
        //         'label' => 'Rejected'
        //     ]
        // ];
        // $rows = [];

        // foreach (range(1, 5) as $i) {
        //     $rows[] = [
        //         'id' => $this->faker->unique()->randomNumber(6),
        //         'name' => $this->faker->name,
        //         'email' => $this->faker->unique()->safeEmail,
        //         'phone' => $this->faker->phoneNumber,
        //         'status' => $this->faker->randomElement($statusOptions),
        //         'submitted_date' => $this->faker->date('Y-m-d', '2023-06-30'),
        //         'gate_pass' => $this->faker->numberBetween(0, 3),
        //         'vehicles' => $this->faker->numberBetween(0, 3)
        //     ];
        // }

        $rows = User::with('vehicles', 'statuses')->where('role_id', '>', 4)->paginate(10);

        return $rows;
    }
}
