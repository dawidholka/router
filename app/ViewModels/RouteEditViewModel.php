<?php

namespace App\ViewModels;

use App\Models\Driver;
use App\Models\Route;
use App\Models\Waypoint;
use App\Support\ColorDictionary;
use Spatie\ViewModels\ViewModel;

class RouteEditViewModel extends ViewModel
{
    public function __construct(private Route $route)
    {
        $route->load([
            'waypoints' => function ($query){
                $query->orderBy('stop_number');
            },
            'waypoints.point',
            'driver'
        ]);
    }

    public function drivers(): array
    {
        return Driver::all()->map(function (Driver $driver) {
            return [
                'id' => $driver->id,
                'name' => $driver->name,
            ];
        })->toArray();
    }

    public function viewRoute(): array
    {
        return [
            'id' => (int)$this->route->id,
            'delivery_time' => $this->route->delivery_time?->format('Y-m-d'),
            'status' => $this->route->status,
            'optimize_status' => $this->route->optimize_status,
            'distance' => $this->route->distance,
            'time' => $this->route->time,
            'driver_downloaded_at' => $this->route->driver_downloaded_at,
            'note' => $this->route->note,
            'created_at' => $this->route->created_at?->format('Y-m-d'),
            'updated_at' => $this->route->updated_at?->format('Y-m-d'),
            'driver' => $this->route->driver ? [
                'id' => $this->route->driver->id,
                'name' => $this->route->driver->name,
                'route' => (int)$this->route->driver->route_id,
            ] : null,
        ];
    }

    public function waypoints()
    {
        return $this->route->waypoints->map(function (Waypoint $waypoint) {
            return [
                'id' => $waypoint->id,
                'name' => $waypoint->point->name,
                'address' => $waypoint->point->address,
                'city' => $waypoint->point->city,
                'stop_number' => $waypoint->stop_number,
                'geocoded' => $waypoint->point->geocoded,
                'color' => $this->route->driver->color ?? ColorDictionary::getRandomColor(),
                'lat' => $waypoint->point->lat,
                'lng' => $waypoint->point->long
            ];
        })->toArray();
    }
}
