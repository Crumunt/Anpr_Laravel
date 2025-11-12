<?php
use Illuminate\Support\Facades\Route;


Route::put('/applicants/{id}/update/{section}', 'ApplicantController@update')->name('applicant.update');
Route::get('/details/{type}/{id}', function($type, $id) {
    return view('components.details.'.$type, [
        'id' => $id,
        'type' => $type
    ]);
})->name('details.show');

// AJAX search (returns JSON, limit 5)
Route::get('/search', [App\Http\Controllers\SearchController::class, 'search'])->name('search.ajax');
// Full results page
Route::get('/search/results', [App\Http\Controllers\SearchController::class, 'results'])->name('search.results');

