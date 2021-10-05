<?php

namespace App\DTOs;

use Spatie\DataTransferObject\DataTransferObject;

class DriverData extends DataTransferObject
{
    public string $name;
    public string $login;
    public ?string $password;
    public string $color;
    public ?float $lat;
    public ?float $long;
}
