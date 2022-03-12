<?php

namespace Router\Zones\Application\Http\ViewModels;

use App\Settings\GeneralSettings;
use Router\Drivers\Domain\Models\Driver;
use Router\Zones\Domain\Models\Zone;
use Spatie\ViewModels\ViewModel;

class ZoneEditorViewModel extends ViewModel
{
    public function zones(): array
    {
        $zones = Zone::with('driver')->get();
        return $zones->map(function (Zone $zone) {
            $coords = json_decode($zone->coords, true);
            $coordsArray = [];

            if (isset($coords['outer'])) {
                for ($i = 0; $i < count($coords['outer']); $i++) {
                    $coordsArray[] = [
                        'lat' => (float)$coords['outer'][$i][0],
                        'lng' => (float)$coords['outer'][$i][1],
                    ];
                }
            } else {
                $coordsArray = $coords;
            }

            return [
                'id' => $zone->id,
                'name' => $zone->name,
                'color' => $zone->color,
                'created_at' => $zone->created_at?->format('Y-m-d H:m:s'),
                'coords' => $coordsArray,
                'driver' => $zone->driver ? [
                    'id' => $zone->driver->id,
                    'name' => $zone->driver->name
                ] : null
            ];
        })->toArray();
    }

    public function mapCenter(GeneralSettings $generalSettings): array
    {
        return [
            'lat' => $generalSettings->company_lat,
            'lng' => $generalSettings->company_lng
        ];
    }

    public function colors(): array
    {
        return Driver::COLORS;
    }
}
