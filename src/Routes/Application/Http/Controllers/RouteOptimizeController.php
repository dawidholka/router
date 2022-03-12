<?php

namespace Router\Routes\Application\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Router\Optimization\Contracts\Dictionaries\OptimizeDictionary;
use Router\Optimization\Contracts\DTOs\OptimizeRouteDTO;
use Router\Routes\Application\Jobs\OptimizeRoute;
use Router\Routes\Domain\Models\Route;

class RouteOptimizeController extends Controller
{
    public function __invoke(
        Route   $route,
        Request $request,
    ): RedirectResponse
    {
        $request->validate([
            'method' => ['required', Rule::in(OptimizeDictionary::toArray())]
        ]);

        $selectedMethod = $request['method'];

        $request->validate(OptimizeDictionary::validationRules[$selectedMethod]);

        $data = OptimizeRouteDTO::fromRoute($route, $selectedMethod);

        OptimizeRoute::dispatch($data);

        return redirect()->back();
    }
}
