<?php

namespace Router\Optimization\Domain\Actions;

use Router\Optimization\Contracts\DTOs\OptimizeRouteDTO;

interface OptimizationAction
{
    public function execute(OptimizeRouteDTO $route): OptimizeRouteDTO;
}
