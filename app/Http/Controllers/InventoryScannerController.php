<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Asset;
use App\Models\Room;

class InventoryScannerController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display the inventory scanner page.
     */
    public function index(): View
    {
        $this->authorize('scan_inventory');
        return view('inventory_scanner.index');
    }

    /**
     * Handle the scanned QR code data.
     */
    public function scan(Request $request): JsonResponse
    {
        $this->authorize('scan_inventory');

        $qrData = $request->input('qr_data');

        // Парсим данные из QR-кода
        parse_str($qrData, $parsedData);

        $type = $parsedData['type'] ?? null;
        $id = $parsedData['id'] ?? null;

        if (!$type || !$id) {
            return response()->json([
                'success' => false,
                'message' => __('Invalid QR code format.')
            ], 400);
        }

        if ($type === 'asset') {
            $item = Asset::with(['category', 'status', 'room.floor.location', 'currentUser'])->find($id);
            if ($item) {
                return response()->json([
                    'success' => true,
                    'type' => 'asset',
                    'id' => $item->id,
                    'name' => $item->inventory_number ?? $item->serial_number,
                    'serial_number' => $item->serial_number,
                    'inventory_number' => $item->inventory_number,
                    'status' => $item->status->name ?? '-',
                    'location' => $item->room->name ?? __('Warehouse'),
                    'floor' => $item->room->floor->name ?? '-',
                    'building' => $item->room->floor->location->name ?? '-',
                    'responsible_person' => $item->currentUser->name ?? '-',
                    'url' => route('assets.show', $item->id),
                ]);
            }
        } elseif ($type === 'room') {
            $item = Room::with(['floor.location', 'assets'])->find($id);
            if ($item) {
                return response()->json([
                    'success' => true,
                    'type' => 'room',
                    'id' => $item->id,
                    'name' => $item->name,
                    'description' => $item->description,
                    'location' => $item->floor->location->name ?? '-',
                    'floor' => $item->floor->name ?? '-',
                    'assets_count' => $item->assets->count(),
                    'url' => route('rooms.show', $item->id),
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => __('Item not found or type not recognized.')
        ], 404);
    }
}
