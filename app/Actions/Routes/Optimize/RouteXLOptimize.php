<?php

namespace App\Actions\Routes\Optimize;

use App\DTOs\RouteOptimizeDTO;
use App\Models\Route;
use App\Services\RouteXL;
use App\Support\OptimizeDictionary;
use Illuminate\Database\Eloquent\Collection;

class RouteXLOptimize implements RouteOptimizeMethod
{
    public function __construct(private RouteXL $routeXL)
    {

    }

    public function execute(Route $route, RouteOptimizeDTO $data): Route
    {
        $route->load([
            'waypoints' => function ($query) {
                $query->orderBy('stop_number');
            },
            'waypoints.point'
        ]);

        /** @var Collection $waypoints */
        $waypoints = $route->waypoints;

        $locations = [];

        foreach ($route->waypoints as $waypoint) {
            $locations[] = [
                'address' => $waypoint->id,
                'lat' => $waypoint->point->lat,
                'lng' => $waypoint->point->long
            ];
        }

        $data = $this->routeXL->optimize($locations);
        $optimizedLocations = collect($data['route']);
        $totalDistance = 0;
        $totalTime = 0;

        foreach ($optimizedLocations as $key => $location) {
            $currentWaypoint = $waypoints->where('id', $location['name'])->first();
            $waypoint->update([
                'stop_number' => ($key + 1)
            ]);
            $totalDistance = $location['distance'];
            $totalTime = $location['arrival'];
        }

        $route->update([
            'distance' => $totalDistance,
            'time' => $totalTime,
            'optimize_status' => OptimizeDictionary::ROUTEXL
        ]);

        return $route;
    }
}
