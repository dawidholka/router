<?php

use Illuminate\Support\Facades\Route;
use Router\Points\Application\Http\Controllers\PointBulkDeleteController;
use Router\Points\Application\Http\Controllers\PointBulkGeolocationController;
use Router\Points\Application\Http\Controllers\PointController;
use Router\Points\Application\Http\Controllers\PointGeolocationController;
use Router\Points\Application\Http\Controllers\PointSearchDatatableController;

Route::middleware(['web', 'auth:sanctum'])->prefix('points')->group(function () {
    Route::post('/points/{point}/geolocation', PointGeolocationController::class)->name('points.geolocation');
    Route::get('/points/search/datatable', PointSearchDatatableController::class)->name('points.search.datatable');;
    Route::get('/points/datatable', [PointController::class, 'datatable'])->name('points.datatable');
    Route::post('/points/bulk-geocode', PointBulkGeolocationController::class)->name('points.bulk-geocode');
    Route::post('/points/bulk-destroy', PointBulkDeleteController::class)->name('points.bulk-destroy');
    Route::resource('points', PointController::class);
});
