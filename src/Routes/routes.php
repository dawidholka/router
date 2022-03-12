<?php

use Illuminate\Support\Facades\Route;
use Router\Routes\Application\Http\Controllers\RouteAddPointController;
use Router\Routes\Application\Http\Controllers\RouteBulkDeleteController;
use Router\Routes\Application\Http\Controllers\RouteCheckStatusController;
use Router\Routes\Application\Http\Controllers\RouteCloneController;
use Router\Routes\Application\Http\Controllers\RouteController;
use Router\Routes\Application\Http\Controllers\RouteExportXLSXController;
use Router\Routes\Application\Http\Controllers\RouteFileImportController;
use Router\Routes\Application\Http\Controllers\RouteGeocodeController;
use Router\Routes\Application\Http\Controllers\RouteMoveWaypointsController;
use Router\Routes\Application\Http\Controllers\RouteOptimizeController;
use Router\Routes\Application\Http\Controllers\RouteResumeEndController;
use Router\Routes\Application\Http\Controllers\RouteSearchController;
use Router\Routes\Application\Http\Controllers\RouteSetCurrentController;
use Router\Routes\Application\Http\Controllers\RouteWaypointsDestroyController;
use Router\Routes\Application\Http\Controllers\RouteWaypointsOrderController;
use Router\Routes\Application\Http\Controllers\RouteXLTXTController;

Route::middleware(['web', 'auth:sanctum'])->prefix('routes')->group(function () {
    Route::post('/routes/move-waypoints', RouteMoveWaypointsController::class)->name('routes.move-waypoints');
    Route::get('/routes/{route}/export-xlsx', RouteExportXLSXController::class)->name('routes.export-xlsx');
    Route::post('/routes/{route}/set-as-current', RouteSetCurrentController::class)->name('routes.set-as-current');
    Route::post('/routes/{route}/add-point', RouteAddPointController::class)->name('routes.add-point');
    Route::get('/routes/{route}/routexl-txt', RouteXLTXTController::class)->name('routes.generate-txt');
    Route::post('/routes/{route}/check-status', RouteCheckStatusController::class)->name('routes.check-status');
    Route::post('/routes/{route}/end-resume', RouteResumeEndController::class)->name('routes.end-resume');
    Route::post('/routes/{route}/optimize', RouteOptimizeController::class)->name('routes.optimize');
    Route::post('/routes/{route}/clone', RouteCloneController::class)->name('routes.clone');
    Route::post('/routes/{route}/waypoints/order', RouteWaypointsOrderController::class)->name('routes.waypoints.order');
    Route::post('/routes/{route}/waypoints/geocode', RouteGeocodeController::class)->name('routes.waypoints.geocode');
    Route::post('/routes/{route}/waypoints/destroy', RouteWaypointsDestroyController::class)->name('routes.waypoints.destroy');
    Route::post('/routes/{route}/import-file', RouteFileImportController::class)->name('routes.import-file');
    Route::get('/routes/datatable', [RouteController::class, 'datatable'])->name('routes.datatable');
    Route::post('/routes/bulk-destroy', RouteBulkDeleteController::class)->name('routes.bulk-destroy');
    Route::get('/routes/search', RouteSearchController::class)->name('routes.search');
    Route::resource('routes', RouteController::class);
});
