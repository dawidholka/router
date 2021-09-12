<?php

namespace App\DTOs;

use App\Models\Point;
use App\Models\Route;
use Spatie\DataTransferObject\DataTransferObject;

class WaypointData extends DataTransferObject
{
    public Route $route;
    public Point $point;
}
