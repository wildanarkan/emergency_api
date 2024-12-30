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

    Route::put('/patient/{id}/status', [PatientController::class, 'updateStatus'])->name('patient.updateStatus');


    Route::resource('hospital', HospitalController::class);
    Route::resource('user', UserController::class);
    Route::resource('patient', PatientController::class);
});
