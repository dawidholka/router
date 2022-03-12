<?php

namespace Router\Zones\Application\Http\Controllers;

use App\Actions\Zones\CreateZonesFromKMLFile;
use App\Actions\Zones\UpdateZone;
use App\DTOs\ZoneData;
use App\Http\Controllers\Controller;
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

    public function update(
        Zone       $zone,
        Request    $request,
        UpdateZone $updateZone
    )
    {
        abort_if(!auth()->user()->admin, 403);

        $request->validate([
            'name' => ['required', 'string'],
            'color' => ['required', 'string'],
            'driver' => ['nullable', 'array'],
            'driver.id' => ['nullable', 'integer', 'exists:drivers,id'],
        ]);

        $zoneData = ZoneData::fromArray($request->toArray());

        $updateZone->execute($zone, $zoneData);

        return redirect()->back();
    }

    public function destroy(Zone $zone): RedirectResponse
    {
        abort_if(!auth()->user()->admin, 403);

        $zone->delete();

        return redirect()->back();
    }

}
