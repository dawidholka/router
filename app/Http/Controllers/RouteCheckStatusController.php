<?php

namespace App\Http\Controllers;

use App\Actions\Routes\CheckRouteStatus;
use App\Models\Route;
use App\Support\RouteDictionary;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RouteCheckStatusController extends Controller
{
    public function __invoke(
        Route            $route,
        CheckRouteStatus $checkRouteStatus
    ): RedirectResponse
    {
        abort_if(!auth()->user()->admin, 403);

        $checkRouteStatus->execute($route);

        return redirect()->back();
    }
}
