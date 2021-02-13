<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BikeController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\BikeReservationController;

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

Route::post('/bikes', [BikeController::class, 'create']);
Route::get('/bikes', [BikeController::class, 'index']);

Route::post('/employees', [EmployeeController::class, 'create']);
Route::get('/employees', [EmployeeController::class, 'index']);

Route::post('/bike-reservation', [BikeReservationController::class, 'create']);
Route::get('/bike-reservation', [BikeReservationController::class, 'index']);
