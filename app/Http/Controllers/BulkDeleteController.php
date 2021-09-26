<?php

namespace App\Http\Controllers;

use App\Actions\Points\BulkDeletePoints;
use App\Actions\Routes\BulkDeleteRoutes;
use App\Support\BulkDeleteDictionary;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BulkDeleteController extends Controller
{
    public function __invoke(
        Request          $request,
        BulkDeletePoints $bulkDeletePoints,
        BulkDeleteRoutes $bulkDeleteRoutes,
    ): RedirectResponse
    {
        abort_if(!auth()->user()->admin, 403);

        $request->validate([
            'option' => ['required', Rule::in(BulkDeleteDictionary::toArray())]
        ]);

        $bulkDeleteRoutes->execute($request['option']);
        $bulkDeletePoints->execute($request['option']);

        return redirect()->back();
    }
}
