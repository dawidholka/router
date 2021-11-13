<?php

namespace App\Http\Controllers;

use App\Actions\Routes\OptimizeRoute;
use App\DTOs\RouteOptimizeDTO;
use App\Models\Route;
use App\Support\OptimizeDictionary;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RouteOptimizeController extends Controller
{
    public function __invoke(
        Route         $route,
        Request       $request,
        OptimizeRoute $optimizeRoute
    ): RedirectResponse
    {
        $request->validate([
            'method' => ['required', Rule::in(OptimizeDictionary::toArray())]
        ]);

        $selectedMethod = $request['method'];

        $request->validate(OptimizeDictionary::validationRules[$selectedMethod]);

        try {
            $optimizeRoute->execute($route, new RouteOptimizeDTO([
                'method' => $selectedMethod,
                'file' => $request['file']
            ]));
        } catch (Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }

        return redirect()->back();
    }
}
