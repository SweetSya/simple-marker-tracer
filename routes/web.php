<?php

use App\Http\Controllers\LocationController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// Public location sharing route
Route::get('/locate', [LocationController::class, 'showSharedLocation'])->name('locate');

// Protected routes
Route::middleware('checkAuth')->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Auth/Dashboard');
    })->name('dashboard');

    Route::get('/create-marker', function () {
        return Inertia::render('Auth/CreateMarker');
    })->name('create-marker');

    Route::get('/history-marker', [LocationController::class, 'index'])->name('history-marker');
    Route::post('/marker/{id}/toggle-expiry', [LocationController::class, 'toggleExpiry'])->name('marker.toggle-expiry');

    // New routes for location management
    Route::post('/locations/{id}/extend-expiry', [LocationController::class, 'extendExpiry'])->name('locations.extend-expiry');
    Route::put('/locations/{id}', [LocationController::class, 'update'])->name('locations.update');
    Route::delete('/locations/{id}', [LocationController::class, 'destroy'])->name('locations.destroy');

    Route::get('/user-settings', [UserController::class, 'show'])->name('user-settings');
    Route::post('/user-settings/profile', [UserController::class, 'updateProfile'])->name('user-settings.profile');
    Route::post('/user-settings/password', [UserController::class, 'updatePassword'])->name('user-settings.password');

    Route::post('/create-marker/save', [LocationController::class, 'store']);
});
