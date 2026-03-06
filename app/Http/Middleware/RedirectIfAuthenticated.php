<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return $this->redirectBasedOnRole();
            }
        }

        return $next($request);
    }

    /**
     * Redirect user based on their role.
     */
    protected function redirectBasedOnRole(): Response
    {
        /** @var User $user */
        $user = Auth::user();

        // Security and security_admin users go to ANPR dashboard
        if ($user->hasAnyRole(['security', 'security_admin'])) {
            return redirect()->route('anpr.dashboard');
        }

        // Check if user has admin-level roles (excludes security roles)
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
}
