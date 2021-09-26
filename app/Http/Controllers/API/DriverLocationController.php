<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\DriverLocation;
use App\Models\Route;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DriverLocationController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'lat' => ['required'],
            'lng' => ['required'],
            'speed' => ['required']
        ]);

        $driver = Driver::where('id', auth()->id())->first();
        $route = Route::where('id', $driver->route_id)->first();

        if(!$route){
            return response()->json(['success' => false]);
        }

        $driverLocation = new DriverLocation();
        $driverLocation->driver_id = auth()->id();
        $driverLocation->route_id = $route->id;
        $driverLocation->lat = $request['lat'];
        $driverLocation->lng = $request['lng'];
        $driverLocation->speed = $request['speed'];
        $driverLocation->save();

        return response()->json(['success' => true]);
    }
}
