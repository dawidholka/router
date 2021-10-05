<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Route;
use App\Models\User;
use App\Models\Waypoint;
use App\Support\ColorDictionary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class RouteController extends Controller
{
    public function index(): JsonResponse
    {
        /** @var Driver $driver */
        $driver = Auth::user();

        $routes = $driver->routes()->limit(5)->latest()->get();

        $routes = $routes->map(function (Route $route) {
            return [
                'id' => $route->id,
                'date' => $route->delivery_time->format('Y-m-d'),
                'note' => $route->note,
            ];
        });

        return response()->json($routes);
    }

    public function show(Route $route): JsonResponse
    {
        /** @var Driver $driver */
        $driver = Auth::user();

        Log::info('Driver ' . $driver->id . 'downloading route');

        abort_if($route->driver_id !== $driver->id, 403);

        $route->load([
            'waypoints' => function ($query){
            $query->orderBy('stop_number');
            },
            'waypoints.point'
        ]);

        $route->update([
            'driver_downloaded_at' => now()
        ]);

        $waypoints = $route->waypoints->map(function (Waypoint $waypoint) {
            return [
                'id' => $waypoint->id,
                'stop_number' => $waypoint->stop_number,
                'name' => $waypoint->point->name,
                'address' => $waypoint->point->address,
                'city' => $waypoint->point->city,
                'lat' => $waypoint->point->lat,
                'lng' => $waypoint->point->long,
                'phone' => $waypoint->point->phone,
                'intercom' => $waypoint->point->intercom,
                'note' => $waypoint->point->note,
                'delivery_time' => $waypoint->point->delivery_time,
                'content' => $waypoint->content,
                'quantity' => $waypoint->quantity,
                'status' => $waypoint->status,
                'driver_note' => $waypoint->point->driver_note,
                'photo_uploaded' => (int)$waypoint->photo_uploaded
            ];
        })->toArray();

        return response()->json([
            'id' => $route->id,
            'note' => $route->note,
            'waypoints' => $waypoints
        ]);
    }
}
