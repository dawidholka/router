<?php

use App\Http\Controllers\BulkDeleteController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\CreatorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GeneralSettingsController;
use App\Http\Controllers\ImportSettingsController;
use App\Http\Controllers\MapPinSvgController;
use App\Http\Controllers\OSRMSettingsController;
use App\Http\Controllers\UpdateSettingsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WaypointPhotoController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('map-ping/{color}/pin.svg', MapPinSvgController::class)->name('map-pin');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::get('calendar/events', [CalendarController::class, 'events'])->name('calendar.events');
    Route::get('creator', [CreatorController::class, 'show'])->name('creator');
    Route::post('creator', [CreatorController::class, 'store']);
    Route::get('/users/datatable', [UserController::class, 'datatable'])->name('users.datatable');
    Route::resource('users', UserController::class);
    Route::post('bulk-destroy', BulkDeleteController::class)->name('bulk-destroy');
    Route::get('waypoints/{waypoint}/photo', WaypointPhotoController::class)->name('waypoints.photo');

    Route::prefix('settings')->group(function () {
        Route::get('general', [GeneralSettingsController::class, 'show'])->name('settings.general');
        Route::put('general', [GeneralSettingsController::class, 'update'])->name('settings.general.update');
        Route::get('import', [ImportSettingsController::class, 'show'])->name('settings.import');
        Route::put('import', [ImportSettingsController::class, 'update']);
        Route::get('update', [UpdateSettingsController::class, 'show'])->name('settings.update');
        Route::get('osrm', [OSRMSettingsController::class, 'show'])->name('settings.osrm');
        Route::put('osrm', [OSRMSettingsController::class, 'update'])->name('settings.osrm.update');
        Route::post('update-check', [UpdateSettingsController::class, 'updateCheck'])->name('settings.update.check');
        Route::post('update-install', [UpdateSettingsController::class, 'installUpdate'])->name('settings.update.install');
    });
});
