<?php

namespace Router\Zones\Application\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Router\Zones\Contracts\DTOs\ZoneDTO;
use Router\Zones\Domain\Actions\CreateZone;
use Router\Zones\Domain\Actions\UpdateZone;
use Router\Zones\Domain\Models\Zone;

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
            $zoneData = ZoneDTO::fromArray($zone);
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
