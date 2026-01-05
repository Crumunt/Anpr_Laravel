<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityMiddleware
{
    /**
     * Handle an incoming request - only allow security role for ANPR access.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Only security role can access ANPR dashboard
        if (!$request->user()->hasRole('security')) {
            return $this->redirectToAppropriateDashboard($request->user());
        }

        return $next($request);
    }

    /**
     * Redirect user to their appropriate dashboard based on role.
     */
    protected function redirectToAppropriateDashboard($user): Response
    {
        // Admin roles go to admin dashboard
        if ($user->hasAnyRole(['super_admin', 'admin_editor', 'admin_viewer', 'encoder', 'maintenance'])) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'You do not have permission to access that area.');
        }

        // Applicants go to applicant dashboard
        if ($user->hasRole('applicant')) {
            return redirect()->route('applicant.dashboard')
                ->with('error', 'You do not have permission to access that area.');
        }

        // Default fallback to login
        return redirect()->route('login');
    }
}
