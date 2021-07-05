<?php

use App\Http\Controllers\AvailabilitiesController;
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

Route::get('/doctors/{doctor}/availabilities', [AvailabilitiesController::class, 'index'])
    ->name('api.availabilities.index');
