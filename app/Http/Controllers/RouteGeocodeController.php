<?php

namespace App\Http\Controllers;

use App\Actions\Points\BulkGecodePoints;
use App\Models\Route;
use Illuminate\Http\RedirectResponse;

class RouteGeocodeController extends Controller
{
    public function __invoke(
        Route $route,
        BulkGecodePoints $bulkGecodePoints
    ): RedirectResponse
    {
        abort_if(!auth()->user()->admin, 403);

        $route->load(['waypoints', 'waypoints.point']);
        $waypoints = $route->waypoints;
        $points = $waypoints->pluck('point');

        $bulkGecodePoints->execute($points);

        return redirect()->back();
    }
}
