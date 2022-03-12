<?php

namespace Router\Drivers\Domain\Actions;

use Illuminate\Support\Facades\Hash;
use Router\Drivers\Contracts\DTOs\DriverDTO;
use Router\Drivers\Domain\Models\Driver;

class CreateDriver
{
    public function execute(DriverDTO $data): Driver
    {
        $driver = new Driver();

        $driver->login = $data->login;
        $driver->name = $data->name;
        $driver->password = Hash::make($data->password);
        $driver->color = $data->color;
        $driver->lat = $data->lat;
        $driver->long = $data->long;

        $driver->save();

        return $driver;
    }
}
