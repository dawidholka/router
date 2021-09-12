<?php

namespace App\Http\Controllers;

use App\Actions\Routes\UpdateWaypointsOrder;
use App\Models\Route;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RouteWaypointsOrderController extends Controller
{
    public function __invoke(Request $request, Route $route, UpdateWaypointsOrder $updateWaypointsOrder): RedirectResponse
    {
        $request->validate([
            'waypoint_ids' => ['array', 'required'],
            'waypoint_ids.*' => ['integer', 'exists:waypoints,id']
        ]);

        $waypointIds = $request['waypoint_ids'];

        $updateWaypointsOrder->execute($waypointIds);

        return redirect()->back();
    }
}
