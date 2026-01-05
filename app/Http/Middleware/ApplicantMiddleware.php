<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplicantMiddleware
{
    /**
     * Handle an incoming request - only allow applicant role.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Only applicants can access applicant routes
        if (!$request->user()->hasRole('applicant')) {
            return $this->redirectToAppropriateDashboard($request->user());
        }

        return $next($request);
    }

    /**
     * Redirect user to their appropriate dashboard based on role.
     */
    protected function redirectToAppropriateDashboard($user): Response
    {
        // Security users go to ANPR dashboard
        if ($user->hasRole('security')) {
            return redirect()->route('anpr.dashboard')
                ->with('error', 'You do not have permission to access that area.');
        }

        // Admin roles go to admin dashboard
        if ($user->hasAnyRole(['super_admin', 'admin_editor', 'admin_viewer', 'encoder', 'maintenance'])) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'You do not have permission to access that area.');
        }

        // Default fallback to login
        return redirect()->route('login');
    }
}
