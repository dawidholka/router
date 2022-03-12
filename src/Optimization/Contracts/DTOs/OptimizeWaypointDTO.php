<?php

namespace Router\Optimization\Contracts\DTOs;

use Spatie\DataTransferObject\DataTransferObject;

class OptimizeWaypointDTO extends DataTransferObject
{
    public int $id;
    public float $lng;
    public float $lat;
    public ?int $stopNumber;
}
