<?php

namespace Router\Points\Domain\Actions;

use Router\Points\Domain\Models\Point;
use Illuminate\Support\Collection;
use Router\Points\Infrastructure\Services\GoogleMaps;

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
            return $point->geocoded;
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
