<?php

namespace Router\Optimization\Domain\Actions;

use Illuminate\Support\Facades\App;
use Router\Optimization\Contracts\Dictionaries\OptimizeDictionary;
use Router\Optimization\Contracts\DTOs\OptimizeRouteDTO;

class Optimization implements OptimizationAction
{
    public function execute(OptimizeRouteDTO $route): OptimizeRouteDTO
    {
        $class = OptimizeDictionary::getOptimizeClass($route->method);

        /** @var OptimizationAction $actionClass */
        $actionClass = App::make($class);

        return $actionClass->execute($route);
    }
}
