<?php

namespace App\Http\Controllers;

use App\Actions\Routes\ImportFileToRoutesByZones;
use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class CreatorController extends Controller
{
    public function show(): Response
    {
        abort_if(!auth()->user()->admin, 403);

        return Inertia::render('Creator/Show');
    }

    public function store(Request $request, ImportFileToRoutesByZones $importFileToRoutesByZones): Response
    {
        abort_if(!auth()->user()->admin, 403);

        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx'],
            'date' => ['required', 'date'],
        ]);

        $date = Carbon::parse($request['date']);

        $routes = $importFileToRoutesByZones->execute($request['file'], $date);

        $routes = collect($routes)->map(function (Route $route) {
            return [
                'id' => $route->id,
                'note' => $route->note
            ];
        });

        return Inertia::render('Creator/Result', compact('routes'));
    }
}
