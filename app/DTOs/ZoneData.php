<?php

namespace App\DTOs;

use App\Models\Zone;
use App\Support\ColorDictionary;
use Spatie\DataTransferObject\DataTransferObject;

class ZoneData extends DataTransferObject
{
    public ?Zone $zone;
    public string $name;
    public string $color;
    public string $coords;

    public static function fromArray(array $data): ZoneData
    {
        $zone = null;

        if (isset($data['id'])) {
            $zone = Zone::whereId($data['id'])->first();
        }

        return new self(
            zone: $zone,
            name: $data['name'] ?? __('Zone'),
            color: $data['color'] ?? ColorDictionary::getRandomColor(),
            coords: json_encode($data['coords'])
        );
    }
}
