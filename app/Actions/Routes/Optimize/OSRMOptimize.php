<?php

namespace App\Actions\Routes\Optimize;

use App\DTOs\RouteOptimizeDTO;
use App\Models\Route;
use App\Services\OpenRouteService;
use App\Services\OSRMService;
use App\Services\RouteXL;
use App\Support\OptimizeDictionary;
use Illuminate\Database\Eloquent\Collection;

class OSRMOptimize implements RouteOptimizeMethod
{
    public function __construct(private OSRMService $OSRMService)
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

        foreach ($waypoints as $waypoint) {
            $locations[] = [
                'id' => $waypoint->id,
                'location' => "{$waypoint->point->long},{$waypoint->point->lat}"
            ];
        }

        $locations = collect($locations);

        $data = $this->OSRMService->trip($locations, true, 'first', 'last');


        foreach ($data->waypoints as $waypointId => $waypoint) {
            $currentWaypoint = $waypoints[$waypointId];
            $currentWaypoint->update([
                'stop_number' => $waypoint->waypoint_index + 1
            ]);
        }

        $route->update([
            'distance' => $data->trips[0]->distance / 1000,
            'time' => $data->trips[0]->duration / 60 / 60,
            'optimize_status' => OptimizeDictionary::ROUTEXL
        ]);

        return $route;
    }
}
