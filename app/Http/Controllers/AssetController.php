<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\AssetStatus;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use SimpleSoftwareIO\QrCode\Facades\QrCode; // Используется для генерации SVG в Blade
// use Illuminate\Support\Facades\Log; // Отладочные логи можно удалить, но пока оставим

class AssetController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $this->authorize('view_assets');

        $assets = Asset::with(['category', 'status', 'room.floor.location', 'currentUser'])
                       ->latest()
                       ->paginate(10);

        return view('assets.index', compact('assets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorize('create_assets');

        $categories = AssetCategory::all();
        $statuses = AssetStatus::all();
        $rooms = Room::with(['floor.location'])->get();
        $users = User::all();

        return view('assets.create', compact('categories', 'statuses', 'rooms', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create_assets');

        $validated = $request->validate([
            'serial_number' => 'required|string|max:255|unique:assets',
            'inventory_number' => 'nullable|string|max:255|unique:assets',
            'mac_address' => 'nullable|string|max:255',
            'category_id' => 'required|exists:asset_categories,id',
            'asset_status_id' => 'required|exists:asset_statuses,id',
            'room_id' => 'nullable|exists:rooms,id',
            'current_user_id' => 'nullable|exists:users,id',
            'purchase_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'warranty_expires_at' => 'nullable|date',
            'notes' => 'nullable|string|max:2000',
        ]);

        // Создаем актив. qr_code_data пока не устанавливаем, так как asset->id еще нет.
        $asset = Asset::create($validated);

        // Теперь, когда asset->id доступен, генерируем данные QR-кода
        $qrCodeData = "type=asset&id={$asset->id}&sn=" . urlencode($asset->serial_number);
        $asset->qr_code_data = $qrCodeData; // Сохраняем строку данных
        $asset->save(); // Сохраняем актив повторно с qr_code_data

        return redirect()->route('assets.index')->with('status', __('Asset created successfully!'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Asset $asset): View
    {
        $this->authorize('view_assets');

        $asset->load(['category', 'status', 'room.floor.location', 'currentUser']);

        return view('assets.show', compact('asset'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asset $asset): View
    {
        $this->authorize('edit_assets');

        $categories = AssetCategory::all();
        $statuses = AssetStatus::all();
        $rooms = Room::with(['floor.location'])->get();
        $users = User::all();

        return view('assets.edit', compact('asset', 'categories', 'statuses', 'rooms', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Asset $asset): RedirectResponse
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
            'purchase_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'warranty_expires_at' => 'nullable|date',
            'notes' => 'nullable|string|max:2000',
        ]);

        $asset->update($validated);

        // Обновляем данные QR-кода, если серийный/инвентарный номер изменился или данных нет
        $newQrCodeData = "type=asset&id={$asset->id}&sn=" . urlencode($asset->serial_number);
        if ($asset->isDirty('serial_number') || $asset->isDirty('inventory_number') || !$asset->qr_code_data || $asset->qr_code_data !== $newQrCodeData) {
            $asset->qr_code_data = $newQrCodeData;
            $asset->save(); // Сохраняем актив повторно с обновленными данными QR-кода
        }

        return redirect()->route('assets.index')->with('status', __('Asset updated successfully!'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asset $asset): RedirectResponse
    {
        $this->authorize('delete_assets');
        // Логика удаления файла QR-кода больше не нужна, так как файл не хранится
        $asset->delete();
        return redirect()->route('assets.index')->with('status', __('Asset deleted successfully!'));
    }
}
