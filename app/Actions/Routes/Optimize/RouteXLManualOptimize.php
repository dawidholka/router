<?php

namespace App\Actions\Routes\Optimize;

use App\DTOs\RouteOptimizeDTO;
use App\Models\Route;
use App\Models\Waypoint;
use App\Services\RouteXL;
use App\Support\OptimizeDictionary;
use Illuminate\Database\Eloquent\Collection;
use PhpOffice\PhpSpreadsheet\IOFactory;

class RouteXLManualOptimize implements RouteOptimizeMethod
{
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
        $sheet = IOFactory::load($data->file->path())->getActiveSheet();

        $currentStopNumber = 1;
        $totalDistance = 0.0;
        $totalTime = 0.0;
        $totalStops = $waypoints->count();

        for($i = 0; $i < $totalStops; $i++){
            $currentWaypointId = $sheet->getCell('E'.($i+3))->getValue();
            $currentDistance = $sheet->getCell('B'.($i+3))->getValue();
            $currentWaypoint = $waypoints->where('id', '=', $currentWaypointId)->first();
            $currentWaypoint->update([
                'stop_number' => $currentStopNumber++
            ]);
            $totalDistance += $currentDistance;
        }

        $route->distance = $totalDistance;
        $route->save();
        return $route;
    }
}
