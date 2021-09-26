<?php

namespace App\Http\Controllers;

use App\Models\Route;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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
