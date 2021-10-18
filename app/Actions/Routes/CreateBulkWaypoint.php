<?php

namespace App\Actions\Routes;

use App\DTOs\WaypointData;
use App\Models\Point;
use App\Models\Route;
use App\Models\Waypoint;
use Illuminate\Support\Collection;

class CreateBulkWaypoint
{
    public function execute(Collection $data)
    {
        $insertData = [];

        $now = now();

        /** @var WaypointData $waypointData */
        foreach ($data as $waypointData) {
            $insertData[] = [
                'route_id' => $waypointData->route->id,
                'point_id' => $waypointData->point->id,
                'content' => $waypointData->content,
                'quantity' => (int)$waypointData->quantity ?? 1,
                'status' => 'undelivered',
                'stop_number' => $waypointData->stopNumber,
                'created_at' => $now,
                'updated_at' => $now
            ];
        }

        Waypoint::insert($insertData);
    }
}
