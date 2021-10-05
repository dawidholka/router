<?php

namespace App\Actions\Drivers;

use App\DTOs\DriverData;
use App\Models\Driver;

class UpdateDriver
{
    public function execute(Driver $driver, DriverData $data): Driver
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
