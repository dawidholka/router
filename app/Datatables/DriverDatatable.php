<?php

namespace App\Datatables;

use App\Models\Driver;

class DriverDatatable extends Datatable
{
    protected string $model = Driver::class;

    protected array $with = [];

    protected function map(): callable
    {
        return function (Driver $driver) {
            return [
                'id' => $driver->id,
                'name' => $driver->name,
            ];
        };
    }
}
