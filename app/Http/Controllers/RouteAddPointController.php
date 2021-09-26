<?php

namespace App\Http\Controllers;

use App\Actions\Routes\CreateWaypoint;
use App\DTOs\WaypointData;
use App\Models\Point;
use App\Models\Route;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RouteAddPointController extends Controller
{
    public function __invoke(
        Route          $route,
        Request        $request,
        CreateWaypoint $createWaypoint
    ): JsonResponse
    {
        $request->validate([
            'point_id' => ['required', 'integer', 'exists:points,id']
        ]);

        $data = new WaypointData([
            'route' => $route,
            'point' => Point::whereId($request['point_id'])->first()
        ]);

        $createWaypoint->execute($data);

        return response()->json(['success' => true]);
    }
}
