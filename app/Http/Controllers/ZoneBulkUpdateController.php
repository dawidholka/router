<?php

namespace App\Http\Controllers;

use App\Actions\Zones\CreateZone;
use App\Actions\Zones\UpdateZone;
use App\DTOs\ZoneData;
use App\Models\Zone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ZoneBulkUpdateController extends Controller
{
    public function __invoke(
        Request    $request,
        UpdateZone $updateZone,
        CreateZone $createZone
    ): RedirectResponse
    {
        abort_if(!auth()->user()->admin, 403);

        $request->validate([
            'zones' => ['array']
        ]);

        $zonesDB = Zone::all();

        $zones = $request['zones'];

        foreach ($zones as $zone) {
            $zoneData = ZoneData::fromArray($zone);
            if ($zoneData->zone) {
                $zonesDB = $zonesDB->except($zoneData->zone->id);
                $updateZone->execute($zoneData->zone, $zoneData);
            } else {
                $createZone->execute($zoneData);
            }
        }

        foreach($zonesDB as $zone){
            $zone->delete();
        }

        return redirect()->back();
    }
}
