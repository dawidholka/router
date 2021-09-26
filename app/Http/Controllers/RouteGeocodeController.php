<?php

namespace App\Http\Controllers;

use App\Actions\Points\GeocodePoint;
use App\Actions\Routes\GeocodeRoute;
use App\Models\Route;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RouteGeocodeController extends Controller
{
    public function __invoke(Route $route, GeocodePoint $geocodePoint): RedirectResponse
    {
        abort_if(!auth()->user()->admin, 403);

        $route->load(['waypoints', 'waypoints.point']);
        $waypoints = $route->waypoints;

        foreach ($waypoints as $waypoint) {
            try {
                $geocodePoint->execute($waypoint->point);
            } catch (\Exception $e) {
                continue;
            }
        }

        return redirect()->back();
    }
}
