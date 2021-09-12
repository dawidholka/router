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
        $colors = Driver::COLORS;

        return Inertia::render('Drivers/Create', compact('colors'));
    }

    public function store(Request $request, CreateDriver $createDriver): RedirectResponse
    {
        $request->validate([
            'login' => ['required', 'string', 'unique:drivers,login'],
            'password' => ['required', 'string'],
            'name' => ['required', 'string'],
            'color' => ['required', 'string'],
        ]);

        $data = new DriverData($request->only(['login', 'password', 'name', 'color']));

        $createDriver->execute($data);

        return redirect()->back();
    }

    public function show(Driver $driver): Response
    {
        return Inertia::render('Drivers/Show');
    }

    public function edit(Driver $driver): Response
    {
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
        $request->validate([
            'login' => ['required', 'string', 'unique:drivers,login,' . $driver->id],
            'password' => ['nullable', 'string'],
            'name' => ['required', 'string'],
            'color' => ['required', 'string'],
        ]);

        $data = new DriverData($request->only(['login', 'password', 'name', 'color']));

        $updateDriver->execute($driver, $data);

        if ($data->password) {
            $updateDriverPassword->execute($driver, $data);
        }

        return redirect()->back();
    }

    public function destroy(Driver $driver, DeleteDriver $deleteDriver): RedirectResponse
    {
        $deleteDriver->execute($driver);

        return redirect()->back();
    }
}
