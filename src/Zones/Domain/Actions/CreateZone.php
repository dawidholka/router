<?php

namespace Router\Zones\Domain\Actions;

use Illuminate\Support\Facades\Cache;
use Router\Zones\Contracts\DTOs\ZoneDTO;
use Router\Zones\Domain\Models\Zone;

class CreateZone
{
    public function execute(ZoneDTO $data): Zone
    {
        $zone = new Zone();
        $zone->color = $data->color;
        $zone->name = $data->name;
        $zone->coords = $data->coords;

        $zone->save();

        Cache::forget('zones');

        return $zone;
    }
}
