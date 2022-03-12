<?php

use Illuminate\Support\Facades\Route;
use Router\Zones\Application\Http\Controllers\ZoneBulkDeleteController;
use Router\Zones\Application\Http\Controllers\ZoneBulkUpdateController;
use Router\Zones\Application\Http\Controllers\ZoneController;
use Router\Zones\Application\Http\Controllers\ZoneEditorController;
use Router\Zones\Application\Http\Controllers\ZoneSearchController;

Route::middleware(['web', 'auth:sanctum'])->prefix('zones')->group(function () {
    Route::get('/zones/search', ZoneSearchController::class)->name('zones.search');
    Route::delete('/zones/bulk-destroy', ZoneBulkDeleteController::class)->name('zones.bulk-destroy');
    Route::post('/zones/bulk-update', ZoneBulkUpdateController::class)->name('zones.bulk-update');
    Route::get('zones/editor', [ZoneEditorController::class, 'index'])->name('zones.editor');
    Route::resource('zones', ZoneController::class);
});
