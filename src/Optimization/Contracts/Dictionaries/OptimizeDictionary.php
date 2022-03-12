<?php

namespace Router\Optimization\Contracts\Dictionaries;

use Router\Optimization\Domain\Actions\OpenRouteOptimization;
use Router\Optimization\Domain\Actions\OSRMOptimization;
use Router\Optimization\Domain\Actions\RouteXLOptimization;

class OptimizeDictionary
{
    const ROUTEXL = 'routexl';
    const OPEN_ROUTE_SERVICE = 'open_route_service';
    const OSRM = 'OSRM';
    const MANUAL = 'manual';
    const NONE = 'none';

    const validationRules = [
        self::ROUTEXL => [
            'last_location' => ['required', 'string', 'in:driver,last_waypoint,company']
        ],
        self::OPEN_ROUTE_SERVICE => [

        ],
        self::OSRM => [

        ],
    ];

    public static function getOptimizeClass(string $method): string
    {
        return match ($method) {
            self::ROUTEXL => RouteXLOptimization::class,
            self::OPEN_ROUTE_SERVICE => OpenRouteOptimization::class,
            self::OSRM => OSRMOptimization::class,
        };
    }

    public static function toArray(): array
    {
        return [
            self::ROUTEXL,
            self::OPEN_ROUTE_SERVICE,
            self::OSRM
        ];
    }
}
