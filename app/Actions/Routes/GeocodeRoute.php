<?php

namespace App\Actions\Routes;

use App\Actions\Points\GeocodePoint;
use App\Models\Route;
use App\Models\Waypoint;

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
