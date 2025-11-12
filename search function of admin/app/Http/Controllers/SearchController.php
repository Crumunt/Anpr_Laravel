<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class SearchController extends Controller
{
    // AJAX search (limit 5)
    public function search(Request $request)
    {
        $query = $request->input('q');
        if (!$query) {
            return response()->json([]);
        }

        $users = User::where('name', 'like', "%$query%")
            ->orWhere('email', 'like', "%$query%")
            ->limit(5)
            ->get(['id', 'name', 'email']);

        return response()->json([
            'users' => $users,
        ]);
    }

    // Full results page
    public function results(Request $request)
    {
        $query = $request->input('q');
        $users = collect();
        if ($query) {
            $users = User::where('name', 'like', "%$query%")
                ->orWhere('email', 'like', "%$query%")
                ->get();
        }
        return view('search.results', compact('users', 'query'));
    }
} 