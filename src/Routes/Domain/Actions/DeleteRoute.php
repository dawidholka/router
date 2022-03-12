<?php

namespace Router\Routes\Domain\Actions;

use Router\Routes\Domain\Models\Route;

class DeleteRoute
{
    public function execute(Route $route)
    {
        $route->delete();
    }
}
