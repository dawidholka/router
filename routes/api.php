<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\DriverLocationController;
use App\Http\Controllers\API\RouteController;
use App\Http\Controllers\API\WaypointDriverNoteUpdateController;
use App\Http\Controllers\API\WaypointImageUploadController;
use App\Http\Controllers\API\WaypointStatusUpdateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/create-token', [AuthController::class, 'createToken']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/routes', [RouteController::class, 'index']);
    Route::get('/routes/{route}', [RouteController::class, 'show']);
    Route::post('/driver/location', DriverLocationController::class);
    Route::post('/waypoints/{waypoint}/upload-image', WaypointImageUploadController::class);
    Route::post('/waypoints/{waypoint}/change-status', WaypointStatusUpdateController::class);
    Route::put('/waypoints/{waypoint}/update-note', WaypointDriverNoteUpdateController::class);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
