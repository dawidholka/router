<?php

namespace Router\Routes\Application\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Router\Routes\Domain\Models\Route;

class RouteSetCurrentController extends Controller
{
    public function __invoke(Request $request, Route $route): RedirectResponse
    {
        $driver = $route->driver;

        $driver->update([
            'route_id' => $route->id
        ]);

        return redirect()->back();
    }
}
