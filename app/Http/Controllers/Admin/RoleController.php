<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use Spatie\Permission\Models\Permission;
use Inertia\Inertia;
use Spatie\Permission\PermissionRegistrar;

class RoleController extends Controller
{
    function flattenPermissions(array $data): array
    {
        $result = [];

        foreach ($data as $item) {
            $menu = $item['menu'];

            foreach (['view', 'create', 'edit', 'delete'] as $action) {
                if (!empty($item[$action])) {
                    $result[] = "{$action} {$menu}";
                }
            }
        }

        return $result;
    }
    
    public function index()
    {
        return Inertia::render('admin/RoleManagement/index', [
            'roles' => Role::with('permissions')->with('users')->get(),
            'permissions' => Permission::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'icon' => 'required|string',
            'status' => 'boolean',
            'permissions' => 'array',
        ]);
        $role = Role::create(['name' => $validated['name'], 'icon' => $validated['icon']],['status' => $validated['status']]);
        // $permission = [];

        // foreach (array_keys($validated['permissions']) as $perm) {
        //     array_push($permission, $perm);
        // }

        $role->syncPermissions($this->flattenPermissions($validated['permissions']));
        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();

        return back()->with('success', 'Role created.');
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'icon' => 'required|string',
            'status' => 'boolean',
            'permissions' => 'array',
        ]);

        $role->update(['name' => $validated['name'], 'icon' => $validated['icon'], 'status' => $validated['status']]);
        // $permission = [];
        // foreach (array_keys($validated['permissions']) as $perm) {
        //     array_push($permission, $perm);
        // }
        $role->syncPermissions($this->flattenPermissions($validated['permissions']));
        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();

        return back()->with('success', 'Role updated.');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->back()->with('success', 'Role deleted.');
    }

    
}
