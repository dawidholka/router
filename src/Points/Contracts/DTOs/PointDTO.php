<?php

namespace Router\Points\Contracts\DTOs;

use Spatie\DataTransferObject\DataTransferObject;

class PointDTO extends DataTransferObject
{
    public string $name;
    public string $street;
    public string $building_number;
    public string $city;
    public ?string $postcode;
    public ?string $apartament;
    public ?string $intercom;
    public ?string $delivery_time;
    public ?string $phone;
    public ?string $note;
    public ?float $lat;
    public ?float $long;
    public bool $lock_geo = false;
    public ?array $rawData;
}
