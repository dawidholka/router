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

        $stops = $data->route->waypoints()->count();

        $waypoint->route_id = $data->route->id;
        $waypoint->point_id = $data->point->id;
        $waypoint->content = $data->content;
        $waypoint->quantity = $data->quantity;
        $waypoint->status = 'undelivered';
        $waypoint->stop_number = $stops + 1;


        $waypoint->save();


        return $waypoint;
    }
}
