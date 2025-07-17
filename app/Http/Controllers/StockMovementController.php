<?php

namespace App\Http\Controllers;

use App\Models\StockMovement;
use App\Models\Asset;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Barryvdh\DomPDF\Facade\Pdf;

class StockMovementController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('view_stock_movements');
        $query = StockMovement::with(['assets', 'user', 'fromLocation', 'toLocation']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('assets', function ($q) use ($search) {
                $q->where('serial_number', 'like', "%{$search}%")
                    ->orWhere('inventory_number', 'like', "%{$search}%");
            });
        }
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }
        $movements = $query->latest('movement_date')->paginate(20)->withQueryString();
        return view('stock_movements.index', compact('movements'));
    }

    public function create()
    {
        $this->authorize('create_stock_movements');
        $assets = Asset::orderBy('inventory_number')->get();
        $rooms = Room::with('floor.location')->get();
        return view('stock_movements.create', compact('assets', 'rooms'));
    }

    public function store(Request $request)
    {
        $this->authorize('create_stock_movements');
        $validated = $request->validate([
            'asset_ids' => 'required|array|min:1',
            'asset_ids.*' => 'exists:assets,id',
            'type' => 'required|in:in,out,transfer',
            'movement_date' => 'required|date',
            'from_location_id' => 'nullable|exists:rooms,id',
            'to_location_id' => 'nullable|exists:rooms,id',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {
            $movement = StockMovement::create([
                'user_id' => Auth::id(),
                'type' => $validated['type'],
                'movement_date' => $validated['movement_date'],
                'from_location_id' => $validated['from_location_id'],
                'to_location_id' => $validated['to_location_id'],
                'notes' => $validated['notes'],
            ]);

            // Привязываем несколько активов к перемещению
            $movement->assets()->attach($validated['asset_ids']);

            // Обновляем местоположение для каждого актива
            Asset::whereIn('id', $validated['asset_ids'])->update(['room_id' => $validated['to_location_id']]);
        });

        return redirect()->route('stock-movements.index')->with('success', 'Movement recorded successfully.');
    }

    public function edit(StockMovement $stockMovement)
    {
        $this->authorize('edit_stock_movements');
        $assets = Asset::orderBy('inventory_number')->get();
        $rooms = Room::with('floor.location')->get();
        
        // Получаем ID уже выбранных активов для предзаполнения
        $selectedAssetIds = $stockMovement->assets->pluck('id')->toArray();

        return view('stock_movements.edit', [
            'movement' => $stockMovement,
            'assets' => $assets,
            'rooms' => $rooms,
            'selectedAssetIds' => $selectedAssetIds,
        ]);
    }

    public function update(Request $request, StockMovement $stockMovement)
    {
        $this->authorize('edit_stock_movements');
        $validated = $request->validate([
            'asset_ids' => 'required|array|min:1',
            'asset_ids.*' => 'exists:assets,id',
            'type' => 'required|in:in,out,transfer',
            'movement_date' => 'required|date',
            'from_location_id' => 'nullable|exists:rooms,id',
            'to_location_id' => 'nullable|exists:rooms,id',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated, $stockMovement) {
            $stockMovement->update($validated);
            // Синхронизируем список активов
            $stockMovement->assets()->sync($validated['asset_ids']);
            // Обновляем местоположение для каждого актива
            Asset::whereIn('id', $validated['asset_ids'])->update(['room_id' => $validated['to_location_id']]);
        });

        return redirect()->route('stock-movements.index')->with('success', 'Movement updated successfully.');
    }

    public function destroy(StockMovement $stockMovement)
    {
        $this->authorize('delete_stock_movements');
        $stockMovement->delete();
        return redirect()->route('stock-movements.index')->with('success', 'Movement deleted successfully.');
    }

    public function downloadPdf(StockMovement $movement)
    {
        $this->authorize('view_stock_movements');
        $movement->load(['assets.category', 'assets.status', 'user', 'fromLocation', 'toLocation']);
        $pdf = Pdf::loadView('pdf.transfer_act', compact('movement'));
        return $pdf->download('act-transfer-'.$movement->id.'.pdf');
    }
}
