<?php

namespace Router\Drivers\Contracts\DTOs;

use Spatie\DataTransferObject\DataTransferObject;

class DriverDTO extends DataTransferObject
{
    public string $name;
    public string $login;
    public ?string $password;
    public string $color;
    public ?float $lat;
    public ?float $long;
}
