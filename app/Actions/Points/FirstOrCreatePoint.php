<?php

namespace App\Actions\Points;

use App\DTOs\PointData;
use App\Models\Point;

class FirstOrCreatePoint
{
    public function execute(PointData $pointData): Point
    {
        return Point::updateOrCreate([
            'name' => $pointData->name,
            'street' => $pointData->street,
            'building_number' => $pointData->building_number,
            'city' => $pointData->city,
            'apartament' => $pointData->apartament,
            'intercom' => $pointData->intercom,
        ], [
            'delivery_time' => $pointData->delivery_time,
            'phone' => $pointData->phone,
            'note' => $pointData->note,
            'postcode' => $pointData->postcode,
            'lat' => $pointData->lat,
            'long' => $pointData->long,
            'lock_geo' => $pointData->lock_geo
        ]);
    }
}
