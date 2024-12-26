<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\PatientController;

// Public routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    
    // Hospitals routes
    Route::get('hospitals/users', [HospitalController::class, 'showUsers']);
    Route::apiResource('hospitals', HospitalController::class);
    
    // Patients routes
    Route::apiResource('patients', PatientController::class);
});