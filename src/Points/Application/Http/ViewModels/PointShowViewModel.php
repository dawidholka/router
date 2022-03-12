<?php

namespace Router\Points\Application\Http\ViewModels;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Router\Points\Domain\Models\Point;
use Router\Routes\Domain\Models\Waypoint;
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
            'postcode' => $this->point->postcode ?? '-',
            'apartament' => $this->point->apartament,
            'intercom' => $this->point->intercom,
            'delivery_time' => $this->point->delivery_time,
            'phone' => $this->point->phone,
            'driver_note' => $this->point->driver_note,
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
                    'status' => __('waypoints.status.'.$waypoint->status),
                    'content' => $waypoint->content,
                    'quantity' => $waypoint->quantity,
                    'delivered_time' => $waypoint->delivered_time?->format('Y-m-d'),
                    'photo_uploaded' => $waypoint->photo_uploaded,
                ];
            })
        ];
    }

}
