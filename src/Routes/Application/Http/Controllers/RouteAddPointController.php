<?php

namespace Router\Routes\Application\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Router\Points\Domain\Models\Point;
use Router\Routes\Contracts\DTOs\WaypointDTO;
use Router\Routes\Domain\Actions\CreateWaypoint;
use Router\Routes\Domain\Models\Route;

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

        $data = new WaypointDTO([
            'route' => $route,
            'point' => Point::whereId($request['point_id'])->first()
        ]);

        $createWaypoint->execute($data);

        return response()->json(['success' => true]);
    }
}
