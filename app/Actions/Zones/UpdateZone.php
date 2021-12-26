<?php

namespace App\Actions\Zones;

use App\DTOs\ZoneData;
use App\Models\Zone;

class UpdateZone
{
    public function execute(Zone $zone, ZoneData $data): Zone
    {
        $zone->name = $data->name;
        $zone->color = $data->color;
        $zone->driver_id = $data?->driver?->id;

        if ($data->coords) {
            $zone->coords = $data->coords;
        }

        $zone->save();

        return $zone;
    }
}
