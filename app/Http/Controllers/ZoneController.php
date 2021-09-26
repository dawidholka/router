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
        abort_if(!auth()->user()->admin, 403);
        $zones = Zone::all();

        $zones = $zones->map(function (Zone $zone) {
            return [
                'id' => $zone->id,
                'name' => $zone->name,
                'color' => $zone->color,
                'created_at' => $zone->created_at?->format('Y-m-d H:m:s'),
                'coords' => $zone->coords,
            ];
        })->toArray();

        return Inertia::render('Zones/Index', [
            'zones' => $zones
        ]);
    }

    public function store(Request $request, CreateZonesFromKMLFile $createZonesFromKMLFile): RedirectResponse
    {
        abort_if(!auth()->user()->admin, 403);

        $request->validate([
            'file' => ['required', 'file']
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
