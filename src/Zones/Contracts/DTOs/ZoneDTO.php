<?php

namespace Router\Zones\Contracts\DTOs;

use App\Support\ColorDictionary;
use Router\Drivers\Domain\Models\Driver;
use Router\Zones\Domain\Models\Zone;
use Spatie\DataTransferObject\DataTransferObject;

class ZoneDTO extends DataTransferObject
{
    public string $name;
    public string $color;
    public ?string $coords;
    public ?Zone $zone;
    public ?Driver $driver;

    public static function fromArray(array $data): ZoneDTO
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
