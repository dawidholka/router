<?php

namespace App\Actions\Routes\Optimize;

use App\DTOs\RouteOptimizeDTO;
use App\Models\Route;
use App\Services\OpenRouteService;
use App\Services\RouteXL;
use App\Support\OptimizeDictionary;
use Illuminate\Database\Eloquent\Collection;

class OpenRouteServiceOptimize implements RouteOptimizeMethod
{
    public function __construct(private OpenRouteService $openRouteService)
    {

    }

    public function execute(Route $route, RouteOptimizeDTO $data): Route
    {
        $route->load([
            'waypoints' => function ($query) {
                $query->orderBy('stop_number');
            },
            'waypoints.point'
        ]);

        /** @var Collection $waypoints */
        $waypoints = $route->waypoints;

        $locations = [];

        foreach ($route->waypoints as $waypoint) {
            $locations[] = [
                'id' => $waypoint->id,
                'location' => [$waypoint->point->lat, $waypoint->point->long],
                'service' => 300
            ];
        }
        $vehicles = [
            [
                'id' => 1,
                'profile' => 'driving-car',
                'start' => $locations[0]['location'],
                'end' => $locations[0]['location'],
            ]
        ];

        $data = $this->openRouteService->optimization($locations, $vehicles);

        $optimizedLocations = collect($data['route']);
        $totalDistance = 0;
        $totalTime = 0;

        foreach ($optimizedLocations as $key => $location) {
            $currentWaypoint = $waypoints->where('id', $location['name'])->first();
            $waypoint->update([
                'stop_number' => ($key + 1)
            ]);
            $totalDistance = $location['distance'];
            $totalTime = $location['arrival'];
        }

        $route->update([
            'distance' => $totalDistance,
            'time' => $totalTime,
            'optimize_status' => OptimizeDictionary::ROUTEXL
        ]);

        return $route;
    }
}
