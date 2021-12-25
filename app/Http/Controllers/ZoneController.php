<?php

namespace App\Http\Controllers;

use App\Actions\Zones\CreateZonesFromKMLFile;
use App\Models\Zone;
use App\ViewModels\ZoneEditorViewModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ZoneController extends Controller
{
    public function index(): Response
    {
        abort_if(!auth()->user()->admin, 403);

        return Inertia::render('Zones/Index', new ZoneEditorViewModel());
    }

    public function store(Request $request, CreateZonesFromKMLFile $createZonesFromKMLFile): RedirectResponse
    {
        abort_if(!auth()->user()->admin, 403);

        $request->validate([
            'file' => ['required', 'file', 'mimetypes:application/vnd.google-earth.kml+xml,application/vnd.google-earth.kmz,application/xml,text/xml']
        ]);

        $createZonesFromKMLFile->execute($request->file);

        return redirect()->back();
    }

    public function destroy(Zone $zone): RedirectResponse
    {
        abort_if(!auth()->user()->admin, 403);

        $zone->delete();

        return redirect()->back();
    }

}
