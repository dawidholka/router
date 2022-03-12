<?php

namespace Router\Optimization\Domain\Actions;

use Router\Optimization\Contracts\DTOs\OptimizeRouteDTO;
use Router\Optimization\Contracts\DTOs\OptimizeWaypointDTO;
use Router\Optimization\Infrastructure\Services\RouteXLService;

class RouteXLOptimization implements OptimizationAction
{
    public function __construct(private RouteXLService $routeXL)
    {
    }

    public function execute(OptimizeRouteDTO $route): OptimizeRouteDTO
    {
        $locations = [];

        foreach ($route->waypoints as $waypoint) {
            $locations[] = [
                'address' => $waypoint->id,
                'lat' => $waypoint->lat,
                'lng' => $waypoint->lng
            ];
        }

        $data = $this->routeXL->optimize($locations);
        $data = collect($data['route']);

        foreach ($data as $index => $location) {
            $route->waypoints->map(function (OptimizeWaypointDTO $waypoint) use ($location, $index) {
                if ($waypoint->id == $location['name']) {
                    $waypoint->stopNumber = ($index + 1);
                }

                return $waypoint;
            });
            $route->distance = $location['distance'];
            $route->time = $location['arrival'];
        }

        return $route;
    }
}
