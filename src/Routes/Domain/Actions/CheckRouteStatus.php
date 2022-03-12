<?php

namespace Router\Routes\Domain\Actions;

use Router\Routes\Domain\Models\Route;
use App\Support\RouteDictionary;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CheckRouteStatus
{
    public function execute(Route $route)
    {
        $route->load([
            'waypoints',
            'waypoints.point' => function (BelongsTo $query) {
                $query->select(['id', 'lat', 'long']);
            }
        ]);

        if ($route->waypoints->count() == 0) {
            $route->status = RouteDictionary::NO_WAYPOINTS;
        } elseif (in_array(false, $route->waypoints->pluck('point')->pluck('geocoded')->toArray())) {
            $route->status = RouteDictionary::NO_GEOCODING;
        } else {
            $route->status = RouteDictionary::OK;
        }

        $route->save();
    }
}
