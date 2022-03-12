<?php

namespace Router\Routes\Application\Datatables;

use App\Datatables\Datatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Router\Routes\Domain\Models\Route;

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

    protected function sortAndOrder(Request $request): Builder
    {
        if (!$request->has('sortField') || !$request->has('sortOrder')) {
            return $this->builder->orderBy('id', 'desc');
        }

        $direction = ($request['sortOrder'] == 1) ? 'asc' : 'desc';

        return $this->builder->orderBy($request['sortField'], $direction);
    }
}
