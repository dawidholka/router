<?php

namespace Router\Points\Domain\Actions;

use Router\Points\Contracts\DTOs\PointDTO;
use Router\Points\Domain\Models\Point;

class FirstOrCreatePoint
{
    public function execute(PointDTO $pointData): Point
    {
        $point = Point::whereName($pointData->name)
            ->whereStreet($pointData->street)
            ->whereBuildingNumber($pointData->building_number)
            ->whereCity($pointData->city)
            ->whereApartament($pointData->apartament)
            ->whereIntercom($pointData->intercom)
            ->first(['id', 'phone', 'delivery_time', 'note', 'postcode', 'lat', 'long', 'lock_geo']);

        if (!$point) {
            $point = Point::create([
                'name' => $pointData->name,
                'street' => $pointData->street,
                'building_number' => $pointData->building_number,
                'city' => $pointData->city,
                'apartament' => $pointData->apartament,
                'intercom' => $pointData->intercom,
            ]);
        }

        $point->phone = $pointData->phone;
        $point->delivery_time = $pointData->delivery_time;
        $point->note = $pointData->note;
        $point->postcode = $pointData->postcode;

        $point->lat = $pointData->lat ?? $point->lat;
        $point->long = $pointData->long ?? $point->long;
        $point->lock_geo = $pointData->lock_geo ?? $point->lock_geo;

        $point->save();

        return $point;
    }
}
