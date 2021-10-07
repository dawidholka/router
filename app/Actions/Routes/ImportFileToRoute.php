<?php

namespace App\Actions\Routes;

use App\Actions\Points\ImportFileToPoints;
use App\DTOs\ImportedPointData;
use App\DTOs\PointData;
use App\DTOs\WaypointData;
use App\Models\Point;
use App\Models\Route;
use App\Settings\ImportSettings;
use Illuminate\Http\UploadedFile;

class ImportFileToRoute
{
    public function __construct(
        private ImportFileToPoints $importFileToPoints,
        private CreateBulkWaypoint $createWaypoint,
        private ImportSettings     $importSettings,
    )
    {
    }

    public function execute(
        Route        $route,
        UploadedFile $file,
    )
    {
        $importedPoints = $this->importFileToPoints->execute($file);

        $waypointsData = collect();

        $stops = $route->waypoints()->count();

        foreach ($importedPoints as $importedPoint) {
            $waypointsData->push($this->mapRowToWaypointData(
                $route,
                $importedPoint->point,
                $importedPoint->data,
                ++$stops
            ));
        }

        $this->createWaypoint->execute($waypointsData);
    }

    private function mapRowToWaypointData(Route $route, Point $point, PointData $data, int $stopNumber): WaypointData
    {
        return new WaypointData([
            'route' => $route,
            'point' => $point,
            'quantity' => $this->importSettings->getColumnData('waypoint_quantity', $data->rawData),
            'content' => $this->importSettings->getColumnData('waypoint_content', $data->rawData),
            'rawData' => $data->rawData,
            'stopNumber' => $stopNumber
        ]);
    }
}
