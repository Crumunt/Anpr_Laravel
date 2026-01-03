<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     * Data is now loaded via Livewire components for better performance and reactivity.
     */
    public function index()
    {
        return view('admin.dashboard');
    }
}
