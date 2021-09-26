<?php

namespace App\Actions\Routes;

use App\Actions\Points\ImportFileToPoints;
use App\DTOs\ImportedPointData;
use App\DTOs\PointData;
use App\DTOs\WaypointData;
use App\Models\Point;
use App\Models\Route;
use Illuminate\Http\UploadedFile;

class ImportFileToRoute
{
    public function __construct(
        private ImportFileToPoints $importFileToPoints,
        private CreateWaypoint     $createWaypoint
    )
    {
    }

    public function execute(
        Route        $route,
        UploadedFile $file,
    )
    {
        $importedPoints = $this->importFileToPoints->execute($file);

        foreach ($importedPoints as $importedPoint) {
            $waypointData = $this->mapRowToWaypointData(
                $route,
                $importedPoint->point,
                $importedPoint->data
            );
            $this->createWaypoint->execute($waypointData);
        }
    }

    private function mapRowToWaypointData(Route $route, Point $point, PointData $data): WaypointData
    {
        //TODO Custom mappings from settings

        return new WaypointData([
            'route' => $route,
            'point' => $point,
            'quantity' => (string)$data->rawData[17],
            'content' => $data->rawData[12] . ' ' . $data->rawData[14],
            'rawData' => $data->rawData,
        ]);
    }
}
