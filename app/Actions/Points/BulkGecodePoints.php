<?php

namespace App\Actions\Points;

use App\Models\Point;
use App\Services\GoogleMaps;
use Illuminate\Support\Collection;

class BulkGecodePoints
{
    public function __construct(
        private GoogleMaps $googleMaps
    )
    {
    }

    public function execute(Collection $points)
    {
        $points = $points->reject(function (Point $point) {
            return $point->lock_geo;
        });

        $chunks = $points->chunk(5);

        /** @var Collection $chunk */
        foreach ($chunks as $chunk) {
            $geocodeDataArray = $this->googleMaps->bulkGeocode($chunk);
            $chunk = $chunk->values(); //reset keys
            foreach ($geocodeDataArray as $key => $geocodeData) {
                if($geocodeData !== null){
                    /** @var Point $point */
                    $point = $chunk->get($key);
                    if($point === null){
                        dd($key, $chunk);
                    }
                    $point->update([
                        'lat' => $geocodeData->lat,
                        'long' => $geocodeData->lng
                    ]);
                }
            }
        }
    }
}
