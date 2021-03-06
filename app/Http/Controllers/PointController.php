<?php

namespace App\Http\Controllers;

use App\Actions\Points\CreatePoint;
use App\Actions\Points\UpdatePoint;
use App\Datatables\PointDatatable;
use App\DTOs\PointData;
use App\Models\Point;
use App\ViewModels\PointShowViewModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PointController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Points/Index');
    }

    public function datatable(Request $request, PointDatatable $datatable): JsonResponse
    {
        $data = $datatable->make($request);

        return response()->json($data);
    }

    public function create(): Response
    {
        abort_if(!auth()->user()->admin, 403);
        return Inertia::render('Points/Create');
    }

    public function store(Request $request, CreatePoint $createPoint): RedirectResponse
    {
        abort_if(!auth()->user()->admin, 403);

        $validatedData = $request->validate([
            'name' => 'required',
            'street' => 'required',
            'building_number' => 'required',
            'city' => 'required',
            'apartament' => 'nullable',
            'intercom' => 'nullable',
            'delivery_time' => 'nullable',
            'phone' => 'nullable',
            'note' => 'nullable',
            'lat' => ['numeric', 'nullable'],
            'long' => ['numeric', 'nullable'],
            'lock_geo' => ['boolean']
        ]);

        $data = new PointData($validatedData);

        $point = $createPoint->execute($data);

        return redirect()->route('points.show', $point->id);
    }

    public function show(Point $point): Response
    {
        $viewModel = new PointShowViewModel($point);

        return Inertia::render('Points/Show', $viewModel->toArray());
    }

    public function edit(Point $point): Response
    {
        abort_if(!auth()->user()->admin, 403);

        return Inertia::render('Points/Create', compact('point'));
    }

    public function update(Point $point, Request $request, UpdatePoint $updatePoint): RedirectResponse
    {
        abort_if(!auth()->user()->admin, 403);

        $validatedData = $request->validate([
            'name' => 'required',
            'street' => 'required',
            'building_number' => 'required',
            'city' => 'required',
            'apartament' => 'nullable',
            'intercom' => 'nullable',
            'delivery_time' => 'nullable',
            'phone' => 'nullable',
            'note' => 'nullable',
            'lat' => ['numeric', 'nullable'],
            'long' => ['numeric', 'nullable'],
            'lock_geo' => ['boolean']
        ]);

        $data = new PointData($validatedData);

        $point = $updatePoint->execute($point, $data);

        return redirect()->route('points.show', $point->id);
    }

    public function destroy(Point $point): RedirectResponse
    {
        abort_if(!auth()->user()->admin, 403);

        $point->delete();

        return redirect()->route('points.index');
    }
}
