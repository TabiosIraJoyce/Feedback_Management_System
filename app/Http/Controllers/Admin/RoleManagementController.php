<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleManagementController extends Controller
{
    public function index(): View
    {
        return view('admin.roles.index', [
            'roles' => Role::with('permissions')->orderBy('name')->get(),
            'permissions' => Permission::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $validated = $request->validate([
            'permissions' => ['array'],
            'permissions.*' => ['exists:permissions,name'],
        ]);

        $role->syncPermissions($validated['permissions'] ?? []);

        return back()->with('status', "Permissions updated for {$role->name}.");
    }
}
