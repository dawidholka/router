<?php

namespace Router\Routes\Application\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Router\Routes\Domain\Actions\ImportFileToRoute;
use Router\Routes\Domain\Models\Route;

class RouteFileImportController extends Controller
{
    public function __invoke(Request $request, Route $route, ImportFileToRoute $importFileToRoute): RedirectResponse
    {
        abort_if(!auth()->user()->admin, 403);

        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx']
        ]);

        $importFileToRoute->execute($route, $request->file);

        return redirect()->back();
    }
}
