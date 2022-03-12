<?php

namespace Router\Points\Application\Datatables;

use App\Datatables\Datatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Router\Points\Domain\Models\Point;

class PointDatatable extends Datatable
{
    protected string $model = Point::class;

    protected bool $globalFilter = true;

    protected array $with = [];

    protected function map(): callable
    {
        return function (Point $point) {
            return [
                'id' => $point->id,
                'name' => $point->name,
                'address' => $point->address,
                'geocoded' => $point->geocoded
            ];
        };
    }

    protected function globalFilter(Request $request): callable
    {
        $filters = json_decode($request['filters'], true);

        $filterValue = $filters['global']['value'];

        return function (Builder $query) use ($filterValue) {
            if ($filterValue) {
                $query->where('name', 'LIKE', '%' . $filterValue . '%')
                    ->orWhere('street', 'LIKE', '%' . $filterValue . '%')
                    ->orWhere('postcode', 'LIKE', '%' . $filterValue . '%')
                    ->orWhere('city', 'LIKE', '%' . $filterValue . '%');
            }
        };
    }
}
