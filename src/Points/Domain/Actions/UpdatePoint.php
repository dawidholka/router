<?php

namespace Router\Points\Domain\Actions;

use Router\Points\Contracts\DTOs\PointDTO;
use Router\Points\Domain\Models\Point;

class UpdatePoint
{
    public function execute(Point $point, PointDTO $pointData): Point
    {
        $point->update($pointData->toArray());

        return $point;
    }

}
