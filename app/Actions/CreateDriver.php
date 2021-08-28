<?php

namespace App\Actions;

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

        $driver->save();

        return $driver;
    }
}
