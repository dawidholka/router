<?php

namespace Router\Points\Contracts\DTOs;

use Router\Points\Domain\Models\Point;
use Spatie\DataTransferObject\DataTransferObject;

class ImportedPointDTO extends DataTransferObject
{
    public Point $point;
    public PointDTO $data;
}
