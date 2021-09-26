<?php

namespace App\Actions\Routes;

use App\Models\Route;
use App\Support\DateHelper;
use Illuminate\Support\Carbon;

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
