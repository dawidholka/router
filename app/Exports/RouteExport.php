<?php

namespace App\Exports;

use App\Models\Route;
use App\Models\Waypoint;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Maatwebsite\Excel\Concerns\FromCollection;

class RouteExport implements FromCollection
{
    public function __construct(private Route $route)
    {
    }

    public function collection()
    {
        $this->route->load([
                'waypoints' => function (HasMany $hasMany) {
                    $hasMany->latest();
                },
                'waypoints.point']
        );

        return $this->route->waypoints->map(function (Waypoint $waypoint) {
            return [
                'stop_number' => $waypoint->stop_number,
                'name' => $waypoint->point->name,
                'street' => $waypoint->point->street,
                'building_number' => $waypoint->point->building_number,
                'apartament' => $waypoint->point->apartament,
                'postcode' => $waypoint->point->postcode,
                'city' => $waypoint->point->city,
                'intercom' => $waypoint->point->intercom,
                'phone' => $waypoint->point->phone,
                'note' => $waypoint->point->note,
                'content' => $waypoint->content,
                'quantity' => $waypoint->quantity,
            ];
        });
    }
}
