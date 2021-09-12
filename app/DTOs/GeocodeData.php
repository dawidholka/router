<?php

namespace App\DTOs;

use Spatie\DataTransferObject\DataTransferObject;

class GeocodeData extends DataTransferObject
{
    public float $lat;
    public float $lng;
    public string $address;
}
