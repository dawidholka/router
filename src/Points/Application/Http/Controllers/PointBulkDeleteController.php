<?php

namespace Router\Points\Application\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Support\BulkDeleteDictionary;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Router\Points\Domain\Actions\BulkDeletePoints;

class PointBulkDeleteController extends Controller
{
    public function __invoke(
        Request $request,
        BulkDeletePoints $bulkDeletePoints,
    ): RedirectResponse
    {
        abort_if(!auth()->user()->admin, 403);

        $request->validate([
            'option' => ['required', Rule::in(BulkDeleteDictionary::toArray())]
        ]);

        $bulkDeletePoints->execute($request['option']);

        return redirect()->back();
    }
}
