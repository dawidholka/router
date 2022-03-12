<?php

namespace Router\Zones\Domain\Actions;

use Router\Zones\Contracts\DTOs\ZoneDTO;
use Router\Zones\Domain\Models\Zone;

class UpdateZone
{
    public function execute(Zone $zone, ZoneDTO $data): Zone
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
