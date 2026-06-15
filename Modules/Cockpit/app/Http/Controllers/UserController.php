<?php

namespace Modules\Cockpit\app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Cockpit\Models\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->paginate(20);

        return view('cockpit::users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();

        return view('cockpit::users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:8'],
            'roles'    => ['nullable', 'array'],
            'roles.*'  => ['exists:roles,id'],
        ]);

        $user = User::create($data);

        if (! empty($data['roles'])) {
            $user->syncRoles($data['roles']);
        }

        return redirect()->route('cockpit.users.index')
            ->with('success', __('cockpit::users.created'));
    }

    public function edit(User $user)
    {
        $roles     = Role::all();
        $userRoles = $user->roles->pluck('id')->toArray();

        return view('cockpit::users.edit', compact('user', 'roles', 'userRoles'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'confirmed', 'min:8'],
            'roles'    => ['nullable', 'array'],
            'roles.*'  => ['exists:roles,id'],
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);
        $user->syncRoles($data['roles'] ?? []);

        return redirect()->route('cockpit.users.index')
            ->with('success', __('cockpit::users.updated'));
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('cockpit.users.index')
            ->with('success', __('cockpit::users.deleted'));
    }
}
