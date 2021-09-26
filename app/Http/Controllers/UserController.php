<?php

namespace App\Http\Controllers;

use App\Actions\Users\CreateUser;
use App\Actions\Users\UpdateUser;
use App\Datatables\UserDatatable;
use App\DTOs\UserData;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Users/Index');
    }

    public function datatable(
        Request       $request,
        UserDatatable $datatable
    ): JsonResponse
    {
        $data = $datatable->make($request);

        return response()->json($data);
    }

    public function create(): Response
    {
        return Inertia::render('Users/Create');
    }

    public function store(
        Request    $request,
        CreateUser $createUser
    ): RedirectResponse
    {
        abort_if(!auth()->user()->admin, 403);

        $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string'],
            'admin' => ['required', 'boolean']
        ]);

        $user = $createUser->execute(new UserData([
            'name' => $request['name'],
            'email' => $request['email'],
            'admin' => $request['admin'],
            'password' => Hash::make($request['password'])
        ]));

        return redirect()->route('users.index');
    }

    public function edit(User $user): Response
    {
        abort_if(!auth()->user()->admin, 403);

        return Inertia::render('Users/Create', [
            'pageUser' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'admin' => $user->admin
            ]
        ]);
    }

    public function update(
        Request    $request,
        User       $user,
        UpdateUser $updateUser
    ): RedirectResponse
    {
        abort_if(!auth()->user()->admin, 403);

        $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string'],
            'admin' => ['required', 'boolean']
        ]);

        $updateUser->execute($user, new UserData([
            'name' => $request['name'],
            'email' => $request['email'],
            'admin' => $request['admin'],
            'password' => $request['password'] ?
                Hash::make($request['password']) : null,
        ]));

        return redirect()->back();
    }

    public function destroy(User $user): RedirectResponse
    {
        abort_if(!auth()->user()->admin, 403);

        $user->delete();
        return redirect()->back();
    }
}
