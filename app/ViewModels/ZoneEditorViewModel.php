<?php

namespace App\ViewModels;

use App\Models\Zone;
use App\Settings\GeneralSettings;
use Spatie\ViewModels\ViewModel;

class ZoneEditorViewModel extends ViewModel
{
    public function zones(): array
    {
        $zones = Zone::all();
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
}
