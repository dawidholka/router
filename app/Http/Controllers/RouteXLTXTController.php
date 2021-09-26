<?php

namespace App\Http\Controllers;

use App\Actions\Routes\Optimize\RouteXLManualOptimize;
use App\Models\Route;
use Illuminate\Http\Request;

class RouteXLTXTController extends Controller
{
    public function __invoke(
        Route                 $route,
        Request               $request,
        RouteXLManualOptimize $routeXLManualOptimize
    )
    {
        abort_if(!auth()->user()->admin, 403);

        $request->validate([
            'last_location' => ['required', 'string', 'in:driver,last_waypoint,company']
        ]);

        $route->load([
            'waypoints' => function ($query) {
                $query->orderBy('stop_number');
            },
            'waypoints.point'
        ]);


        $content = view('routexl-txt', compact('route'));

        return response($content)->header('Content-Type', 'text/plain');
    }
}
