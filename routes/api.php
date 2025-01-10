<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\PatientController;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

// Public routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('login-app', [AuthController::class, 'loginApp']);

// Protected routes
Route::middleware('auth:sanctum', EnsureFrontendRequestsAreStateful::class)->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    
    // Hospitals routes
    Route::get('hospital/user', [HospitalController::class, 'showUsers']);
    Route::apiResource('hospital', HospitalController::class);
    
    // Patients routes
    Route::apiResource('patient', PatientController::class);
});