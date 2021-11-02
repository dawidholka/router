<?php

namespace App\ViewModels;

use App\Models\Driver;
use Spatie\ViewModels\ViewModel;

class DashboardViewModel extends ViewModel
{
    private $drivers;

    public function __construct()
    {
        $drivers = Driver::whereNotNull('route_id')->get();
        $drivers->load([
            'route',
            'route.waypoints'
        ]);

        $this->drivers = $drivers;
    }

    public function driversData()
    {
        return $this->drivers->map(function (Driver $driver) {
            $waypoints = $driver->route->waypoints->count();
            $delivered = $driver->route->waypoints->where('status', 'delivered')->count();
            $undelivered = $driver->route->waypoints->where('status', 'undelivered')->count();
            $problem = $driver->route->waypoints->where('status', 'problem')->count();

            return [
                'name' => $driver->name,
                'color' => $driver->color,
                'percent' => ($waypoints > 0) ? ($delivered / $waypoints) * 100 : 0,
                'waypoints' => $waypoints,
                'delivered' => $delivered,
                'undelivered' => $undelivered,
                'problem' => $problem,
                'route_id' => $driver->route_id,
            ];
        })->toArray();
    }
}
