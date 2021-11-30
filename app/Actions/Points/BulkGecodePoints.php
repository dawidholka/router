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

    /**
     * @throws \Exception
     */
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
                        throw new \Exception('Geocode error');
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
