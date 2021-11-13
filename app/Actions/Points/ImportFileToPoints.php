<?php

namespace App\Actions\Points;

use App\DTOs\ImportedPointData;
use App\DTOs\PointData;
use App\Models\Point;
use App\Settings\ImportSettings;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

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
     * @return ImportedPointData[]
     */
    public function execute(
        UploadedFile $file
    ): array
    {
        $rows = $this->getRowsCollection($file);
        $importedPoints = [];
        $pointsDataBulk = [];

        foreach ($rows as $row) {
            $pointData = $this->mapRowToPointData($row);
            $pointsDataBulk[] = $pointData;

//            $point = $this->firstOrCreatePoint->execute($pointData);
//
//            $importedPoints[] = new ImportedPointData([
//                'point' => $point,
//                'data' => $pointData
//            ]);
        }

        $points = Point::query();
        $i = 1;
        /** @var PointData $item */
        foreach ($pointsDataBulk as $item) {
            if ($i++ == 1) {
                $points->where(function ($query) use ($item) {
                    $query->whereName($item->name)
                        ->whereStreet($item->street)
                        ->whereBuildingNumber($item->building_number)
                        ->whereCity($item->city);
                });
            } else {
                $points->orWhere(function ($query) use ($item) {
                    $query->whereName($item->name)
                        ->whereStreet($item->street)
                        ->whereBuildingNumber($item->building_number)
                        ->whereCity($item->city);
                });
            }
        }

        $points = $points->get()->keyBy('id');
        /** @var Collection $points */

        $toBeCreated = [];
        $completed = [];
        $toBeUpdated = [];

        /** @var PointData $item */
        foreach ($pointsDataBulk as $pointData) {
            $currentPoint = $points->where('name', $pointData->name)
                ->where('street', $pointData->street);
            if($currentPoint->isNotEmpty()){
                /** @var Point $currentPoint */
                $currentPoint = $currentPoint->first();
                if($this->isDataEqual($pointData, $currentPoint)){
                    $completed[] = new ImportedPointData([
                        'point' => $currentPoint,
                        'data' => $pointData
                    ]);
                }else{
                    $point = $this->firstOrCreatePoint->execute($pointData);
                    $completed[] = new ImportedPointData([
                        'point' => $point,
                        'data' => $pointData
                    ]);
                }
            }else{
                $point = $this->firstOrCreatePoint->execute($pointData);
                $completed[] = new ImportedPointData([
                    'point' => $point,
                    'data' => $pointData
                ]);
            }
        }

        return $completed;
    }

    private function mapRowToPointData(array $row): PointData
    {
        return new PointData([
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

    private function isDataEqual(PointData $pointData, Point $point): bool
    {
        $point->apartament = $pointData->apartament;
        $point->intercom = $pointData->intercom;
        $point->phone = $pointData->phone;
        $point->delivery_time = $pointData->delivery_time;
        $point->note = $pointData->note;
        $point->postcode = $pointData->postcode;
        $point->lat = $pointData->lat ?? $point->lat;
        $point->long = $pointData->long ?? $point->long;
        $point->lock_geo = $pointData->lock_geo ?? $point->lock_geo;

        return !$point->isDirty();
    }
}
