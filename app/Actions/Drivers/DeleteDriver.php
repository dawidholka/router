<?php

namespace App\Actions\Drivers;

use App\Models\Driver;

class DeleteDriver
{
    public function execute(Driver $driver): ?bool
    {
        return $driver->delete();
    }
}
