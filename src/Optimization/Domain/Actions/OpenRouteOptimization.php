<?php

namespace Router\Optimization\Domain\Actions;

use Router\Optimization\Contracts\DTOs\OptimizeRouteDTO;
use Router\Optimization\Contracts\DTOs\OptimizeWaypointDTO;
use Router\Optimization\Infrastructure\Services\OpenRouteService;

class OpenRouteOptimization implements OptimizationAction
{
    public function __construct(private OpenRouteService $openRouteService)
    {

    }

    public function execute(OptimizeRouteDTO $route): OptimizeRouteDTO
    {
        $locations = [];

        foreach ($route->waypoints as $waypoint) {
            $locations[] = [
                'id' => $waypoint->id,
                'location' => [$waypoint->lat, $waypoint->long],
                'service' => 300
            ];
        }

        $vehicles = [
            [
                'id' => 1,
                'profile' => 'driving-car',
                'start' => $locations[0]['location'],
                'end' => $locations[0]['location'],
            ]
        ];

        $data = $this->openRouteService->optimization($locations, $vehicles);

        $optimizedLocations = collect($data['route']);
        $totalDistance = 0;
        $totalTime = 0;

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
