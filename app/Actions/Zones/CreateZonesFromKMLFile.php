<?php

namespace App\Actions\Zones;

use App\DTOs\ZoneData;
use App\Support\ColorDictionary;
use App\Support\KMLFileParser;
use Illuminate\Http\UploadedFile;

class CreateZonesFromKMLFile
{
    public function __construct(
        private KMLFileParser $KMLFileParser,
        private CreateZone    $createZone
    )
    {

    }

    public function execute(UploadedFile $file): array
    {
        $zonesData = $this->KMLFileParser->getZoneDataFromKMLFile($file);
        $zones = [];

        foreach($zonesData as $data){
            $zones[] = $this->createZone->execute($data);
        }

        return $zones;
    }

}
