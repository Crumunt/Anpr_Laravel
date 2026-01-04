<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ApplicantController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GatePassController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('auth.login'));

Route::get('/test', fn() => view('testing'));

Route::get('/documents/{document}/view', [DocumentController::class, 'view'])->name('documents.view');

// Admin routes - Protected by auth and role middleware
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:super_admin|admin_editor|admin_viewer|encoder|security|maintenance'])->group(function () {

    // Dashboard - All admin roles
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Applicant routes - Requires 'view applicants' permission
    Route::middleware(['permission:view applicants'])->group(function () {
        Route::get('/applicant', [ApplicantController::class, 'index'])->name('applicant');
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

    // Account Settings (Manage My Account) - Available to all admin roles
    Route::get('/account', \App\Livewire\Admin\Settings\AccountSettings::class)->name('account');

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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
