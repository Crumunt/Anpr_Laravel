<?php
use Illuminate\Support\Facades\Route;


Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/welcome', fn() => view('welcome'))->name('welcome');
    Route::get('/applicant', fn() => view('applicant'))->name('applicant');
    Route::get('/gate-pass', fn() => view('gate-pass'))->name('gate-pass');
    Route::get('/vehicles', fn() => view('vehicles'))->name('vehicles');
    Route::get('/admins', fn() => view('admins'))->name('admins');
    
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

Route::prefix('view-details')->name('details.')->group(function() {
    Route::get('/details/{type}/{id}', fn($type, $id) => view('details.' . $type, [
        'id' => $id,
        'type' => $type
    ]))->name('show');
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


