<?php

use App\Http\Controllers\BulkDeleteController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\CreatorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\DriverSearchController;
use App\Http\Controllers\GeneralSettingsController;
use App\Http\Controllers\ImportSettingsController;
use App\Http\Controllers\MapPinSvgController;
use App\Http\Controllers\PointBulkDeleteController;
use App\Http\Controllers\PointBulkGeolocationController;
use App\Http\Controllers\PointController;
use App\Http\Controllers\PointGeolocationController;
use App\Http\Controllers\PointSearchDatatableController;
use App\Http\Controllers\RouteAddPointController;
use App\Http\Controllers\RouteBulkDeleteController;
use App\Http\Controllers\RouteCheckStatusController;
use App\Http\Controllers\RouteCloneController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\RouteFileImportController;
use App\Http\Controllers\RouteGeocodeController;
use App\Http\Controllers\RouteOptimizeController;
use App\Http\Controllers\RouteResumeEndController;
use App\Http\Controllers\RouteSetCurrentController;
use App\Http\Controllers\RouteWaypointsDestroyController;
use App\Http\Controllers\RouteWaypointsOrderController;
use App\Http\Controllers\RouteXLTXTController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WaypointPhotoController;
use App\Http\Controllers\ZoneBulkDeleteController;
use App\Http\Controllers\ZoneController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

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
    Route::post('/points/{point}/geolocation', PointGeolocationController::class)->name('points.geolocation');
    Route::get('/points/search/datatable', PointSearchDatatableController::class)->name('points.search.datatable');;
    Route::get('/points/datatable', [PointController::class, 'datatable'])->name('points.datatable');
    Route::post('/points/bulk-geocode', PointBulkGeolocationController::class)->name('points.bulk-geocode');
    Route::post('/points/bulk-destroy', PointBulkDeleteController::class)->name('points.bulk-destroy');
    Route::resource('points', PointController::class);
    Route::get('/drivers/search', DriverSearchController::class)->name('drivers.search');
    Route::get('/drivers/datatable', [DriverController::class, 'datatable'])->name('drivers.datatable');
    Route::resource('drivers', DriverController::class);
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
    Route::resource('routes', RouteController::class);
    Route::get('/users/datatable', [UserController::class, 'datatable'])->name('users.datatable');
    Route::resource('users', UserController::class);
    Route::delete('/zones/bulk-destroy', ZoneBulkDeleteController::class)->name('zones.bulk-destroy');
    Route::resource('zones', ZoneController::class);
    Route::post('bulk-destroy', BulkDeleteController::class)->name('bulk-destroy');
    Route::get('waypoints/{waypoint}/photo', WaypointPhotoController::class)->name('waypoints.photo');
    Route::prefix('settings')->group(function () {
        Route::get('general', [GeneralSettingsController::class, 'show'])->name('settings.general');
        Route::put('general', [GeneralSettingsController::class, 'update'])->name('settings.general.update');
        Route::get('import', [ImportSettingsController::class, 'show'])->name('settings.import');
        Route::put('import', [ImportSettingsController::class, 'update']);
    });
});
