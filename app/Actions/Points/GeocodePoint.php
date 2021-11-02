<?php

namespace App\Actions\Points;

use App\Models\Point;
use App\Services\GoogleMaps;
use Illuminate\Http\RedirectResponse;

class GeocodePoint
{
    public function __construct(private GoogleMaps $googleMaps)
    {

    }

    public function execute(Point $point): Point
    {
        if ($point->lock_geo) {
            throw new \Exception('Point geocode locked.');
        }

        $geocodeData = $this->googleMaps->geocode($point->full_address);


        $point->update([
            'lat' => $geocodeData->lat,
            'long' => $geocodeData->lng
        ]);

        return $point;
    }
}
