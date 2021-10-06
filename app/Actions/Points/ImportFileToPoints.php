<?php

namespace App\Actions\Points;

use App\DTOs\ImportedPointData;
use App\DTOs\PointData;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

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

    private function mapRowToPointData(array $row): PointData
    {
        $row = collect($row);

        return new PointData([
            'name' => $row[1],
            'street' => $row[2],
            'building_number' => (string)$row[3],
            'city' => $row[6],
            'rawData' => $row->toArray()
        ]);
    }

    private function getRowsCollection(UploadedFile $file): Collection
    {
        $path = $file->getRealPath();
        $reader = new Xlsx();
        $reader->setReadDataOnly(true);
        $reader->setReadEmptyCells(false);
        $spreadsheet = $reader->load($path);
        $worksheet = $spreadsheet->getActiveSheet();
        $import = collect($worksheet->toArray());
        return $import->slice(2);
    }
}
