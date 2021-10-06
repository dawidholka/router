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

        $stopNumber = $data->stopNumber;

        if(!$data->stopNumber){
            $stopNumber = $data->route->waypoints()->count() + 1;
        }

        $waypoint->route_id = $data->route->id;
        $waypoint->point_id = $data->point->id;
        $waypoint->content = $data->content;
        $waypoint->quantity = $data->quantity;
        $waypoint->status = 'undelivered';
        $waypoint->stop_number = $stopNumber;


        $waypoint->save();


        return $waypoint;
    }
}
