<?php

use App\Http\Controllers\CreatorController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\DriverSearchController;
use App\Http\Controllers\MapPinSvgController;
use App\Http\Controllers\PointController;
use App\Http\Controllers\PointGeolocationController;
use App\Http\Controllers\RouteCloneController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\RouteFileImportController;
use App\Http\Controllers\RouteGeocodeController;
use App\Http\Controllers\RouteWaypointsDestroyController;
use App\Http\Controllers\RouteWaypointsOrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ZoneBulkDeleteController;
use App\Http\Controllers\ZoneController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('creator', [CreatorController::class, 'show'])->name('creator');
    Route::post('creator', [CreatorController::class, 'store']);
    Route::post('/points/{point}/geolocation', PointGeolocationController::class)->name('points.geolocation');
    Route::get('/points/datatable', [PointController::class, 'datatable'])->name('points.datatable');
    Route::resource('points', PointController::class);
    Route::get('/drivers/search', DriverSearchController::class)->name('drivers.search');
    Route::get('/drivers/datatable', [DriverController::class, 'datatable'])->name('drivers.datatable');
    Route::resource('drivers', DriverController::class);
    Route::post('/routes/{route}/clone', RouteCloneController::class)->name('routes.clone');
    Route::post('/routes/{route}/waypoints/order', RouteWaypointsOrderController::class)->name('routes.waypoints.order');
    Route::post('/routes/{route}/waypoints/geocode', RouteGeocodeController::class)->name('routes.waypoints.geocode');
    Route::post('/routes/{route}/waypoints/destroy', RouteWaypointsDestroyController::class)->name('routes.waypoints.destroy');
    Route::post('/routes/{route}/import-file', RouteFileImportController::class)->name('routes.import-file');
    Route::get('/routes/datatable', [RouteController::class, 'datatable'])->name('routes.datatable');
    Route::resource('routes', RouteController::class);
    Route::get('/users/datatable', [UserController::class, 'datatable'])->name('users.datatable');
    Route::resource('users', UserController::class);
    Route::delete('/zones/bulk-destroy', ZoneBulkDeleteController::class)->name('zones.bulk-destroy');
    Route::resource('zones', ZoneController::class);
});


Route::get('/create-driver', function () {
    \App\Models\Driver::create([
        'name' => 'Dawid',
        'login' => 'dawid',
        'password' => \Illuminate\Support\Facades\Hash::make('testowe123')
    ]);
});
