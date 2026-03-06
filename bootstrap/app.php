<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Override the default RedirectIfAuthenticated middleware
        $middleware->redirectGuestsTo('/login');
        $middleware->redirectUsersTo(function () {
            /** @var \App\Models\User|null $user */
            $user = Auth::user();

            // Security and security_admin users go to ANPR dashboard
            if ($user && $user->hasAnyRole(['security', 'security_admin'])) {
                return route('anpr.dashboard');
            }

            // Admin roles go to admin dashboard
            if ($user && $user->hasAnyRole(['super_admin', 'admin_editor', 'admin_viewer', 'encoder', 'maintenance'])) {
                return route('admin.dashboard');
            }

            // Applicants go to applicant dashboard
            return route('applicant.dashboard');
        });

        // Register Spatie Permission middleware aliases
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'applicant' => \App\Http\Middleware\ApplicantMiddleware::class,
            'security' => \App\Http\Middleware\SecurityMiddleware::class,
            'ensure_role' => \App\Http\Middleware\EnsureUserHasRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Handle CSRF token mismatch - redirect to login instead of showing error page
        $exceptions->renderable(function (TokenMismatchException $e, $request) {
            return redirect()->route('login')->with('status', 'Your session has expired. Please log in again.');
        });
    })->create();
