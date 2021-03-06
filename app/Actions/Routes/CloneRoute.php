<?php

namespace App\Actions\Routes;

use App\DTOs\WaypointData;
use App\Models\Route;

class CloneRoute
{
    public function __construct(
        private CreateRoute    $createRoute,
        private CreateWaypoint $createWaypoint
    )
    {

    }

    public function execute(
        Route $route,
    ): Route
    {
        $newRoute = $this->createRoute->execute(
            $route->delivery_time,
            $route->driver_id,
            $route->note
        );

        $route->load('waypoints', 'waypoints.point');

        foreach ($route->waypoints as $waypoint) {
            $data = new WaypointData([
                'route' => $newRoute,
                'point' => $waypoint->point
            ]);
            $this->createWaypoint->execute($data);
        }

        return $newRoute;
    }
}
