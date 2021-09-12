<?php

namespace App\Actions\Routes;

use App\Models\Waypoint;

class UpdateWaypointsOrder
{
    public function execute(array $waypointIds)
    {
        $stopNumber = 1;
        foreach ($waypointIds as $waypointId) {
            $waypoint = Waypoint::findOrFail($waypointId);
            $waypoint->stop_number = $stopNumber++;
            $waypoint->save();
        }
    }
}
