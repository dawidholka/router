<?php

namespace App\Http\Controllers;

use App\Actions\Routes\ImportFileToRoutesByZones;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CreatorController extends Controller
{
    public function show(): Response
    {
        return Inertia::render('Creator/Show');
    }

    public function store(Request $request, ImportFileToRoutesByZones $importFileToRoutesByZones)
    {
        $request->validate([
            'file' => ['required', 'file']
        ]);

        $routes = $importFileToRoutesByZones->execute($request['file']);

        dd($routes);

        return Inertia::render('Creator/Result');
    }
}
