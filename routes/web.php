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

Route::get('/admin/rfid', function () {
    return view('rfid');
})->name('admin.rfid');

Route::get('/admin/vehicles', function () {
    return view('vehicles');
})->name('admin.vehicles');

Route::get('/admin/admins', function () {
    return view('admins');
})->name('admin.admins');

//sa applicant form route
Route::get('/admin/rfid-applicant-form', function () {
    return view('rfid-applicant-form');
})->name('admin.rfid-applicant-form');

Route::get('/rfid/personal-info', function () {
    return view('rfid.personal-info');
})->name('rfid.personal-info');

Route::get('/rfid/documents', function () {
    return view('rfid.documents');
})->name('rfid.documents');

Route::get('/rfid/vehicle', function () {
    return view('rfid.vehicle-info');
})->name('rfid.vehicle');

Route::get('/rfid/review', function () {
    return view('rfid.review');
})->name('rfid.review');

Route::get('/rfid/success', function () {
    return view('rfid.success');
})->name('rfid.success');

// RFID Application Form - Single unified page
Route::get('/rfid/application', function () {
    return view('rfid.application-form');
})->name('rfid.application');

// Form submission handler
Route::post('/rfid/submit', 'App\Http\Controllers\RfidApplicationController@submit')->name('rfid.submit');
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

