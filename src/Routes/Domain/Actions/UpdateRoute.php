<?php

namespace Router\Routes\Domain\Actions;

use App\Support\DateHelper;
use Illuminate\Support\Carbon;
use Router\Routes\Domain\Models\Route;

class UpdateRoute
{
    public function execute(Route $route, Carbon $date, ?int $driver_Id, ?string $note): Route
    {
        $route->delivery_time = DateHelper::parse($date);
        $route->driver_id = $driver_Id;
        $route->note = $note;

        $route->save();

        return $route;
    }
}
