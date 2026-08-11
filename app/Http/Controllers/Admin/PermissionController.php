<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index(Request $request): View
    {
        $query = Permission::query()->withCount('roles');

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($guard = $request->input('guard_name')) {
            $query->where('guard_name', $guard);
        }

        $permissions = $query->orderBy('name')->paginate(30)->withQueryString();

        return view('admin.permissions.index', compact('permissions'));
    }

    public function create(): View
    {
        return view('admin.permissions.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'       => 'required|string|max:125|unique:permissions,name',
            'guard_name' => 'required|string|max:125',
        ]);

        $permission = Permission::create($data);

        return redirect()->route('admin.permissions.index')
            ->with('success', "Permission '{$permission->name}' created successfully!");
    }

    public function edit(Permission $permission): View
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission): RedirectResponse
    {
        $data = $request->validate([
            'name'       => "required|string|max:125|unique:permissions,name,{$permission->id}",
            'guard_name' => 'required|string|max:125',
        ]);

        $permission->update($data);

        return redirect()->route('admin.permissions.index')
            ->with('success', "Permission '{$permission->name}' updated successfully!");
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        $permission->delete();

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission deleted successfully!');
    }
}
