<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Floor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RoomController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('view_locations');

        $query = Room::with('floor.location');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }

        if ($request->filled('floor_id')) {
            $query->where('floor_id', $request->input('floor_id'));
        }

        $rooms = $query->latest()->paginate(15)->withQueryString();
        $floors = Floor::with('location')->orderBy('name')->get();

        return view('rooms.index', compact('rooms', 'floors'));
    }

    public function create()
    {
        $this->authorize('create_locations');
        $floors = Floor::with('location')->get();
        return view('rooms.create', compact('floors'));
    }

    public function store(Request $request)
    {
        $this->authorize('create_locations');
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'floor_id' => 'required|exists:floors,id',
            'description' => 'nullable|string',
        ]);
        
        $validated['qr_code_data'] = http_build_query([
            'type' => 'room',
            'id' => Str::orderedUuid(),
            'name' => $validated['name']
        ]);

        $room = Room::create($validated);

        $room->update([
            'qr_code_data' => http_build_query([
                'type' => 'room',
                'id' => $room->id,
                'name' => $room->name
            ])
        ]);

        return redirect()->route('rooms.index')->with('success', 'Room created successfully.');
    }

    public function show(Room $room)
    {
        $this->authorize('view_locations');
        $room->load('floor.location', 'assets.category');
        return view('rooms.show', compact('room'));
    }

    public function edit(Room $room)
    {
        $this->authorize('edit_locations');
        $floors = Floor::with('location')->get();
        return view('rooms.edit', compact('room', 'floors'));
    }

    public function update(Request $request, Room $room)
    {
        $this->authorize('edit_locations');
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'floor_id' => 'required|exists:floors,id',
            'description' => 'nullable|string',
        ]);

        $validated['qr_code_data'] = http_build_query([
            'type' => 'room',
            'id' => $room->id,
            'name' => $validated['name']
        ]);

        $room->update($validated);

        return redirect()->route('rooms.index')->with('success', 'Room updated successfully.');
    }

    public function destroy(Room $room)
    {
        $this->authorize('delete_locations');
        $room->delete();
        return redirect()->route('rooms.index')->with('success', 'Room deleted successfully.');
    }

    public function downloadQrCode(Room $room)
    {
        if (!$room->qr_code_data) {
            abort(404, __('QR Code data not found.'));
        }
        $svg = QrCode::size(200)->generate($room->qr_code_data);
        return response($svg, 200, [
            'Content-Type' => 'image/svg+xml',
            'Content-Disposition' => 'attachment; filename="' . $room->name . '_qrcode.svg"',
        ]);
    }
}