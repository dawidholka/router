<?php

namespace App\Http\Controllers;

use App\Actions\Routes\CreateRoute;
use App\Actions\Routes\DeleteRoute;
use App\Actions\Routes\UpdateRoute;
use App\Datatables\RouteDatatable;
use App\Models\Driver;
use App\Models\Route;
use App\ViewModels\RouteShowViewModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class RouteController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Routes/Index');
    }

    public function datatable(Request $request, RouteDatatable $datatable): JsonResponse
    {
        $data = $datatable->make($request);

        return response()->json($data);
    }

    public function create(): Response
    {
        $drivers = Driver::all(['id', 'name']);

        return Inertia::render('Routes/Create', compact('drivers'));
    }

    public function store(Request $request, CreateRoute $createRoute): RedirectResponse
    {
        $request->validate([
            'driver' => ['nullable', 'integer', 'exists:drivers,id'],
            'delivery_date' => ['required', 'date'],
            'note' => ['nullable', 'string']
        ]);

        $route = $createRoute->execute(
            Carbon::parse($request['delivery_date']),
            $request['driver'],
            $request['note']
        );

        return redirect()->route('routes.show', $route->id);
    }

    public function show(Route $route): Response
    {
        $viewModel = new RouteShowViewModel($route);

        return Inertia::render('Routes/Show', $viewModel->toArray());
    }

    public function edit(Route $route): Response
    {
        $viewModel = new RouteShowViewModel($route);

        return Inertia::render('Routes/Edit', $viewModel->toArray());
    }

    public function update(Request $request, Route $route, UpdateRoute $updateRoute): RedirectResponse
    {
        $request->validate([
            'driver' => ['nullable', 'integer', 'exists:drivers,id'],
            'delivery_date' => ['required', 'date'],
            'note' => ['nullable', 'string']
        ]);

        $route = $updateRoute->execute(
            $route,
            Carbon::parse($request['delivery_date']),
            $request['driver'],
            $request['note']
        );

        return redirect()->route('routes.edit', $route->id);
    }

    public function destroy(Route $route, DeleteRoute $deleteRoute): RedirectResponse
    {
        $deleteRoute->execute($route);

        return redirect()->route('routes.index');
    }
}
