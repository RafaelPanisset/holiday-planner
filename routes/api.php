<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HolidayPlanController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [LoginController::class, 'login']);
Route::middleware('auth:api')->post('logout', [LoginController::class, 'logout']);



Route::middleware('auth:api')->group(function () {
    Route::post('/holiday-plans', [HolidayPlanController::class, 'store']);
    Route::get('/holiday-plans', [HolidayPlanController::class, 'index']);
    Route::get('/holiday-plans/{id}', [HolidayPlanController::class, 'show']);
    Route::put('/holiday-plans/{id}', [HolidayPlanController::class, 'update']);
    Route::delete('/holiday-plans/{id}', [HolidayPlanController::class, 'destroy']);
    Route::get('/holiday-plans/{id}/pdf', [HolidayPlanController::class, 'generatePdf']);
});

