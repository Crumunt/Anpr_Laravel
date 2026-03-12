<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Services\ActivityLogService;
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
        try {
            $request->authenticate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            // If the account is deactivated, redirect to the deactivated page
            if (($e->errors()['email'][0] ?? null) === 'deactivated') {
                return redirect()->route('account.deactivated');
            }
            throw $e;
        }

        $request->session()->regenerate();

        // Log the login activity
        if (Auth::user()) {
            ActivityLogService::logLogin(Auth::user());
        }

        return $this->redirectBasedOnRole();
    }

    /**
     * Redirect user based on their role.
     */
    protected function redirectBasedOnRole(): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        // Security users go to ANPR dashboard
        if ($user->hasRole('security')) {
            return redirect()->route('anpr.dashboard');
        }

        // Check if user has admin-level roles (excludes security)
        if ($user->hasAnyRole(['super_admin', 'admin_editor', 'admin_viewer', 'encoder', 'maintenance'])) {
            return redirect()->route('admin.dashboard');
        }

        // Applicants go to their dashboard
        if ($user->hasRole('applicant')) {
            return redirect()->route('applicant.dashboard');
        }

        // Default fallback
        return redirect()->intended(route('applicant.dashboard', absolute: false));
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
