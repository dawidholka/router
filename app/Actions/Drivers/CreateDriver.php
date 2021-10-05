<?php

namespace App\Actions\Drivers;

use App\DTOs\DriverData;
use App\Models\Driver;
use Illuminate\Support\Facades\Hash;

class CreateDriver
{
    public function execute(DriverData $data): Driver
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
