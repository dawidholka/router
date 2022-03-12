<?php

namespace Router\Routes\Application\Http\Controllers;

use App\Exports\RouteExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Router\Routes\Domain\Models\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class RouteExportXLSXController extends Controller
{
    public function __invoke(Route $route): BinaryFileResponse
    {
        $export = new RouteExport($route);

        return Excel::download($export, 'trasa-'.$route->id.'.xlsx');
    }
}
