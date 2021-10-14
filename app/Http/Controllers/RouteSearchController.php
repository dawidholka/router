<?php

namespace App\Http\Controllers;

use App\Models\Route;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RouteSearchController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'params' => ['nullable', 'string']
        ]);

        $routes = Route::where('id', 'LIKE', '%' . $request['params'] . '%')->limit(5)->get();

        return response()->json($routes->map(function (Route $route) {
            return [
                'id' => $route->id,
                'name' => 'Trasa #' . $route->id . ' ' . $route->delivery_time?->format('Y-m-d'),
            ];
        })->toArray());
    }
}
