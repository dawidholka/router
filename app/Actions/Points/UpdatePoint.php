<?php

namespace App\Actions\Points;

use App\DTOs\PointData;
use App\Models\Point;

class UpdatePoint
{
    public function execute(Point $point, PointData $pointData): Point
    {
        $point->update($pointData->toArray());

        return $point;
    }

}
