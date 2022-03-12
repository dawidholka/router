<?php

namespace Router\Routes\Domain\Actions;

use Illuminate\Support\Collection;
use Router\Routes\Contracts\DTOs\WaypointDTO;
use Router\Routes\Domain\Models\Waypoint;

class CreateBulkWaypoint
{
    public function execute(Collection $data)
    {
        $insertData = [];

        $now = now();

        /** @var WaypointDTO $waypointData */
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
