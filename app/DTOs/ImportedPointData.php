<?php

namespace App\DTOs;

use App\Models\Point;
use Spatie\DataTransferObject\DataTransferObject;

class ImportedPointData extends DataTransferObject
{
    public Point $point;
    public PointData $data;
}
