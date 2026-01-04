<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return $this->redirectBasedOnRole();
    }

    /**
     * Redirect user based on their role.
     */
    protected function redirectBasedOnRole(): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        // Check if user has admin-level roles
        if ($user->hasAnyRole(['super_admin', 'admin_editor', 'admin_viewer', 'encoder', 'security', 'maintenance'])) {
            return redirect()->route('admin.dashboard');
        }

        // Applicants go to their dashboard
        if ($user->hasRole('applicant')) {
            return redirect()->route('dashboard');
        }

        // Default fallback
        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
