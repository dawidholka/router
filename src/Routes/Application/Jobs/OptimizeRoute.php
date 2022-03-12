<?php

namespace Router\Routes\Application\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Router\Optimization\Contracts\DTOs\OptimizeRouteDTO;
use Router\Optimization\Contracts\DTOs\OptimizeWaypointDTO;
use Router\Optimization\Domain\Actions\Optimization;
use Router\Routes\Domain\Actions\UpdateWaypointsOrder;
use Router\Routes\Domain\Models\Waypoint;

class OptimizeRoute implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected OptimizeRouteDTO $route)
    {

    }

    public function handle(
        Optimization         $optimization,
        UpdateWaypointsOrder $updateWaypointsOrder
    )
    {
        $data = $optimization->execute($this->route);

        $waypointsIDs = $data->waypoints->sortBy('stop_number')->pluck('id');

        $updateWaypointsOrder->execute($waypointsIDs->toArray());
    }
}
