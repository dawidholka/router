<?php

namespace App\Http\Controllers;

use App\Actions\Zones\CreateZone;
use App\Actions\Zones\UpdateZone;
use App\DTOs\ZoneData;
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
        $request->validate([
            'zones' => ['array']
        ]);

        //TODO Delete non existing zones
        $zones = $request['zones'];

        foreach ($zones as $zone) {
            $zoneData = ZoneData::fromArray($zone);
            if ($zoneData->zone) {
                $updateZone->execute($zoneData->zone, $zoneData);
            } else {
                $createZone->execute($zoneData);
            }
        }

        return redirect()->back();
    }
}
