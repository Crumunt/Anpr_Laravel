<?php
use Illuminate\Support\Facades\Route;

//navigation tab routes
Route::get('/admin/welcome', function () {
    return view('welcome');
})->name('admin-home');

Route::get('/admin/welcome', function () {
    return view('welcome');
})->name('admin.welcome');

Route::get('/admin/applicant', function () {
    return view('applicant');
})->name('admin.applicant');

Route::get('/admin/gate_pass', function () {
    return view('gate-pass');
})->name('admin.gate-pass');

Route::get('/admin/vehicles', function () {
    return view('vehicles');
})->name('admin.vehicles');

Route::get('/admin/admins', function () {
    return view('admins');
})->name('admin.admins');

//sa applicant form route
Route::get('/admin/gate_pass-applicant-form', function () {
    return view('gate-pass-applicant-form');
})->name('admin.gate-pass-applicant-form');

Route::get('/gate_pass/personal-info', function () {
    return view('gate_pass.personal-info');
})->name('gate_pass.personal-info');

Route::get('/gate_pass/documents', function () {
    return view('gate_pass.documents');
})->name('gate_pass.documents');

Route::get('/gate_pass/vehicle', function () {
    return view('gate_pass.vehicle-info');
})->name('gate_pass.vehicle');

Route::get('/gate_pass/review', function () {
    return view('gate_pass.review');
})->name('gate_pass.review');

Route::get('/gate_pass/success', function () {
    return view('gate_pass.success');
})->name('gate_pass.success');

// gate_pass Application Form - Single unified page
Route::get('/gate_pass/application', function () {
    return view('gate_pass.application-form');
})->name('gate_pass.application');

// Form submission handler
Route::post('/gate_pass/submit', 'App\Http\Controllers\RfidApplicationController@submit')->name('rfid.submit');
// $typeRouteMap = [
//     'applicant' => ['route' => 'admin.applicants', 'label' => 'Applicants'],
//     'rfid' => ['route' => 'admin.rfid', 'label' => 'rfid'],
//     'vehicle' => ['route' => 'admin.vehicles', 'label' => 'Vehicles']

// ];
// In your routes/web.php file
Route::put('/applicants/{id}/update/{section}', 'ApplicantController@update')->name('applicant.update');
Route::get('/details/{type}/{id}', function($type, $id) {
    return view('components.details.'.$type, [
        'id' => $id,
        'type' => $type
    ]);
})->name('details.show');

