<?php

namespace App\Actions\Routes;

use App\Models\Route;
use Illuminate\Support\Carbon;

class UpdateRoute
{
    public function execute(Route $route, Carbon $date, ?int $driver_Id, ?string $note): Route
    {
        $route->delivery_time = $date;
        $route->driver_id = $driver_Id;
        $route->note = $note;
        $route->status = Route::STATUS_NO_WAYPOINTS;
        $route->optimize_status = Route::OPTIMIZE_NONE;

        $route->save();

        return $route;
    }
}
