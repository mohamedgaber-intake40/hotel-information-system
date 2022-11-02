<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RefreshTokenController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\FacilityController;
use Illuminate\Support\Facades\Route;

/*
 |--------------------------------------------------------------------------|
 |                              Auth Routes                                 |
 |--------------------------------------------------------------------------|
 */
Route::prefix('auth')->group(function () {
    Route::post('login', LoginController::class)->name('login');
    Route::get('refresh-token', RefreshTokenController::class)->middleware('auth:sanctum')->name('token.refresh');
});


/*
 |--------------------------------------------------------------------------|
 |                              Authenticated Routes                        |
 |--------------------------------------------------------------------------|
 */
Route::group([ 'middleware' => [ 'auth:sanctum' ] ], function () {
    Route::get('cities', CityController::class)->name('cities.index');
    Route::get('countries', CountryController::class)->name('countries.index');
    Route::get('facilities', FacilityController::class)->name('facilities.index');
});
