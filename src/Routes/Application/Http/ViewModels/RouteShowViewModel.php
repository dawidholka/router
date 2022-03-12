<?php

namespace Router\Routes\Application\Http\ViewModels;

use App\Models\DriverLocation;
use Illuminate\Database\Eloquent\Collection;
use Router\Drivers\Domain\Models\Driver;
use Router\Routes\Domain\Models\Route;
use Router\Routes\Domain\Models\Waypoint;
use Spatie\ViewModels\ViewModel;

class RouteShowViewModel extends ViewModel
{
    public function __construct(private Route $route)
    {
        $route->load([
            'waypoints' => function ($query) {
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
            'id' => $this->route->id,
            'delivery_time' => $this->route->delivery_time?->format('Y-m-d'),
            'status_translated' => __('routes.status.' . $this->route->status),
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
            ] : null,
        ];
    }

    public function waypoints(): array
    {
        return $this->route->waypoints->map(function (Waypoint $waypoint) {
            return [
                'id' => $waypoint->id,
                'name' => $waypoint->point->name,
                'address' => $waypoint->point->address,
                'city' => $waypoint->point->city,
                'stop_number' => $waypoint->stop_number,
                'status' => __('waypoints.status.' . $waypoint->status),
                'delivered_time' => $waypoint->delivered_time?->format('Y-m-d H:i:s'),
                'photo_uploaded' => $waypoint->photo_uploaded,
                'driver_note' => $waypoint->point->driver_note ?? '-',
                'geocoded' => $waypoint->point->geocoded,
                'postcode' => $waypoint->point->postcode,
                'quantity' => $waypoint->quantity,
                'content' => $waypoint->content,
                'color' => $waypoint->status_color,
                'lat' => $waypoint->point->lat,
                'lng' => $waypoint->point->long
            ];
        })->toArray();
    }

    public function driverLocations(): array
    {
        return $this->route->driver_locations()->latest()->get()->map(function (DriverLocation $location) {
            return [
                'id' => $location->id,
                'lat' => $location->lat,
                'lng' => $location->lng
            ];
        })->toArray();
    }

    public function deliveryContent(): array
    {
        $contentArray = [];
        /** @var Collection $waypoints */
        $waypoints = $this->route->waypoints;
        $groupedByContent = $waypoints->groupBy('content');
        $groupedByContent->sortKeys();
        /**
         * @var String $content
         * @var Collection $items
         */
        foreach ($groupedByContent as $content => $items) {
            $count = $items->sum('quantity');
            $name = $content;
            if ($count < 1) {
                continue;
            }
            $contentArray[] = [
                'name' => $name,
                'count' => $count
            ];
        }

        $names = array_column($contentArray, 'name');
        array_multisort($contentArray, SORT_ASC, $names);

        return $contentArray;
    }
}
