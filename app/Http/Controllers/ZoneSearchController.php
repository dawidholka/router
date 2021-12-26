<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ZoneSearchController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'params' => ['nullable', 'string']
        ]);

        $query = $request['params'];

        $zones = Zone::where('id', 'LIKE', '%' . $query . '%')
            ->orWhere('name', 'LIKE', '%' . $query . '%')
            ->limit(5)
            ->get();

        return response()->json($zones->map(function (Zone $zone) {
            return [
                'id' => $zone->id,
                'name' => $zone->name,
            ];
        })->toArray());
    }
}
