<?php

namespace App\DTOs;

use Spatie\DataTransferObject\DataTransferObject;

class UserData extends DataTransferObject
{
    public string $name;
    public string $email;
    public ?string $password;
    public bool $admin = false;
}
