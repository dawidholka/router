<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

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
