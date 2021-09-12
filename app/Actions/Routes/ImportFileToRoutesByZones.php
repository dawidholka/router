<?php

namespace App\Actions\Routes;

use App\Actions\Points\GeocodePoint;
use App\Actions\Points\ImportFileToPoints;
use App\Actions\Zones\GetZoneByCoords;
use App\DTOs\ImportedPointData;
use App\DTOs\PointData;
use App\DTOs\WaypointData;
use App\Models\Point;
use App\Models\Route;
use Illuminate\Http\UploadedFile;

class ImportFileToRoutesByZones
{
    public function __construct(
        private ImportFileToPoints $importFileToPoints,
        private GeocodePoint       $geocodePoint,
        private GetZoneByCoords    $getZoneByCoords,
        private CreateRoute        $createRoute,
        private CreateWaypoint     $createWaypoint
    )
    {
    }


    /**
     * @param UploadedFile $file
     * @return Route[]
     * @throws \Exception
     */
    public function execute(
        UploadedFile $file
    ): array
    {
        $importedPoints = $this->importFileToPoints->execute($file);
        $pointsByZones = [];

        foreach ($importedPoints as $importedPoint) {
            $point = $importedPoint->point;
            if (!$point->geocoded) {
                $point = $this->geocodePoint->execute($importedPoint->point);
            }

            if ($zone = $this->getZoneByCoords->execute($point->lat, $point->long)) {
                $pointsByZones[$zone->id][] = $importedPoint;
                continue;
            }

            $pointsByZones[0][] = $importedPoint;
        }

        $routes = [];

        foreach ($pointsByZones as $importedPoints) {
            $route = $this->createRoute->execute(now(), null, null);

            /** @var ImportedPointData $importedPoint */
            foreach ($importedPoints as $importedPoint) {
                $waypointData = $this->mapRowToWaypointData($route, $importedPoint->point, $importedPoint->data);
                $this->createWaypoint->execute($waypointData);
            }
            $routes[] = $route;
        }

        return $routes;
    }

    private function mapRowToWaypointData(Route $route, Point $point, PointData $data): WaypointData
    {
        return new WaypointData([
            'route' => $route,
            'point' => $point,
        ]);
    }

}
