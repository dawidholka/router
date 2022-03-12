<?php

namespace Router\Drivers\Domain\Actions;

use Router\Drivers\Contracts\DTOs\DriverDTO;
use Router\Drivers\Domain\Models\Driver;

class UpdateDriver
{
    public function execute(Driver $driver, DriverDTO $data): Driver
    {
        $driver->name = $data->name;
        $driver->login = $data->login;
        $driver->color = $data->color;
        $driver->lat = $data->lat;
        $driver->long =  $data->long;

        $driver->save();

        return $driver;
    }
}
