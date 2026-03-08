<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ApplicantController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GatePassController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\ANPR\AnprAlertsController;
use App\Http\Controllers\ANPR\AnprAnalyticsController;
use App\Http\Controllers\ANPR\AnprDashboardController;
use App\Http\Controllers\ANPR\AnprFlaggedController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('auth.login'));

Route::get('/test', fn() => view('testing'));

// Public Gate Pass Application - No authentication required
Route::get('/apply', \App\Livewire\GatePass\GatePassApplication::class)->name('gate-pass.apply');

Route::get('/documents/{document}/view', [DocumentController::class, 'view'])->name('documents.view');

// Admin routes - Protected by auth and role middleware (excludes security and applicant)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:super_admin|admin_editor|admin_viewer|encoder|maintenance'])->group(function () {

    // Dashboard - All admin roles
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Applicant routes - Requires 'view applicants' permission
    Route::middleware(['permission:view applicants'])->group(function () {
        Route::get('/applicant', [ApplicantController::class, 'index'])->name('applicant');
        Route::get('/applicant/archived', [ApplicantController::class, 'archived'])->name('applicant.archived');
        Route::get('/applicant/{id}', [ApplicantController::class, 'show'])->name('applicant.show');
    });

    // Gate Pass routes - Requires 'view gate passes' permission
    Route::middleware(['permission:view gate passes'])->group(function () {
        Route::get('/gate-pass', [GatePassController::class, 'index'])->name('gate_passes.index');
        Route::get('/gate-pass/{id}', fn($id) => view('admin.gate_passes.show', ['id' => $id]))->name('gate_passes.show');
    });

    // Vehicle routes - Requires 'view vehicles' permission
    Route::middleware(['permission:view vehicles'])->group(function () {
        Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles');
        Route::get('/vehicles/{id}', fn($id) => view('admin.vehicles.show', ['id' => $id]))->name('vehicles.show');
    });

    // Vehicle creation - Requires 'create vehicles' permission
    Route::post('/vehicles', [VehicleController::class, 'store'])
        ->middleware(['permission:create vehicles'])
        ->name('vehicles.store');

    // Admin management - Only super_admin and admin_editor can access
    Route::middleware(['role:super_admin|admin_editor'])->group(function () {
        Route::get('/admins', [AdminController::class, 'index'])->name('admins');
        Route::get('/admins/{id}', [AdminController::class, 'show'])->name('admins.show');
    });

    // Lightweight applicant search for vehicle owner selection
    Route::get('/users/search', [ApplicantController::class, 'search'])->name('users.search');

    // Global search (header)
    Route::get('/search', [\App\Http\Controllers\Admin\SearchController::class, 'search'])->name('search.ajax');
    Route::get('/search/results', [\App\Http\Controllers\Admin\SearchController::class, 'results'])->name('search.results');

    // System Settings - Only super_admin can access
    Route::get('/settings', \App\Livewire\Admin\Settings\SystemSettings::class)
        ->middleware(['role:super_admin'])
        ->name('settings');

    // Applicant Types Settings - Only super_admin can access
    Route::get('/settings/applicant-types', \App\Livewire\Admin\Settings\ApplicantTypeSettings::class)
        ->middleware(['role:super_admin'])
        ->name('settings.applicant-types');

    // Account Settings (Manage My Account) - Available to all admin roles
    Route::get('/account', \App\Livewire\Admin\Settings\AccountSettings::class)->name('account');
});

// Applicant routes - Protected by auth and applicant middleware
Route::prefix('applicant')->name('applicant.')->middleware(['auth', 'applicant'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Applicant\ApplicantDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', \App\Livewire\Applicant\ProfileSettings::class)->name('profile');

    Route::get('/vehicles', [\App\Http\Controllers\Applicant\ApplicantVehicleController::class, 'index'])->name('vehicles');
    Route::get('/vehicles/{id}', [\App\Http\Controllers\Applicant\ApplicantVehicleController::class, 'show'])->name('vehicles.show');

    Route::get('/notifications', [\App\Http\Controllers\Applicant\NotificationsController::class, 'index'])->name('notifications');

    Route::get('/activity-log', [\App\Http\Controllers\Applicant\ActivityLogController::class, 'index'])->name('activity-log');
});

// ANPR Dashboard Routes - Protected by auth and security middleware
Route::prefix('anpr')->name('anpr.')->middleware(['auth', 'security'])->group(function () {
    Route::get('/dashboard', [AnprDashboardController::class, 'index'])->name('dashboard');

    Route::get('/alerts', [AnprAlertsController::class, 'index'])->name('alerts');

    Route::get('/live-feeds', [AnprDashboardController::class, 'index'])->name('live-feeds');

    Route::get('/flagged-vehicles', [AnprFlaggedController::class, 'index'])->name('flagged-vehicles');

    Route::get('/analytics', [AnprAnalyticsController::class, 'index'])->name('analytics');
    Route::get('/analytics/download-report', [AnprAnalyticsController::class, 'downloadReport'])->name('analytics.download-report');

    Route::get('/profile', \App\Livewire\ANPR\ProfileSettings::class)->name('profile');

    Route::get('/settings', function () {
        return view('anpr.settings.index');
    })->name('settings');

    // Manage Security Accounts - Only accessible by security_admin
    Route::get('/accounts', \App\Livewire\ANPR\ManageAccounts::class)
        ->middleware(['role:security_admin'])
        ->name('accounts');
});

//sa applicant form route

Route::prefix('gate-pass')->name('gate-pass.')->group(function () {
    Route::get('/gate-pass-applicant-form', fn() => view('gate-pass-applicant-form'))->name('gate-pass-applicant-form');
    Route::get('/personal-info', fn() => view('gate-pass.personal-info'))->name('personal-info');
    Route::get('/documents', fn() => view('gate-pass.documents'))->name('documents');
    Route::get('/vehicle', fn() => view('gate-pass.vehicle-info'))->name('vehicle');
    Route::get('/review', fn() => view('gate-pass.review'))->name('review');
    Route::get('/success', fn() => view('gate-pass.success'))->name('success');
});

// gate-pass Application Form - Single unified page
// Route::get('/gate-pass/application', function () {
//     return view('gate-pass.application-form');
// })->name('gate-pass.application');

// Form submission handler
// $typeRouteMap = [
//     'applicant' => ['route' => 'admin.applicants', 'label' => 'Applicants'],
//     'rfid' => ['route' => 'admin.rfid', 'label' => 'rfid'],
//     'vehicle' => ['route' => 'admin.vehicles', 'label' => 'Vehicles']

// ];

// Role-based dashboard redirect (fallback for direct /dashboard access)
Route::get('/dashboard', function () {
    /** @var \App\Models\User $user */
    $user = auth()->user();

    // Security and security_admin users go to ANPR dashboard
    if ($user->hasAnyRole(['security', 'security_admin'])) {
        return redirect()->route('anpr.dashboard');
    }

    // Admin roles go to admin dashboard
    if ($user->hasAnyRole(['super_admin', 'admin_editor', 'admin_viewer', 'encoder', 'maintenance'])) {
        return redirect()->route('admin.dashboard');
    }

    // Applicants go to applicant dashboard
    return redirect()->route('applicant.dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
