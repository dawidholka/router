<?php

namespace Router\Routes\Domain\Actions;

use App\Support\DateHelper;
use App\Support\OptimizeDictionary;
use App\Support\RouteDictionary;
use Illuminate\Support\Carbon;
use Router\Routes\Domain\Models\Route;

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
