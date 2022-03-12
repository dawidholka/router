<?php

namespace Router\Points\Application\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Router\Points\Application\Datatables\PointSearchDatatable;

class PointSearchDatatableController extends Controller
{
    public function __invoke(
        Request $request,
        PointSearchDatatable $datatable
    ): JsonResponse
    {
        $data = $datatable->make($request);

        return response()->json($data);
    }
}
