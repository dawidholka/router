<?php

namespace App\Http\Controllers;

use App\Actions\Routes\BulkDeleteRoutes;
use App\Support\BulkDeleteDictionary;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RouteBulkDeleteController extends Controller
{
    public function __invoke(
        Request          $request,
        BulkDeleteRoutes $bulkDeleteRoutes
    ): RedirectResponse
    {
        abort_if(!auth()->user()->admin, 403);

        $request->validate([
            'option' => ['required', Rule::in(BulkDeleteDictionary::toArray())]
        ]);

        $bulkDeleteRoutes->execute($request['option']);

        return redirect()->back();
    }
}
