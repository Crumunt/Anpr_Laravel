<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

trait RedirectsBasedOnRole
{
    /**
     * Redirect user to their appropriate dashboard based on role.
     */
    protected function redirectBasedOnRole(): RedirectResponse
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

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
        return redirect()->route('applicant.dashboard');
    }

    /**
     * Get the intended redirect URL for the user based on their role.
     */
    protected function getIntendedUrlForRole(): string
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            return route('login');
        }

        // Security users go to ANPR dashboard
        if ($user->hasRole('security')) {
            return route('anpr.dashboard');
        }

        // Check if user has admin-level roles (excludes security)
        if ($user->hasAnyRole(['super_admin', 'admin_editor', 'admin_viewer', 'encoder', 'maintenance'])) {
            return route('admin.dashboard');
        }

        // Applicants go to their dashboard
        if ($user->hasRole('applicant')) {
            return route('applicant.dashboard');
        }

        // Default fallback
        return route('applicant.dashboard');
    }
}
