<?php

use App\Http\Controllers\LocationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/regions', [LocationController::class, 'regions']);

Route::get('/provinces', [LocationController::class, 'provinces']);
Route::get('/city-municipalities', [LocationController::class, 'cityMunicipalities']);
Route::get('/barangays', [LocationController::class, 'barangays']);
