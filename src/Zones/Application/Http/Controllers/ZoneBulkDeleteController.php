<?php

namespace Router\Zones\Application\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Router\Zones\Domain\Models\Zone;

class ZoneBulkDeleteController extends Controller
{
    public function __invoke(): RedirectResponse
    {
        abort_if(!auth()->user()->admin, 403);

        Zone::truncate();

        Cache::forget('zones');

        return redirect()->back();
    }
}
