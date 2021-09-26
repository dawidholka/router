<?php

namespace App\Actions\Routes\Optimize;

use App\DTOs\RouteOptimizeDTO;
use App\Models\Route;

interface RouteOptimizeMethod
{
    public function execute(Route $route, RouteOptimizeDTO $data): Route;
}
