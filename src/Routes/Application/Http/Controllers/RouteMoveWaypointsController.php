<?php

namespace Router\Routes\Application\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Router\Routes\Domain\Models\Route;
use Router\Routes\Domain\Models\Waypoint;

class RouteMoveWaypointsController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $request->validate([
            'route' => ['required', 'array'],
            'route.id' => ['required', 'integer', 'exists:routes,id'],
            'waypoints' => ['required', 'array'],
            'waypoints.*.id' => ['required', 'integer', 'exists:waypoints,id']
        ]);

        $waypointsIds = collect($request['waypoints'])->pluck('id');

        $route = Route::where('id', $request['route']['id'])->first();
        $waypoints = Waypoint::whereIn('id', $waypointsIds)->get();

        /** @var Waypoint $waypoint */
        foreach($waypoints as $waypoint){
            $waypoint->update([
                'route_id' => $route->id
            ]);
        }

        return redirect()->back();
    }
}
