<?php

namespace Router\Routes\Application\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Router\Optimization\Contracts\Dictionaries\OptimizeDictionary;
use Router\Routes\Domain\Actions\UpdateWaypointsOrder;
use Router\Routes\Domain\Models\Route;

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
