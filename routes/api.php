<?php

use App\Http\Controllers\BookingsController;
use App\Http\Controllers\DoctorsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/doctors', [DoctorsController::class, 'index'])
    ->name('api.doctors.index');

Route::get('/doctors/{doctor}/availabilities', [DoctorsController::class, 'availabilities'])
    ->name('api.doctors.availabilities');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/bookings', [BookingsController::class, 'index'])
        ->name('api.bookings.index');

    Route::post('/bookings', [BookingsController::class, 'create'])
        ->name('api.bookings.create');

    Route::get('/bookings/{booking}/cancel', [BookingsController::class, 'cancel'])
        ->name('api.bookings.cancel');
});
