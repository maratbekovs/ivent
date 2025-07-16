<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth; // Добавлено

class LocationController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $this->authorize('view_locations');

        $locations = Location::latest()->paginate(10);

        return view('locations.index', compact('locations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // ВРЕМЕННАЯ ОТЛАДКА: Проверка разрешений перед authorize()
        if (Auth::check()) {
            $user = Auth::user();
            \Log::info('Пользователь ' . $user->email . ' пытается создать локацию.');
            \Log::info('Роли пользователя: ' . implode(', ', $user->getRoleNames()->toArray()));
            \Log::info('Все разрешения пользователя: ' . implode(', ', $user->getAllPermissions()->pluck('name')->toArray()));
            \Log::info('Проверка manage_locations: ' . ($user->hasPermissionTo('manage_locations') ? 'ДА' : 'НЕТ'));
            \Log::info('Проверка create_locations: ' . ($user->hasPermissionTo('create_locations') ? 'ДА' : 'НЕТ'));
        } else {
            \Log::info('Неавторизованный пользователь пытается создать локацию.');
        }

        $this->authorize('manage_locations'); // Здесь происходит проверка

        return view('locations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('manage_locations');

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:locations',
            'address' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        Location::create($validated);

        return redirect()->route('locations.index')->with('status', __('Location created successfully!'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Location $location): View
    {
        $this->authorize('manage_locations');

        return view('locations.edit', compact('location'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Location $location): RedirectResponse
    {
        $this->authorize('manage_locations');

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:locations,name,' . $location->id,
            'address' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $location->update($validated);

        return redirect()->route('locations.index')->with('status', __('Location updated successfully!'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location): RedirectResponse
    {
        $this->authorize('manage_locations');

        $location->delete();

        return redirect()->route('locations.index')->with('status', __('Location deleted successfully!'));
    }
}
