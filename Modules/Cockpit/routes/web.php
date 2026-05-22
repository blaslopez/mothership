<?php

use Illuminate\Support\Facades\Route;
use Modules\Cockpit\Http\Controllers\AuthController;
use Modules\Cockpit\Http\Controllers\DashboardController;
use Modules\Cockpit\Http\Controllers\ProfileController;
use Modules\Cockpit\Http\Controllers\UserController;
use Modules\Cockpit\Http\Controllers\RoleController;
use Modules\Cockpit\Http\Controllers\PreferenceController;

Route::prefix('cockpit')->name('cockpit.')->group(function () {

    // Auth routes (guests only)
    Route::middleware('guest')->group(function () {
        Route::get('login',          [AuthController::class, 'showLogin'])->name('login');
        Route::post('login',         [AuthController::class, 'login']);
        Route::get('register',       [AuthController::class, 'showRegister'])->name('register');
        Route::post('register',      [AuthController::class, 'register']);
    });

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard (accessible to all, auth optional)
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Authenticated routes
    Route::middleware('auth')->group(function () {

        Route::get('profile',        [ProfileController::class, 'show'])->name('profile.show');
        Route::put('profile',        [ProfileController::class, 'update'])->name('profile.update');

        Route::get('preferences',    [PreferenceController::class, 'show'])->name('preferences.show');
        Route::put('preferences',    [PreferenceController::class, 'update'])->name('preferences.update');

        // User management
        Route::middleware('can:cockpit.users.view')->group(function () {
            Route::resource('users', UserController::class)->names('users');
        });

        // Role management
        Route::middleware('can:cockpit.roles.view')->group(function () {
            Route::resource('roles', RoleController::class)->names('roles');
        });
    });
});
