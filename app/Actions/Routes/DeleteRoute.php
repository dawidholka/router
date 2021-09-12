<?php

namespace App\Actions\Routes;

use App\Models\Route;

class DeleteRoute
{
    public function execute(Route $route)
    {
        $route->delete();
    }
}
