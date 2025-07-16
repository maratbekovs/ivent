<?php

namespace App\Http\Controllers;

use App\Models\Floor;
use App\Models\Location; // Для выпадающего списка
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class FloorController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $this->authorize('view_locations'); // Разрешение на просмотр локаций включает этажи

        $floors = Floor::with('location')->latest()->paginate(10);

        return view('floors.index', compact('floors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorize('manage_locations'); // Разрешение на управление локациями включает этажи

        $locations = Location::all(); // Получаем все локации для выпадающего списка

        return view('floors.create', compact('locations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('manage_locations');

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                // Уникальность имени этажа в рамках одной локации
                'unique:floors,name,NULL,id,location_id,' . $request->location_id,
            ],
            'location_id' => 'required|exists:locations,id',
            'description' => 'nullable|string|max:1000',
        ]);

        Floor::create($validated);

        return redirect()->route('floors.index')->with('status', __('Floor created successfully!'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Floor $floor): View
    {
        $this->authorize('manage_locations');

        $locations = Location::all(); // Получаем все локации для выпадающего списка

        return view('floors.edit', compact('floor', 'locations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Floor $floor): RedirectResponse
    {
        $this->authorize('manage_locations');

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                // Уникальность имени этажа в рамках одной локации, игнорируя текущий этаж
                'unique:floors,name,' . $floor->id . ',id,location_id,' . $request->location_id,
            ],
            'location_id' => 'required|exists:locations,id',
            'description' => 'nullable|string|max:1000',
        ]);

        $floor->update($validated);

        return redirect()->route('floors.index')->with('status', __('Floor updated successfully!'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Floor $floor): RedirectResponse
    {
        $this->authorize('manage_locations');

        $floor->delete();

        return redirect()->route('floors.index')->with('status', __('Floor deleted successfully!'));
    }
}
