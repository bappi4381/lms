<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /** Core roles mirrored from Filament's RoleResource — cannot be deleted. */
    private const CORE_ROLES = ['admin', 'instructor', 'student'];

    public function index(Request $request): View
    {
        $query = Role::query()->withCount(['permissions', 'users']);

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $roles = $query->orderBy('name')->paginate(20)->withQueryString();

        return view('admin.roles.index', compact('roles'));
    }

    public function create(): View
    {
        $permissions = Permission::orderBy('name')->pluck('name', 'id');

        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'          => 'required|string|max:125|unique:roles,name',
            'guard_name'    => 'required|string|max:125',
            'permissions'   => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create([
            'name'       => $data['name'],
            'guard_name' => $data['guard_name'],
        ]);

        if (! empty($data['permissions'])) {
            $role->permissions()->sync($data['permissions']);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', "Role '{$role->name}' created successfully!");
    }

    public function edit(Role $role): View
    {
        $permissions = Permission::orderBy('name')->pluck('name', 'id');
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $data = $request->validate([
            'name'          => "required|string|max:125|unique:roles,name,{$role->id}",
            'guard_name'    => 'required|string|max:125',
            'permissions'   => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update([
            'name'       => $data['name'],
            'guard_name' => $data['guard_name'],
        ]);

        $role->permissions()->sync($data['permissions'] ?? []);

        return redirect()->route('admin.roles.index')
            ->with('success', "Role '{$role->name}' updated successfully!");
    }

    public function destroy(Role $role): RedirectResponse
    {
        // Mirrors RoleResource's DeleteAction::before() guard on core roles.
        if (in_array($role->name, self::CORE_ROLES, true)) {
            return redirect()->back()
                ->with('error', "The \"{$role->name}\" role is a core role and cannot be deleted.");
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role deleted successfully!');
    }
}
