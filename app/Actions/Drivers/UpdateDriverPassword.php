<?php

namespace App\Actions\Drivers;

use App\DTOs\DriverData;
use App\Models\Driver;
use Illuminate\Support\Facades\Hash;

class UpdateDriverPassword
{
    public function execute(Driver $driver, DriverData $data): Driver
    {
        $driver->password = Hash::make($data->password);

        $driver->save();

        return $driver;
    }
}
