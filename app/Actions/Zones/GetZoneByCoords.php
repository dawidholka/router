<?php

namespace App\Actions\Zones;

use App\Models\Zone;
use App\Support\PointInPolygon;
use Illuminate\Support\Facades\Cache;

class GetZoneByCoords
{
    public function __construct(
        private PointInPolygon $pointInPolygon
    )
    {
    }

    public function execute($lat, $lng): ?Zone
    {
        $zones = Cache::rememberForever('zones', function () {
            return Zone::all();
        });

        /** @var Zone $zone */
        foreach ($zones as $zone) {
            $point = [$lat, $lng];
            $polygon = json_decode($zone->coords, true)['outer'];

            if($this->pointInPolygon->check($point, $polygon) === true){
                return $zone;
            }
        }

        return null;
    }
}
