<?php

namespace App\Support;

use App\DTOs\ZoneData;
use DOMDocument;
use DOMElement;
use Exception;
use Illuminate\Http\UploadedFile;

class KMLFileParser
{
    /**
     * @param UploadedFile $file
     * @return ZoneData[]
     * @throws Exception
     */
    public function getZoneDataFromKMLFile(UploadedFile $file): array
    {
        $doc = new DOMDocument();
        $doc->loadXML($file->getContent());

        $zonesData = [];

        /** @var DOMElement $placemark */
        foreach ($doc->getElementsByTagName('Placemark') as $placemark) {
            $name = $placemark->getElementsByTagName('name')?->item(0)?->nodeValue;
            $color = ColorDictionary::getRandomColor();
            $coords = [];
            /** @var DOMElement $polygon */
            foreach ($placemark->getElementsByTagName('Polygon') as $polygon) {
                if (($innerBoundaryIs = $polygon->getElementsByTagName('innerBoundaryIs'))->length) {
                    // TODO Not supported yet
                    throw new Exception('Polygons with inner boundary is not supported yet.');
                    $coords['inner'] = $this->getCoordinates($innerBoundaryIs->item(0));
                }
                if (($outerBoundaryIs = $polygon->getElementsByTagName('outerBoundaryIs'))->length) {
                    $coords['outer'] = $this->getCoordinates($outerBoundaryIs->item(0));
                }
            }

            $coordsArray = [];

            if (isset($coords['outer'])) {
                for ($i = 0; $i < count($coords['outer']); $i++) {
                    $coordsArray[] = [
                        'lat' => (float)$coords['outer'][$i][0],
                        'lng' => (float)$coords['outer'][$i][1],
                    ];
                }
            }


            $zonesData[] = new ZoneData([
                'name' => $name,
                'color' => $color,
                'coords' => json_encode($coordsArray)
            ]);
        }

        return $zonesData;
    }

    private function getCoordinates(DOMElement $element): array
    {
        $coords = $element->getElementsByTagName('coordinates')->item(0)->nodeValue;
        $coords = str_replace("\n", "", $coords);
        $coords = str_replace(",0", ",", $coords);
        $coords = trim($coords);
        $coords = explode(',', $coords);
        $coords = array_map('trim', $coords);
        $coords = array_filter($coords);
        $coordsArray = [];
        for ($i = 0; $i < count($coords); $i += 2) {
            $coordsArray[] = [$coords[$i + 1], $coords[$i]];
        }
        return $coordsArray;
    }
}
