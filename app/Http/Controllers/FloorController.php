<?php

namespace App\Http\Controllers;

use App\Models\Floor;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class FloorController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('view_locations');

        $query = Floor::with('location');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }

        if ($request->filled('location_id')) {
            $query->where('location_id', $request->input('location_id'));
        }

        $floors = $query->latest()->paginate(15)->withQueryString();
        $locations = Location::orderBy('name')->get();

        return view('floors.index', compact('floors', 'locations'));
    }

    public function create()
    {
        $this->authorize('create_locations');
        $locations = Location::all();
        return view('floors.create', compact('locations'));
    }

    public function store(Request $request)
    {
        $this->authorize('create_locations');
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location_id' => 'required|exists:locations,id',
            'description' => 'nullable|string',
        ]);
        Floor::create($validated);
        return redirect()->route('floors.index')->with('success', 'Floor created successfully.');
    }

    public function edit(Floor $floor)
    {
        $this->authorize('edit_locations');
        $locations = Location::all();
        return view('floors.edit', compact('floor', 'locations'));
    }

    public function update(Request $request, Floor $floor)
    {
        $this->authorize('edit_locations');
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location_id' => 'required|exists:locations,id',
            'description' => 'nullable|string',
        ]);
        $floor->update($validated);
        return redirect()->route('floors.index')->with('success', 'Floor updated successfully.');
    }

    public function destroy(Floor $floor)
    {
        $this->authorize('delete_locations');
        $floor->delete();
        return redirect()->route('floors.index')->with('success', 'Floor deleted successfully.');
    }
}
