<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ApplicationTableHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ApplicantController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $query = User::with('vehicles', 'statuses')
            ->whereHas('roles', function ($q) {
                $q->where('id', '>', 4);
            });

        if ($request->filled('status')) {
            $query->whereHas('statuses', fn($q) => $q->where('status_name', $request->status));
        }

        if ($request->filled('applicant_types')) {
            $types = $request->input('applicant_types');

            $query->whereHas('roles', fn($q) => $q->whereIn('name', $types));
        }

        $sortOptions = [
            'newest' => ['created_at', 'asc'],
            'oldest' => ['created_at', 'desc'],
            'a-z' => ['first_name', 'asc'],
            'z-a' => ['first_name', 'desc'],
        ];

        if ($request->filled('sort_by') && isset($sortOptions[$request->sort_by])) {
            [$column, $direction] = $sortOptions[$request->sort_by];
            $query->orderBy($column, $direction);
        }

        if ($request->filled('search')) {
            $terms = explode(' ', $request->search);

            $query->where('user_id', 'like', "{$request->search}%");
            $query->orWhere('email', 'like', "%{$request->search}%");

            $query->orWhere(function ($q) use ($terms) {
                foreach ($terms as $term) {
                    $q->where('first_name', 'like', "{$term}%")
                        ->orWhere('last_name', $term);
                }
            });

        }

        $users = $query->paginate(10);

        $userDetails = $users->map(
            fn($user) =>
            [
                'id' => $user->id,
                'user_id' => $user->user_id ?? '-',
                'name' => ApplicationTableHelper::getFullNameAttribute(
                    $user->first_name,
                    $user->middle_name,
                    $user->last_name
                ),
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'status' => [
                    'label' => ucfirst($user->statuses->status_name)
                ],
                'submitted_date' => $user->created_at->format('F j, Y h:i A'),
                'vehicles' => $user->vehicles->count()
            ]
        );

        // AJAX REQUEST WAS MADE?
        if ($request->ajax()) {
            return response()->json([
                'rows' => view('components.table.partials.data-rows', [
                    'rows' => $userDetails,
                    'showCheckboxes' => true,
                    'showActions' => true,
                    'headers' => ApplicationTableHelper::headerHelper('user_applicant', '')
                ])->render(),
                'pagination' => view('components.pagination', ['pagination' => $users])->render()
            ]);
        }

        // DEFAULT NO AJAX REQUEST
        return view('admin.users.applicants.index', compact('userDetails', 'users'));
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

        $rows = User::with('vehicles', 'statuses')->where('role_id', '>', 4)->paginate(10);

        return $rows;
    }
}
