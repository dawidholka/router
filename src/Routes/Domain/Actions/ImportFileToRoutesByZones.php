<?php

namespace Router\Routes\Domain\Actions;

use App\DTOs\ImportedPointData;
use App\Settings\ImportSettings;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Router\Points\Contracts\DTOs\PointDTO;
use Router\Points\Domain\Actions\GeocodePoint;
use Router\Points\Domain\Actions\ImportFileToPoints;
use Router\Points\Domain\Models\Point;
use Router\Routes\Contracts\DTOs\WaypointDTO;
use Router\Routes\Domain\Models\Route;
use Router\Zones\Domain\Actions\GetZoneByCoords;
use Router\Zones\Domain\Models\Zone;

class ImportFileToRoutesByZones
{
    public function __construct(
        private ImportFileToPoints $importFileToPoints,
        private GeocodePoint       $geocodePoint,
        private GetZoneByCoords    $getZoneByCoords,
        private CreateRoute        $createRoute,
        private ImportSettings     $importSettings,
        private CreateBulkWaypoint $createBulkWaypoint
    )
    {
    }


    /**
     * @param UploadedFile $file
     * @param Carbon $date
     * @return Route[]
     * @throws Exception
     */
    public function execute(
        UploadedFile $file,
        Carbon       $date
    ): array
    {
        $importedPoints = $this->importFileToPoints->execute($file);
        $pointsByZones = [];

        foreach ($importedPoints as $importedPoint) {
            $point = $importedPoint->point;
            //TODO Bulk geocoding
            if (!$point->geocoded) {
                $point = $this->geocodePoint->execute($importedPoint->point);
            }

            if ($zone = $this->getZoneByCoords->execute($point->lat, $point->long)) {
                $pointsByZones[$zone->id][] = $importedPoint;
                continue;
            }

            $pointsByZones[0][] = $importedPoint;
        }

        /** @var Collection $zones */
        $zones = Cache::rememberForever('zones', function () {
            return Zone::all();
        });
        $zones = $zones->keyBy('id');


        $routes = DB::transaction(function () use ($zones, $date, $pointsByZones) {
            $routes = [];

            foreach ($pointsByZones as $zoneId => $importedPoints) {

                $zone = $zones[$zoneId] ?? null;

                $name = ($zone ? $zone->name : 'Poza strefami');

                $route = $this->createRoute->execute($date, null, $name);

                $waypointDataBulk = collect();
                /** @var ImportedPointData $importedPoint */
                $i = 0;
                foreach ($importedPoints as $importedPoint) {
                    $waypointData = $this->mapRowToWaypointData($route, $importedPoint->point, $importedPoint->data);
                    $waypointData->stopNumber = ++$i;
                    $waypointDataBulk->push($waypointData);
                }

                $this->createBulkWaypoint->execute($waypointDataBulk);

                $routes[] = $route;
            }

            return $routes;
        });


        return $routes;
    }

    private function mapRowToWaypointData(Route $route, Point $point, PointDTO $data): WaypointDTO
    {
        return new WaypointDTO([
            'route' => $route,
            'point' => $point,
            'quantity' => $this->importSettings->getColumnData('waypoint_quantity', $data->rawData),
            'content' => $this->importSettings->getColumnData('waypoint_content', $data->rawData),
            'rawData' => $data->rawData,
        ]);
    }

}
