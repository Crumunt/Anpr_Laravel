<?php
use Illuminate\Support\Facades\Route;


Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/welcome', fn() => view('welcome'))->name('welcome');
    Route::get('/applicant', fn() => view('applicant'))->name('applicant');
    Route::get('/gate-pass', fn() => view('gate-pass'))->name('gate-pass');
    Route::get('/vehicles', fn() => view('vehicles'))->name('vehicles');
    Route::get('/admins', fn() => view('admins'))->name('admins');
    Route::get('/gate-pass-applicant-form', fn() => view('gate-pass-applicant-form'))->name('gate-pass-applicant-form');

});

//sa applicant form route

Route::prefix('gate-pass')->name('gate-pass.')->group(function () {
    Route::get('/personal-info', fn() => view('personal-info'))->name('personal-info');
    Route::get('/documents', fn() => view('documents'))->name('documents');
    Route::get('/vehicle', fn() => view('vehicle-info'))->name('vehicle');
    Route::get('/review', fn() => view('review'))->name('review');
    Route::get('/success', fn() => view('success'))->name('success');
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
Route::get('/details/{type}/{id}', function ($type, $id) {
    return view('components.details.' . $type, [
        'id' => $id,
        'type' => $type
    ]);
})->name('details.show');

