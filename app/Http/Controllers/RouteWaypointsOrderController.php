<?php

namespace App\Http\Controllers;

use App\Actions\Routes\UpdateWaypointsOrder;
use App\Models\Route;
use App\Support\OptimizeDictionary;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RouteWaypointsOrderController extends Controller
{
    public function __invoke(Request $request, Route $route, UpdateWaypointsOrder $updateWaypointsOrder): RedirectResponse
    {
        abort_if(!auth()->user()->admin, 403);

        $request->validate([
            'waypoint_ids' => ['array', 'required'],
            'waypoint_ids.*' => ['integer', 'exists:waypoints,id']
        ]);

        $waypointIds = $request['waypoint_ids'];

        $updateWaypointsOrder->execute($waypointIds);

        $route->update([
            'optimize_status' => OptimizeDictionary::MANUAL
        ]);

        return redirect()->back();
    }
}
