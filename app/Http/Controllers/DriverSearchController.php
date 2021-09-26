<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;

class DriverSearchController extends Controller
{
    public function __invoke(Request $request)
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
