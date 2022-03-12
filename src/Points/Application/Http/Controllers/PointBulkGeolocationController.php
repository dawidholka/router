<?php

namespace Router\Points\Application\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Router\Points\Domain\Models\Point;

class PointBulkGeolocationController extends Controller
{
    public function __invoke(): RedirectResponse
    {
        abort_if(!auth()->user()->admin, 403);

        // TODO BATCH JOBS

        return redirect()->back();
    }
}
