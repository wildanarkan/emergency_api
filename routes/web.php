<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotifController;
use Illuminate\Support\Facades\Artisan;

// Authentication Routes
Route::get('login', function () {
    return view('login');
})->name('login')->middleware('guest');

Route::post('login', [AuthController::class, 'login']);

Route::get('/foo', function () {
    Artisan::call('storage:link');
});

// Protected Routes
Route::middleware(['auth', 'check.role'])->group(function () {

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/patient/{id}/pdf', [PatientController::class, 'downloadPDF'])->name('patient.pdf');
    Route::put('/patient/{id}/status', [PatientController::class, 'updateStatus'])->name('patient.updateStatus');

    Route::get('/notifications', [NotifController::class, 'getNotifications'])->name('notifications');
    Route::put('/notifications/{id}/update-status', [NotifController::class, 'updateStatus'])->name('notifications.update-status');
    Route::resource('notif', NotifController::class);
    Route::resource('hospital', HospitalController::class);
    Route::resource('user', UserController::class);
    Route::resource('patient', PatientController::class)->only(['index']);
});
