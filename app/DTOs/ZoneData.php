<?php

namespace App\DTOs;

use App\Models\Driver;
use App\Models\Zone;
use App\Support\ColorDictionary;
use Spatie\DataTransferObject\DataTransferObject;

class ZoneData extends DataTransferObject
{
    public string $name;
    public string $color;
    public ?string $coords;
    public ?Zone $zone;
    public ?Driver $driver;

    public static function fromArray(array $data): ZoneData
    {
        $zone = null;
        $driver = null;

        if (isset($data['id'])) {
            $zone = Zone::whereId($data['id'])->first();
        }

        if (isset($data['driver'])) {
            $driver = Driver::whereId($data['driver']['id'])->first();
        }

        return new self(
            zone: $zone,
            name: $data['name'] ?? __('Zone'),
            color: $data['color'] ?? ColorDictionary::getRandomColor(),
            coords: isset($data['coords']) ? json_encode($data['coords']) : null,
            driver: $driver
        );
    }
}
