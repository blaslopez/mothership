<?php

use Illuminate\Support\Facades\Route;
use Modules\Cockpit\app\Http\Controllers\AuthController;
use Modules\Cockpit\app\Http\Controllers\DashboardController;
use Modules\Cockpit\app\Http\Controllers\PreferenceController;
use Modules\Cockpit\app\Http\Controllers\UserController;
use Modules\Cockpit\app\Http\Controllers\RoleController;

Route::prefix('cockpit')->name('cockpit.')->middleware('web')->group(function () {

    // Guest-only routes
    Route::middleware('guest')->group(function () {
        Route::get('login',     [AuthController::class, 'showLogin'])->name('login');
        Route::post('login',    [AuthController::class, 'login']);
        Route::get('register',  [AuthController::class, 'showRegister'])->name('register');
        Route::post('register', [AuthController::class, 'register']);
    });

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard: accessible to all (guests and authenticated)
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Authenticated routes
    Route::middleware('auth')->group(function () {

        Route::get('preferences',       [PreferenceController::class, 'show'])->name('preferences.show');
        Route::put('preferences',       [PreferenceController::class, 'update'])->name('preferences.update');
        Route::post('preferences/sync', [PreferenceController::class, 'sync'])->name('preferences.sync');

        // User management
        Route::middleware('can:cockpit.users.view')
            ->resource('users', UserController::class)
            ->names('users');

        // Role management
        Route::middleware('can:cockpit.roles.view')
            ->resource('roles', RoleController::class)
            ->names('roles');
    });
});
