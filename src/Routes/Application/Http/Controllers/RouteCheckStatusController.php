<?php

namespace Router\Routes\Application\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Router\Routes\Domain\Actions\CheckRouteStatus;
use Router\Routes\Domain\Models\Route;

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
