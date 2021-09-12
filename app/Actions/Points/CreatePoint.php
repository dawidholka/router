<?php

namespace App\Actions\Points;

use App\DTOs\PointData;
use App\Models\Point;

class CreatePoint
{
    public function execute(PointData $data): Point
    {
        return Point::create($data->toArray());
    }
}
