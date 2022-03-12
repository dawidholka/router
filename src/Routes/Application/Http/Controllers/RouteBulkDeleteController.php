<?php

namespace Router\Routes\Application\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Support\BulkDeleteDictionary;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Router\Routes\Domain\Actions\BulkDeleteRoutes;

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
