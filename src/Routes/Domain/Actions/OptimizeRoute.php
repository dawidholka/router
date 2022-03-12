<?php

namespace Router\Routes\Domain\Actions;

use Illuminate\Support\Facades\App;
use Router\Optimization\Contracts\Dictionaries\OptimizeDictionary;
use Router\Optimization\Contracts\DTOs\OptimizeRouteDTO;
use Router\Optimization\Domain\Actions\OptimizationAction;
use Router\Routes\Contracts\DTOs\RouteOptimizeDTO;
use Router\Routes\Domain\Models\Route;

class OptimizeRoute
{
    public function execute(Route $route, RouteOptimizeDTO $data): Route
    {
        $class = OptimizeDictionary::getOptimizeClass($data->method);

        /** @var OptimizationAction $actionClass */
        $actionClass = App::make($class);

        $data = OptimizeRouteDTO::fromRoute($route);

        $optimizedRoute = $actionClass->execute($data);

        return $route;
    }

}
