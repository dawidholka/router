<?php

namespace App\Actions\Zones;

use App\DTOs\ZoneData;
use App\Models\Zone;
use Illuminate\Support\Facades\Cache;

class CreateZone
{
    public function execute(ZoneData $data): Zone
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
