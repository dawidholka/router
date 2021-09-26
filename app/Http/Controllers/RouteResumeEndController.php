<?php

namespace App\Http\Controllers;

use App\Actions\Routes\CheckRouteStatus;
use App\Models\Route;
use App\Support\RouteDictionary;
use Illuminate\Http\RedirectResponse;

class RouteResumeEndController extends Controller
{
    public function __invoke(
        Route            $route,
        CheckRouteStatus $checkRouteStatus
    ): RedirectResponse
    {
        abort_if(!auth()->user()->admin, 403);

        if ($route->status === RouteDictionary::ENDED) {
            $checkRouteStatus->execute($route);
        } else {
            $route->update(['status' => RouteDictionary::ENDED]);
        }

        return redirect()->back();
    }
}
