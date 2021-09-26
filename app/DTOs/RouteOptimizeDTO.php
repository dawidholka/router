<?php

namespace App\DTOs;

use Illuminate\Http\UploadedFile;
use Spatie\DataTransferObject\DataTransferObject;

class RouteOptimizeDTO extends DataTransferObject
{
    public string $method;
    public ?UploadedFile $file;
}
