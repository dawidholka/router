<?php

namespace Router\Routes\Domain\Actions;

use App\Settings\ImportSettings;
use Illuminate\Http\UploadedFile;
use Router\Points\Contracts\DTOs\PointDTO;
use Router\Points\Domain\Actions\ImportFileToPoints;
use Router\Points\Domain\Models\Point;
use Router\Routes\Contracts\DTOs\WaypointDTO;
use Router\Routes\Domain\Models\Route;

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

    private function mapRowToWaypointData(Route $route, Point $point, PointDTO $data, int $stopNumber): WaypointDTO
    {
        return new WaypointDTO([
            'route' => $route,
            'point' => $point,
            'quantity' => $this->importSettings->getColumnData('waypoint_quantity', $data->rawData),
            'content' => $this->importSettings->getColumnData('waypoint_content', $data->rawData),
            'rawData' => $data->rawData,
            'stopNumber' => $stopNumber
        ]);
    }
}
