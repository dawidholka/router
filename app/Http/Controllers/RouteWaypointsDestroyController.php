<?php

namespace App\Http\Controllers;

use App\Models\Route;
use App\Models\Waypoint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RouteWaypointsDestroyController extends Controller
{
    public function __invoke(Request $request, Route $route): RedirectResponse
    {
        abort_if(!auth()->user()->admin, 403);

        $request->validate([
            'waypoint_ids' => ['array', 'required'],
            'waypoint_ids.*' => ['integer', 'exists:waypoints,id']
        ]);

        $waypointIds = $request['waypoint_ids'];

        $waypoints = Waypoint::whereIn('id', $waypointIds)->get();

        foreach ($waypoints as $waypoint) {
            $waypoint->delete();
        }

        return redirect()->back();
    }
}
