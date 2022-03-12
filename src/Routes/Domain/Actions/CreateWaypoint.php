<?php

namespace Router\Routes\Domain\Actions;

use Router\Routes\Contracts\DTOs\WaypointDTO;
use Router\Routes\Domain\Models\Waypoint;

class CreateWaypoint
{
    public function execute(WaypointDTO $data): Waypoint
    {
        $waypoint = new Waypoint();

        $stopNumber = $data->stopNumber;

        if(!$data->stopNumber){
            $stopNumber = $data->route->waypoints()->count() + 1;
        }

        $waypoint->route_id = $data->route->id;
        $waypoint->point_id = $data->point->id;
        $waypoint->content = $data->content;

        $quantity = (int)$data->quantity;

        $waypoint->quantity = $quantity > 0 ? $quantity : 1;
        $waypoint->status = 'undelivered';
        $waypoint->stop_number = $stopNumber;


        $waypoint->save();


        return $waypoint;
    }
}
