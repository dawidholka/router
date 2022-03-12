<?php

namespace Router\Routes\Application\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Router\Points\Domain\Actions\BulkGecodePoints;
use Router\Routes\Domain\Models\Route;
use Router\Points\Application\Jobs\GeocodePoints;

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

        GeocodePoints::dispatch($points);

        return redirect()->back();
    }
}
