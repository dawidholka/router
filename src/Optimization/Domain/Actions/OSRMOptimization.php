<?php

namespace Router\Optimization\Domain\Actions;

use Router\Optimization\Contracts\DTOs\OptimizeRouteDTO;
use Router\Optimization\Infrastructure\Services\OSRMService;

class OSRMOptimization implements OptimizationAction
{
    public function __construct(private OSRMService $osrm)
    {

    }

    public function execute(OptimizeRouteDTO $route): OptimizeRouteDTO
    {
        $locations = collect([]);

        foreach ($route->waypoints as $waypoint) {
            $locations->push([
                'id' => $waypoint->id,
                'location' => "{$waypoint->lng},{$waypoint->lat}"
            ]);
        }

        $data = $this->osrm->trip($locations, true, 'first', 'last');

        foreach ($data->waypoints as $id => $waypoint) {
            $route->waypoints[$id]->stop_number = $waypoint->waypoint_index + 1;
        }

        $route->distance = $data->trips[0]->distance / 1000;
        $route->time = $data->trips[0]->duration / 60 / 60;

        return $route;
    }
}
