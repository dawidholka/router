<?php

namespace Router\Points\Contracts\DTOs;

use Spatie\DataTransferObject\DataTransferObject;

class GeocodeDTO extends DataTransferObject
{
    public float $lat;
    public float $lng;
    public string $address;
}
