<?php

namespace Router\Zones\Domain\Actions;

use App\Support\PointInPolygon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Router\Zones\Domain\Models\Zone;

class GetZoneByCoords
{
    private Collection $zones;

    public function __construct(
        private PointInPolygon $pointInPolygon
    )
    {
        $this->zones = Cache::rememberForever('zones', function () {
            return Zone::all();
        });
    }

    public function execute($lat, $lng): ?Zone
    {
        /** @var Zone $zone */
        foreach ($this->zones as $zone) {
            $point = [$lat, $lng];
            $polygon = json_decode($zone->coords, true);

            if ($this->pointInPolygon->check($point, $polygon) === true) {
                return $zone;
            }
        }

        return null;
    }
}
