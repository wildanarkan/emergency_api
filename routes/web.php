<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

// Authentication Routes
Route::get('login', function () {
    return view('login');
})->name('login');

Route::post('login', [AuthController::class, 'login']);

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Routes for Operator System only
    // Route::middleware('admin')->group(function () {
        Route::resource('hospitals', HospitalController::class);
        Route::resource('users', UserController::class);
    // });

    // Routes for Hospital Operator and Operator System
    Route::resource('patients', PatientController::class);

    // Route::get('profile', [UserController::class, 'profile'])->name('profile');
});
