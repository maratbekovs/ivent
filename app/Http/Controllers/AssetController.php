<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\AssetStatus;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AssetController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('view_assets');

        $query = Asset::with(['category', 'status', 'room', 'currentUser']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('inventory_number', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%");
            });
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }
        if ($request->filled('status_id')) {
            $query->where('asset_status_id', $request->input('status_id'));
        }
        if ($request->filled('room_id')) {
            $query->where('room_id', $request->input('room_id'));
        }

        $assets = $query->latest()->paginate(15)->withQueryString();
        $categories = AssetCategory::orderBy('name')->get();
        $statuses = AssetStatus::orderBy('name')->get();
        $rooms = Room::orderBy('name')->get();

        return view('assets.index', compact('assets', 'categories', 'statuses', 'rooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create_assets');
        $categories = AssetCategory::all();
        $statuses = AssetStatus::all();
        $rooms = Room::with('floor.location')->get();
        $users = User::all();
        return view('assets.create', compact('categories', 'statuses', 'rooms', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create_assets');
        $validated = $request->validate([
            'serial_number' => 'required|string|max:255|unique:assets,serial_number',
            'inventory_number' => 'nullable|string|max:255|unique:assets,inventory_number',
            'mac_address' => 'nullable|string|max:255',
            'category_id' => 'required|exists:asset_categories,id',
            'asset_status_id' => 'required|exists:asset_statuses,id',
            'room_id' => 'nullable|exists:rooms,id',
            'current_user_id' => 'nullable|exists:users,id',
            'purchase_year' => 'nullable|integer|min:1900',
            'warranty_expires_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $validated['qr_code_data'] = http_build_query([
            'type' => 'asset',
            'id' => Str::orderedUuid(),
            'sn' => $validated['serial_number']
        ]);

        $asset = Asset::create($validated);

        $asset->update([
            'qr_code_data' => http_build_query([
                'type' => 'asset',
                'id' => $asset->id,
                'sn' => $asset->serial_number
            ])
        ]);

        return redirect()->route('assets.index')->with('success', 'Asset created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Asset $asset)
    {
        $this->authorize('view_assets');
        // ИЗМЕНЕНИЕ: Загружаем историю вместе с основной информацией
        $asset->load([
            'category',
            'status',
            'room.floor.location',
            'currentUser',
            'userHistory' => fn($q) => $q->with('user')->latest(),
            'stockMovements' => fn($q) => $q->with(['user', 'fromLocation', 'toLocation'])->latest(),
        ]);
        return view('assets.show', compact('asset'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asset $asset)
    {
        $this->authorize('edit_assets');
        $categories = AssetCategory::all();
        $statuses = AssetStatus::all();
        $rooms = Room::with('floor.location')->get();
        $users = User::all();
        return view('assets.edit', compact('asset', 'categories', 'statuses', 'rooms', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Asset $asset)
    {
        $this->authorize('edit_assets');
        $validated = $request->validate([
            'serial_number' => 'required|string|max:255|unique:assets,serial_number,' . $asset->id,
            'inventory_number' => 'nullable|string|max:255|unique:assets,inventory_number,' . $asset->id,
            'mac_address' => 'nullable|string|max:255',
            'category_id' => 'required|exists:asset_categories,id',
            'asset_status_id' => 'required|exists:asset_statuses,id',
            'room_id' => 'nullable|exists:rooms,id',
            'current_user_id' => 'nullable|exists:users,id',
            'purchase_year' => 'nullable|integer|min:1900',
            'warranty_expires_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);
        
        $validated['qr_code_data'] = http_build_query([
            'type' => 'asset',
            'id' => $asset->id,
            'sn' => $validated['serial_number']
        ]);

        $asset->update($validated);

        return redirect()->route('assets.index')->with('success', 'Asset updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asset $asset)
    {
        $this->authorize('delete_assets');
        $asset->delete();
        return redirect()->route('assets.index')->with('success', 'Asset deleted successfully.');
    }
    
    /**
     * Download the QR code for the specified asset.
     */
    public function downloadQrCode(Asset $asset)
    {
        if (!$asset->qr_code_data) {
            abort(404, __('QR Code data not found.'));
        }
        $svg = QrCode::size(200)->generate($asset->qr_code_data);
        return response($svg, 200, [
            'Content-Type' => 'image/svg+xml',
            'Content-Disposition' => 'attachment; filename="' . ($asset->inventory_number ?? $asset->serial_number) . '_qrcode.svg"',
        ]);
    }
}
