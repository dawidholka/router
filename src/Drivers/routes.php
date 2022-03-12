<?php

use Illuminate\Support\Facades\Route;
use Router\Drivers\Application\Http\Controllers\DriverController;
use Router\Drivers\Application\Http\Controllers\DriverSearchController;

Route::middleware(['web', 'auth:sanctum'])->prefix('drivers')->group(function () {
    Route::get('/drivers/datatable', [DriverController::class, 'datatable'])->name('drivers.datatable');
    Route::resource('drivers', DriverController::class);
    Route::get('/drivers/search', DriverSearchController::class)->name('drivers.search');
});
