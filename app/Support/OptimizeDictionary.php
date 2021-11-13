<?php

namespace App\Support;

use App\Actions\Routes\Optimize\OpenRouteServiceOptimize;
use App\Actions\Routes\Optimize\OSRMOptimize;
use App\Actions\Routes\Optimize\RouteXLManualOptimize;
use App\Actions\Routes\Optimize\RouteXLOptimize;

class OptimizeDictionary
{
    const ROUTEXL = 'routexl';
    const ROUTEXL_MANUAL = 'routexl_manual';
    const OPEN_ROUTE_SERVICE = 'open_route_service';
    const OSRM = 'osrm';
    const MANUAL = 'manual';
    const NONE = 'none';

    const validationRules = [
        self::ROUTEXL => [
            'last_location' => ['required', 'string', 'in:driver,last_waypoint,company']
        ],
        self::ROUTEXL_MANUAL => [
            'file' => ['required', 'file', 'mimes:csv']
        ],
        self::OPEN_ROUTE_SERVICE => [

        ],
        self::OSRM => [

        ],
    ];

    public static function getOptimizeClass(string $method): string
    {
        return match ($method) {
            self::ROUTEXL => RouteXLOptimize::class,
            self::ROUTEXL_MANUAL => RouteXLManualOptimize::class,
            self::OPEN_ROUTE_SERVICE => OpenRouteServiceOptimize::class,
            self::OSRM => OSRMOptimize::class,
        };
    }

    public static function toArray(): array
    {
        return [
            self::ROUTEXL,
            self::ROUTEXL_MANUAL,
            self::OPEN_ROUTE_SERVICE,
            self::OSRM
        ];
    }
}
