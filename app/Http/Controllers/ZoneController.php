<?php

namespace App\Http\Controllers;

use App\Actions\Zones\CreateZonesFromKMLFile;
use App\Models\Zone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ZoneController extends Controller
{
    public function index(): Response
    {
        $zones = Zone::all();

        return Inertia::render('Zones/Index', compact('zones'));
    }

    public function store(Request $request, CreateZonesFromKMLFile $createZonesFromKMLFile): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file']
        ]);

        $createZonesFromKMLFile->execute($request->file);

        return redirect()->back();
    }

}
