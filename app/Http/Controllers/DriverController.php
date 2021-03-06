<?php

namespace App\Http\Controllers;

use App\Actions\Drivers\CreateDriver;
use App\Actions\Drivers\DeleteDriver;
use App\Actions\Drivers\UpdateDriver;
use App\Actions\Drivers\UpdateDriverPassword;
use App\Datatables\DriverDatatable;
use App\DTOs\DriverData;
use App\Models\Driver;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DriverController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Drivers/Index');
    }

    public function datatable(Request $request, DriverDatatable $datatable): JsonResponse
    {
        $data = $datatable->make($request);

        return response()->json($data);
    }

    public function create(): Response
    {
        abort_if(!auth()->user()->admin, 403);
        $colors = Driver::COLORS;

        return Inertia::render('Drivers/Create', compact('colors'));
    }

    public function store(Request $request, CreateDriver $createDriver): RedirectResponse
    {
        abort_if(!auth()->user()->admin, 403);
        $request->validate([
            'login' => ['required', 'string', 'unique:drivers,login'],
            'password' => ['required', 'string'],
            'name' => ['required', 'string'],
            'color' => ['required', 'string'],
            'lat' => ['nullable', 'numeric'],
            'long' => ['nullable', 'numeric'],
        ]);

        $data = new DriverData($request->only(['login', 'password', 'name', 'color', 'lat', 'long']));

        $createDriver->execute($data);

        return redirect()->route('drivers.index');
    }

    public function show(Driver $driver): Response
    {
        return Inertia::render('Drivers/Show');
    }

    public function edit(Driver $driver): Response
    {
        abort_if(!auth()->user()->admin, 403);
        $colors = Driver::COLORS;
        return Inertia::render('Drivers/Create', compact(
            'driver', 'colors'
        ));
    }

    public function update(
        Request              $request,
        UpdateDriver         $updateDriver,
        Driver               $driver,
        UpdateDriverPassword $updateDriverPassword
    ): RedirectResponse
    {
        abort_if(!auth()->user()->admin, 403);

        $request->validate([
            'login' => ['required', 'string', 'unique:drivers,login,' . $driver->id],
            'password' => ['nullable', 'string'],
            'name' => ['required', 'string'],
            'color' => ['required', 'string'],
            'lat' => ['nullable', 'numeric'],
            'long' => ['nullable', 'numeric'],
        ]);

        $data = new DriverData($request->only(['login', 'password', 'name', 'color', 'lat', 'long']));

        $updateDriver->execute($driver, $data);

        if ($data->password) {
            $updateDriverPassword->execute($driver, $data);
        }

        return redirect()->route('drivers.index');
    }

    public function destroy(Driver $driver, DeleteDriver $deleteDriver): RedirectResponse
    {
        abort_if(!auth()->user()->admin, 403);

        $deleteDriver->execute($driver);

        return redirect()->back();
    }
}
