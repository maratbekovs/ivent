<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Asset;
use App\Models\InventorySession;
use App\Models\InventorySessionItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
// ИЗМЕНЕНИЕ: Добавлен трейт для авторизации
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class GlobalInventoryController extends Controller
{
    // ИЗМЕНЕНИЕ: Подключение трейта
    use AuthorizesRequests;

    /**
     * Display a list of past inventory sessions.
     */
    public function index()
    {
        $this->authorize('scan_inventory');
        $sessions = InventorySession::with('room', 'user')->latest()->paginate(20);
        return view('global_inventory.index', compact('sessions'));
    }

    /**
     * Show the form for creating a new inventory session.
     */
    public function create()
    {
        $this->authorize('scan_inventory');
        $rooms = Room::orderBy('name')->get();
        return view('global_inventory.create', compact('rooms'));
    }

    /**
     * Store a newly created inventory session in storage and start it.
     */
    public function store(Request $request)
    {
        $this->authorize('scan_inventory');
        $request->validate(['room_id' => 'required|exists:rooms,id']);

        $session = InventorySession::create([
            'room_id' => $request->room_id,
            'user_id' => Auth::id(),
            'status' => 'in_progress',
        ]);

        return redirect()->route('global-inventory.show', $session);
    }

    /**
     * Display the specified inventory session (the scanner interface).
     */
    public function show(InventorySession $session)
    {
        $this->authorize('scan_inventory');

        // Загружаем связанные данные для эффективности
        $session->load('room.assets');

        // Получаем активы, которые должны быть в этом кабинете
        $expectedAssets = $session->room->assets()->with(['category', 'currentUser'])->get();

        // Получаем уже отсканированные в этой сессии активы
        $sessionItems = $session->items()->with('asset.room')->get();
        $scannedAssetIds = $sessionItems->pluck('asset_id');

        // Определяем, какие активы еще не найдены
        $missingAssets = $expectedAssets->whereNotIn('id', $scannedAssetIds);
        
        // Разделяем отсканированные на "найденные" и "лишние"
        $foundItems = $sessionItems->where('status', 'found');
        $extraItems = $sessionItems->where('status', 'extra');

        return view('global_inventory.show', compact('session', 'missingAssets', 'foundItems', 'extraItems'));
    }

    /**
     * Handle a scanned item via AJAX.
     */
    public function scan(Request $request, InventorySession $session)
    {
        $this->authorize('scan_inventory');

        $validated = $request->validate([
            'qr_code_data' => 'required|string',
        ]);

        parse_str($validated['qr_code_data'], $params);

        if (!isset($params['type']) || $params['type'] !== 'asset' || !isset($params['id'])) {
            return response()->json(['success' => false, 'message' => __('Invalid or non-asset QR code.')], 422);
        }

        $asset = Asset::with(['category', 'currentUser', 'room'])->find($params['id']);

        if (!$asset) {
            return response()->json(['success' => false, 'message' => __('Asset not found.')], 404);
        }

        // Проверяем, не был ли этот актив уже отсканирован в этой сессии
        if ($session->items()->where('asset_id', $asset->id)->exists()) {
            return response()->json(['success' => false, 'message' => __('Asset already scanned.'), 'status' => 'already_scanned']);
        }

        $status = ($asset->room_id == $session->room_id) ? 'found' : 'extra';

        $session->items()->create([
            'asset_id' => $asset->id,
            'expected_room_id' => $asset->room_id,
            'found_in_room_id' => $session->room_id,
            'status' => $status,
        ]);

        return response()->json([
            'success' => true,
            'status' => $status,
            'asset' => [
                'id' => $asset->id,
                'name' => $asset->inventory_number ?? $asset->serial_number,
                'category' => $asset->category->name ?? '-',
                'user' => $asset->currentUser->name ?? '-',
                'expected_room' => $asset->room->name ?? __('Warehouse'),
            ]
        ]);
    }

    /**
     * Mark the inventory session as complete.
     */
    public function complete(Request $request, InventorySession $session)
    {
        $this->authorize('scan_inventory');

        DB::transaction(function () use ($session, $request) {
            $expectedAssetIds = $session->room->assets()->pluck('id');
            $scannedAssetIds = $session->items()->pluck('asset_id');

            // Находим недостающие активы и создаем для них записи
            $missingAssetIds = $expectedAssetIds->diff($scannedAssetIds);
            foreach ($missingAssetIds as $assetId) {
                $session->items()->create([
                    'asset_id' => $assetId,
                    'expected_room_id' => $session->room_id,
                    'found_in_room_id' => $session->room_id,
                    'status' => 'missing',
                ]);
            }

            // Обновляем статус сессии
            $session->update([
                'status' => 'completed',
                'completed_at' => now(),
                'notes' => $request->input('notes'),
            ]);
        });
        
        return redirect()->route('global-inventory.report', $session);
    }

    /**
     * Display the report for a completed inventory session.
     */
    public function report(InventorySession $session)
    {
        $this->authorize('scan_inventory');
        $session->load('items.asset.category', 'items.asset.expectedRoom', 'room', 'user');
        return view('global_inventory.report', compact('session'));
    }
}
