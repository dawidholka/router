<?php

namespace App\Http\Controllers;

use App\Jobs\GeocodeAllPointsJob;
use Illuminate\Http\RedirectResponse;

class PointBulkGeolocationController extends Controller
{
    public function __invoke(): RedirectResponse
    {
        abort_if(!auth()->user()->admin, 403);

        GeocodeAllPointsJob::dispatch();

        return redirect()->back();
    }
}
