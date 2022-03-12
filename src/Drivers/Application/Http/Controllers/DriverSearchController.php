<?php

namespace Router\Drivers\Application\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Router\Drivers\Domain\Models\Driver;

class DriverSearchController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'params' => ['nullable', 'string']
        ]);

        $drivers = Driver::where('name', 'LIKE', '%' . $request['params'] . '%')->limit(5)->get();

        return response()->json($drivers->map(function (Driver $driver) {
            return [
                'id' => $driver->id,
                'name' => $driver->name,
            ];
        })->toArray());
    }
}
