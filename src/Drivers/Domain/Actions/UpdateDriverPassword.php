<?php

namespace Router\Drivers\Domain\Actions;

use Router\Drivers\Contracts\DTOs\DriverDTO;
use Router\Drivers\Domain\Models\Driver;
use Illuminate\Support\Facades\Hash;

class UpdateDriverPassword
{
    public function execute(Driver $driver, DriverDTO $data): Driver
    {
        $driver->password = Hash::make($data->password);

        $driver->save();

        return $driver;
    }
}
