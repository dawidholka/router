<?php

namespace App\Actions\Points;

use App\DTOs\ImportedPointData;
use App\DTOs\PointData;
use App\Imports\PointsImport;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

class ImportFileToPoints
{
    public function __construct(
        private FirstOrCreatePoint $firstOrCreatePoint,
    )
    {
    }

    /**
     * @param UploadedFile $file
     * @return ImportedPointData[]
     */
    public function execute(
        UploadedFile $file
    ): array
    {
        $rows = $this->getRowsCollection($file);
        $importedPoints = [];

        foreach ($rows as $row) {
            $pointData = $this->mapRowToPointData($row);
            $point = $this->firstOrCreatePoint->execute($pointData);

            $importedPoints[] = new ImportedPointData([
                'point' => $point,
                'data' => $pointData
            ]);
        }

        return $importedPoints;
    }

    private function mapRowToPointData(Collection $row): PointData
    {
        return new PointData([
            'name' => $row[1],
            'street' => $row[2],
            'building_number' => (string)$row[3],
            'city' => $row[6],
        ]);
    }

    private function getRowsCollection(UploadedFile $file): Collection
    {
        $path = $file->getRealPath();
        $import = Excel::toCollection(new PointsImport, $path, null, \Maatwebsite\Excel\Excel::XLSX);
        /** @var Collection $collection */
        return $import[0];
    }
}
