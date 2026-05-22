<?php

use Illuminate\Support\Facades\Route;
use Modules\Cockpit\Http\Controllers\CockpitController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('cockpits', CockpitController::class)->names('cockpit');
});
