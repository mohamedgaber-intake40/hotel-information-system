<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RefreshTokenController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\FacilityController;
use App\Http\Controllers\Api\HotelController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\ReservationController;
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
    Route::apiResource('cities', CityController::class);
    Route::apiResource('countries', CountryController::class);
    Route::apiResource('facilities', FacilityController::class);
    Route::apiResource('hotels', HotelController::class);
    Route::apiResource('hotels.rooms', RoomController::class)->where(['hotel'=>'[0-9]*']);
    Route::get('reservations',ReservationController::class)->name('reservations.index');
});
