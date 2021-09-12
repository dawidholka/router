<?php

namespace App\DTOs;

use Spatie\DataTransferObject\DataTransferObject;

class ZoneData extends DataTransferObject
{
    public string $name;
    public string $color;
    public string $coords;
}
