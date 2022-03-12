<?php

namespace Router\Points\Domain\Actions;

use App\Settings\ImportSettings;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Router\Points\Contracts\DTOs\ImportedPointDTO;
use Router\Points\Contracts\DTOs\PointDTO;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class ImportFileToPoints
{
    public function __construct(
        private FirstOrCreatePoint $firstOrCreatePoint,
        private ImportSettings     $importSettings,
    )
    {
    }

    /**
     * @param UploadedFile $file
     * @return ImportedPointDTO[]
     * @throws UnknownProperties
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

            $importedPoints[] = new ImportedPointDTO([
                'point' => $point,
                'data' => $pointData
            ]);
        }

        return $importedPoints;
    }

    private function mapRowToPointData(array $row): PointDTO
    {
        return new PointDTO([
            'name' => $this->importSettings->getColumnData('point_name', $row),
            'street' => $this->importSettings->getColumnData('point_street', $row),
            'building_number' => (string)$this->importSettings->getColumnData('point_building_number', $row),
            'apartament' => (string)$this->importSettings->getColumnData('point_apartment', $row),
            'city' => $this->importSettings->getColumnData('point_city', $row),
            'postcode' => $this->importSettings->getColumnData('point_postcode', $row),
            'intercom' => $this->importSettings->getColumnData('point_intercom', $row),
            'phone' => $this->importSettings->getColumnData('point_phone', $row),
            'note' => $this->importSettings->getColumnData('point_note', $row),
            'rawData' => $row
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
        return $import->slice($this->importSettings->start_row - 1);
    }
}
