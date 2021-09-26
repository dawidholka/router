<?php

namespace App\Actions\Routes;

use App\Models\Route;
use App\Support\DateHelper;
use App\Support\OptimizeDictionary;
use App\Support\RouteDictionary;
use Carbon\Traits\Date;
use Illuminate\Support\Carbon;

class CreateRoute
{
    public function execute(Carbon $date, ?int $driver_Id, ?string $note): Route
    {
        $route = new Route();

        $route->delivery_time = DateHelper::parse($date);
        $route->driver_id = $driver_Id;
        $route->note = $note;
        $route->status = RouteDictionary::NO_WAYPOINTS;
        $route->optimize_status = OptimizeDictionary::NONE;

        $route->save();

        return $route;
    }
}
