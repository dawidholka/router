<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithStartRow;

class PointsImport implements WithStartRow, SkipsEmptyRows
{
    public function startRow(): int
    {
        return 3;
    }
}
