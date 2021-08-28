<?php

use App\Http\Controllers\DriverController;
use App\Http\Controllers\PointController;
use App\Http\Controllers\RouteController;
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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');


Route::middleware(['auth:sanctum'])->group(function () {
    Route::resource('points', PointController::class);
    Route::resource('drivers', DriverController::class);
    Route::resource('routes', RouteController::class);
});


Route::get('/create-driver', function () {
    \App\Models\Driver::create([
        'name' => 'Dawid',
        'login' => 'dawid',
        'password' => \Illuminate\Support\Facades\Hash::make('testowe123')
    ]);
});
