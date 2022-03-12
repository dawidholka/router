<?php

namespace Router\Routes\Application\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Support\RouteDictionary;
use Illuminate\Http\RedirectResponse;
use Router\Routes\Domain\Actions\CheckRouteStatus;
use Router\Routes\Domain\Models\Route;

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
