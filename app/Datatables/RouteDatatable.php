<?php

namespace App\Datatables;

use App\Models\Route;

class RouteDatatable extends Datatable
{
    protected string $model = Route::class;

    protected array $with = ['driver'];

    protected function map(): callable
    {
        return function (Route $route) {
            return [
                'id' => $route->id,
                'date' => $route->delivery_time?->format('Y-m-d'),
                'driver' => $route->driver?->name,
                'note' => $route->note,
            ];
        };
    }
}
