<?php

namespace App\Actions\Routes;

use App\DTOs\WaypointData;
use App\Models\Point;
use App\Models\Route;
use App\Models\Waypoint;

class CreateWaypoint
{
    public function execute(WaypointData $data): Waypoint
    {
        $waypoint = new Waypoint();

        $waypoint->route_id = $data->route->id;
        $waypoint->point_id = $data->point->id;
        $waypoint->status = 0;
        $waypoint->stop_number = 0;

        $waypoint->save();


        return $waypoint;
    }
}
