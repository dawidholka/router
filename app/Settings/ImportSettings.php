<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class ImportSettings extends Settings
{
    public int $start_row;
    public ?string $point_name;
    public ?string $point_street;
    public ?string $point_building_number;
    public ?string $point_apartment;
    public ?string $point_city;
    public ?string $point_postcode;
    public ?string $point_intercom;
    public ?string $point_phone;
    public ?string $point_note;
    public ?string $waypoint_content;
    public ?string $waypoint_quantity;

    public function getColumnData(string $name, array $row): mixed
    {
        if (!property_exists($this, $name)) {
            return null;
        }

        $indexes = $this->getColumnIndexes($this->$name);

        if (empty($indexes)) {
            return null;
        }

        $data = '';
        foreach ($indexes as $key => $index) {
            if (!isset($row[$index])) {
                continue;
            }

            $data .= $row[$index] . ' ';
        }

        return trim($data);
    }

    public static function group(): string
    {
        return 'import';
    }

    /**
     * @param string|null $columnLetters
     * @return array<int>|null
     */
    private function getColumnIndexes(?string $columnLetters): ?array
    {
        if (!$columnLetters) {
            return null;
        }

        $lettersArray = explode(' ', $columnLetters);

        $indexesArray = [];

        foreach ($lettersArray as $letter) {
            $index = ord($letter) - 65;

            if ($index > 0) {
                $indexesArray[] = $index;
            }
        }

        return $indexesArray;
    }
}
