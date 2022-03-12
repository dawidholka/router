<?php

namespace Router\Routes\Application\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Router\Routes\Domain\Actions\CloneRoute;
use Router\Routes\Domain\Models\Route;

class RouteCloneController extends Controller
{
    public function __invoke(Route $route, CloneRoute $cloneRoute): RedirectResponse
    {
        abort_if(!auth()->user()->admin, 403);

        $newRoute = $cloneRoute->execute($route);

        return redirect()->route('routes.edit', $newRoute->id);
    }
}
