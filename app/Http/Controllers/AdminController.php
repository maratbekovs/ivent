<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display the admin settings dashboard.
     */
    public function index(): View
    {
        $this->authorize('manage_settings'); // Разрешение для доступа к админ-настройкам

        return view('admin.settings');
    }

    /**
     * Display a listing of roles.
     */
    public function rolesIndex(): View
    {
        $this->authorize('manage_roles'); // Разрешение для управления ролями

        $roles = Role::with('permissions')->get(); // Загружаем роли с их разрешениями

        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for editing the specified role's permissions.
     */
    public function rolesEdit(Role $role): View
    {
        $this->authorize('manage_roles'); // Разрешение для управления ролями

        $permissions = Permission::all(); // Все доступные разрешения

        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified role's permissions in storage.
     */
    public function rolesUpdate(Request $request, Role $role): RedirectResponse
    {
        $this->authorize('manage_roles'); // Разрешение для управления ролями

        $validated = $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name', // Проверяем, что каждое разрешение существует
        ]);

        // Синхронизируем разрешения для роли
        $role->syncPermissions($validated['permissions'] ?? []);

        return redirect()->route('admin.roles.index')->with('status', __('Role permissions updated successfully!'));
    }

    /**
     * Display a listing of all permissions.
     * Этот метод больше не используется, так как список разрешений встроен в редактирование ролей.
     */
    // public function permissionsIndex(): View
    // {
    //     $this->authorize('manage_permissions');
    //     $permissions = Permission::all();
    //     return view('admin.permissions.index', compact('permissions'));
    // }
}
