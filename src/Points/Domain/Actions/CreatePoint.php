<?php

namespace Router\Points\Domain\Actions;

use Router\Points\Contracts\DTOs\PointDTO;
use Router\Points\Domain\Models\Point;

class CreatePoint
{
    public function execute(PointDTO $data): Point
    {
        return Point::create($data->toArray());
    }
}
