<?php

namespace App\Http\Controllers;

use App\Actions\Routes\Optimize\RouteXLManualOptimize;
use App\Models\Route;
use App\Settings\GeneralSettings;
use Illuminate\Http\Request;

class RouteXLTXTController extends Controller
{
    public function __invoke(
        Route                 $route,
        Request               $request,
        RouteXLManualOptimize $routeXLManualOptimize
    )
    {
        abort_if(!auth()->user()->admin, 403);

        $request->validate([
            'last_location' => ['required', 'string', 'in:driver,last_waypoint,company']
        ]);

        $route->load([
            'waypoints' => function ($query) {
                $query->orderBy('stop_number');
            },
            'waypoints.point'
        ]);

        $end = $this->getLastLocation($route, $request['last_location']);

        $content = view('routexl-txt', compact('route', 'end'));

        return response($content)->header('Content-Type', 'text/plain');
    }

    private function getLastLocation(Route $route, ?string $last_location): ?string
    {
        if ($last_location === 'driver' && $route->driver_id) {
            return "@END@ " . $route->driver->lat . ',' . $route->driver->long;
        }
        if ($last_location === 'last_waypoint') {
            $waypoint = $route->waypoints->last();
            return "@END@ " . $waypoint->point->lat . ',' . $waypoint->point->long;
        }
        if ($last_location === 'company') {
            /** @var GeneralSettings $config */
            $config = app()->make(GeneralSettings::class);
            return "@END@ " . $config->company_lat . ',' . $config->company_lng;
        }

        return null;
    }
}
