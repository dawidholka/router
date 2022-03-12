<?php

namespace Router\Routes\Domain\Actions;

use Router\Points\Domain\Actions\GeocodePoint;
use Router\Routes\Domain\Models\Route;
use Router\Routes\Domain\Models\Waypoint;

class GeocodeRoute
{
    public function execute(Route $route, GeocodePoint $geocodePoint)
    {
        $waypoints = $route->waypoints;

        $waypoints->load('points');

        /** @var Waypoint $waypoint */
        foreach ($waypoints as $waypoint) {
            if($waypoint->point->geocoded){
                continue;
            }
            $geocodePoint->execute($waypoint->point);
        }
    }
}
