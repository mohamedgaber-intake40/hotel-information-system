<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RefreshTokenController;
use Illuminate\Support\Facades\Route;

/*
 |--------------------------------------------------------------------------|
 |                              Auth Routes                                 |
 |--------------------------------------------------------------------------|
 */
Route::prefix('auth')->group(function () {
    Route::post('login', LoginController::class);
    Route::get('refresh-token', RefreshTokenController::class)->middleware('auth:sanctum')->name('token.refresh');
});
