<?php

namespace App\Actions\Routes;

use App\Actions\Routes\Optimize\RouteOptimizeMethod;
use App\DTOs\RouteOptimizeDTO;
use App\Models\Route;
use App\Support\OptimizeDictionary;
use Illuminate\Support\Facades\App;

class OptimizeRoute
{
    public function execute(Route $route, RouteOptimizeDTO $data): Route
    {
        $class = OptimizeDictionary::getOptimizeClass($data->method);

        /** @var RouteOptimizeMethod $actionClass */
        $actionClass = App::make($class);

        return $actionClass->execute($route, $data);
    }

}
