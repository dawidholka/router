<?php

namespace App\Http\Controllers;

use App\Actions\Routes\CloneRoute;
use App\Models\Route;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RouteCloneController extends Controller
{
    public function __invoke(Route $route, CloneRoute $cloneRoute): RedirectResponse
    {
        abort_if(!auth()->user()->admin, 403);

        $newRoute = $cloneRoute->execute($route);

        return redirect()->route('routes.edit', $newRoute->id);
    }
}
