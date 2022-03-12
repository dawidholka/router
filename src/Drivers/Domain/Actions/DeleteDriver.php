<?php

namespace Router\Drivers\Domain\Actions;

use Router\Drivers\Domain\Models\Driver;

class DeleteDriver
{
    public function execute(Driver $driver): ?bool
    {
        return $driver->delete();
    }
}
