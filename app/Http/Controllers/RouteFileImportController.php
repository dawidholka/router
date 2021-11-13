<?php

namespace App\Http\Controllers;

use App\Actions\Routes\ImportFileToRoute;
use App\Models\Route;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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
