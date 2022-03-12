<?php

namespace Router\Drivers\Application\Datatables;

use App\Datatables\Datatable;
use Router\Drivers\Domain\Models\Driver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

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
