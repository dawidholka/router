<?php

namespace App\Http\Controllers;

use App\Datatables\PointSearchDatatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
