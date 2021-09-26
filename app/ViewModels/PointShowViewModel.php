<?php

namespace App\ViewModels;

use App\Models\Point;
use App\Models\Waypoint;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\ViewModels\ViewModel;

class PointShowViewModel extends ViewModel
{
    public function __construct(private Point $point)
    {
        $this->point->load([
            'waypoints' => function(HasMany $hasMany) {
                $hasMany->latest();
            }
        ]);
    }

    public function point(): array
    {
        return [
            'id' => $this->point->id,
            'name' => $this->point->name,
            'street' => $this->point->street,
            'building_number' => $this->point->building_number,
            'city' => $this->point->city,
            'apartament' => $this->point->apartament,
            'intercom' => $this->point->intercom,
            'delivery_time' => $this->point->delivery_time,
            'phone' => $this->point->phone,
            'note' => $this->point->note,
            'lat' => $this->point->lat,
            'long' => $this->point->long,
            'lock_geo' => $this->point->lock_geo,
            'created_at' => $this->point->created_at?->format('Y-m-d H:m:s'),
            'updated_at' => $this->point->updated_at?->format('Y-m-d H:m:s'),
            'routes' => $this->point->waypoints->map(function (Waypoint $waypoint) {
                return [
                    'id' => $waypoint->id,
                    'route_id' => $waypoint->route_id,
                    'status' => $waypoint->status,
                    'content' => $waypoint->content,
                    'quantity' => $waypoint->quantity,
                    'delivered_time' => $waypoint->delivered_time?->format('Y-m-d'),
                    'photo_uploaded' => $waypoint->photo_uploaded,
                ];
            })
        ];
    }

}
