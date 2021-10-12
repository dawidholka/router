<?php

namespace App\Http\Controllers;

use App\Exports\RouteExport;
use App\Models\Route;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class RouteExportXLSXController extends Controller
{
    public function __invoke(Route $route): BinaryFileResponse
    {
        $export = new RouteExport($route);

        return Excel::download($export, 'trasa-'.$route->id.'.xlsx');
    }
}
