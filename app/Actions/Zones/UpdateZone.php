<?php

namespace App\Actions\Zones;

use App\DTOs\ZoneData;
use App\Models\Zone;

class UpdateZone
{
    public function execute(Zone $zone, ZoneData $data): Zone
    {

        $zone->name = $data->name;

        $zone->save();

        return $zone;
    }
}
