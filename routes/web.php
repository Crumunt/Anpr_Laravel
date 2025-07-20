<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ApplicantController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GatePassController;
use App\Http\Controllers\Admin\VehicleController;
use Illuminate\Support\Facades\Route;

Route::get('/test', fn() => view('testing'));

Route::prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/', [DashboardController::class, 'index'])->name('welcome');

    Route::get('/applicant', [ApplicantController::class, 'index'])->name('applicant');

    Route::get('/applicant/{id}', fn($id) => view('admin.users.applicants.show', ['id' => $id]))->name('applicant.show-details');
    
    Route::get('/gate-pass', [GatePassController::class, 'index'])->name('gate_passes.index');
    
    Route::get('/gate-pass/{id}', fn($id) => view('admin.gate_passes.show', ['id' => $id]))->name('gate_passes.show');
    
    Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles');
    
    Route::get('/vehicles/{id}', fn($id) => view('admin.vehicles.show', ['id' => $id]))->name('vehicles.show');
    
    Route::get('/admins', [AdminController::class, 'index'])->name('admins');
    
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
Route::post('/gate-pass/submit', 'App\Http\Controllers\RfidApplicationController@submit')->name('rfid.submit');
// $typeRouteMap = [
//     'applicant' => ['route' => 'admin.applicants', 'label' => 'Applicants'],
//     'rfid' => ['route' => 'admin.rfid', 'label' => 'rfid'],
//     'vehicle' => ['route' => 'admin.vehicles', 'label' => 'Vehicles']

// ];
// In your routes/web.php file
Route::put('/applicants/{id}/update/{section}', 'ApplicantController@update')->name('applicant.update');


