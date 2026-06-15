<?php

namespace Modules\Cockpit\app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->paginate(20);

        return view('cockpit::roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::orderBy('module')->orderBy('name')->get()->groupBy('module');

        return view('cockpit::roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255', 'unique:roles'],
            'description' => ['nullable', 'string', 'max:255'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $role = Role::create([
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'guard_name'  => 'web',
        ]);

        if (! empty($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }

        return redirect()->route('cockpit.roles.index')
            ->with('success', __('cockpit::roles.created'));
    }

    public function edit(Role $role)
    {
        $permissions     = Permission::orderBy('module')->orderBy('name')->get()->groupBy('module');
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('cockpit::roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255', 'unique:roles,name,' . $role->id],
            'description'   => ['nullable', 'string', 'max:255'],
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $role->update([
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        $role->syncPermissions($data['permissions'] ?? []);

        return redirect()->route('cockpit.roles.index')
            ->with('success', __('cockpit::roles.updated'));
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('cockpit.roles.index')
            ->with('success', __('cockpit::roles.deleted'));
    }
}
