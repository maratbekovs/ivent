<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth; // Добавлено для отладки
use Illuminate\Support\Facades\Log; // Добавлено для отладки

class UserController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // ВРЕМЕННАЯ ОТЛАДКА
        if (Auth::check()) {
            $user = Auth::user();
            Log::info('Пользователь ' . $user->email . ' пытается просмотреть список пользователей.');
            Log::info('Проверка view_users: ' . ($user->hasPermissionTo('view_users') ? 'ДА' : 'НЕТ'));
        }

        $this->authorize('view_users');

        $users = User::latest()->paginate(10);

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // ВРЕМЕННАЯ ОТЛАДКА
        if (Auth::check()) {
            $user = Auth::user();
            Log::info('Пользователь ' . $user->email . ' пытается создать пользователя.');
            Log::info('Проверка create_users: ' . ($user->hasPermissionTo('create_users') ? 'ДА' : 'НЕТ'));
            Log::info('Проверка manage_users: ' . ($user->hasPermissionTo('manage_users') ? 'ДА' : 'НЕТ'));
        }

        $this->authorize('create_users'); // Или 'manage_users' если хотим более общее разрешение

        $roles = Role::all();

        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create_users'); // Или 'manage_users'

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'roles' => 'array',
            'roles.*' => 'exists:roles,name',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'position' => $validated['position'],
        ]);

        if (!empty($validated['roles'])) {
            $user->assignRole($validated['roles']);
        }

        return redirect()->route('users.index')->with('status', __('User created successfully!'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
    {
        $this->authorize('edit_users'); // Или 'manage_users'

        $roles = Role::all();

        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $this->authorize('edit_users'); // Или 'manage_users'

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'roles' => 'array',
            'roles.*' => 'exists:roles,name',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'position' => $validated['position'],
        ]);

        $user->syncRoles($validated['roles'] ?? []);

        return redirect()->route('users.index')->with('status', __('User updated successfully!'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('delete_users'); // Или 'manage_users'

        if (Auth::id() === $user->id) {
            return redirect()->route('users.index')->withErrors(['delete_self' => __('You cannot delete your own account from here.')]);
        }

        $user->delete();

        return redirect()->route('users.index')->with('status', __('User deleted successfully!'));
    }
}
