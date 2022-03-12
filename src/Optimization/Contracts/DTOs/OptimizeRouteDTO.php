<?php

namespace Router\Optimization\Contracts\DTOs;

use Illuminate\Support\Collection;
use Router\Routes\Domain\Models\Route;
use Router\Routes\Domain\Models\Waypoint;
use Spatie\DataTransferObject\DataTransferObject;

class OptimizeRouteDTO extends DataTransferObject
{
    public int $id;
    public string $method;
    /** @var Collection<OptimizeWaypointDTO> */
    public Collection $waypoints;
    public ?string $distance;
    public ?string $time;

    public static function fromRoute(Route $route, string $method): OptimizeRouteDTO
    {
        $route->load(['waypoints', 'waypoints.point']);
        $waypoints = $route->waypoints->map(function (Waypoint $waypoint) {
            return new OptimizeWaypointDTO([
                'id' => $waypoint->id,
                'lat' => $waypoint->point->lat,
                'lng' => $waypoint->point->long
            ]);
        });

        return new self([
            'id' => $route->id,
            'method' => $method,
            'waypoints' => $waypoints,
        ]);
    }
}
