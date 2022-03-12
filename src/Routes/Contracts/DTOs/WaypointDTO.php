<?php

namespace Router\Routes\Contracts\DTOs;

use Router\Points\Domain\Models\Point;
use Router\Routes\Domain\Models\Route;
use Spatie\DataTransferObject\DataTransferObject;

class WaypointDTO extends DataTransferObject
{
    public Route $route;
    public Point $point;
    public ?string $content;
    public ?string $quantity;
    public ?array $rawData;
    public ?int $stopNumber;
}
