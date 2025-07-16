<?php

namespace App\Http\Controllers;

use App\Models\StockMovement;
use App\Models\Asset; // Для выпадающего списка
use App\Models\Room;  // Для выпадающего списка
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth; // Для получения текущего пользователя
use Illuminate\Support\Facades\Log; // Добавлено для отладки

class StockMovementController extends Controller
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
            Log::info('Пользователь ' . $user->email . ' пытается просмотреть список перемещений.');
            Log::info('Проверка view_stock_movements: ' . ($user->hasPermissionTo('view_stock_movements') ? 'ДА' : 'НЕТ'));
        }

        $this->authorize('view_stock_movements');

        // Загружаем связанные данные для отображения в таблице
        $movements = StockMovement::with(['asset.room.floor.location', 'fromLocation.floor.location', 'toLocation.floor.location', 'user'])
                                  ->latest()
                                  ->paginate(10);

        return view('stock_movements.index', compact('movements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // ВРЕМЕННАЯ ОТЛАДКА
        if (Auth::check()) {
            $user = Auth::user();
            Log::info('Пользователь ' . $user->email . ' пытается создать перемещение.');
            Log::info('Проверка create_stock_movements: ' . ($user->hasPermissionTo('create_stock_movements') ? 'ДА' : 'НЕТ'));
            Log::info('Проверка manage_stock_movements: ' . ($user->hasPermissionTo('manage_stock_movements') ? 'ДА' : 'НЕТ'));
        }

        $this->authorize('create_stock_movements'); // Или 'manage_stock_movements' если хотим более общее разрешение

        $assets = Asset::all(); // Все активы для выбора
        $rooms = Room::with(['floor.location'])->get(); // Все комнаты для выбора (откуда/куда)

        return view('stock_movements.create', compact('assets', 'rooms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create_stock_movements'); // Или 'manage_stock_movements'

        $validated = $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'type' => 'required|in:in,out,transfer',
            // from_location_id и to_location_id могут быть null, если это "склад"
            'from_location_id' => 'nullable|exists:rooms,id',
            'to_location_id' => 'nullable|exists:rooms,id',
            'movement_date' => 'required|date',
            'notes' => 'nullable|string|max:2000',
        ]);

        // Автоматически устанавливаем пользователя, который совершил перемещение
        $validated['user_id'] = Auth::id();

        $movement = StockMovement::create($validated);

        // Обновляем текущее местоположение актива
        $asset = Asset::find($validated['asset_id']);
        if ($asset) {
            // Если тип 'out' или 'transfer', устанавливаем room_id в to_location_id
            // Если тип 'in', актив прибывает на склад (room_id = null)
            if ($validated['type'] === 'in') {
                $asset->room_id = null; // Актив на складе
            } else {
                $asset->room_id = $validated['to_location_id'];
            }
            $asset->save();
        }

        return redirect()->route('stock-movements.index')->with('status', __('Movement created successfully!'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StockMovement $stockMovement): View
    {
        $this->authorize('edit_stock_movements'); // Или 'manage_stock_movements'

        $assets = Asset::all();
        $rooms = Room::with(['floor.location'])->get();

        return view('stock_movements.edit', ['movement' => $stockMovement, 'assets' => $assets, 'rooms' => $rooms]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StockMovement $stockMovement): RedirectResponse
    {
        $this->authorize('edit_stock_movements'); // Или 'manage_stock_movements'

        $validated = $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'type' => 'required|in:in,out,transfer',
            'from_location_id' => 'nullable|exists:rooms,id',
            'to_location_id' => 'nullable|exists:rooms,id',
            'movement_date' => 'required|date',
            'notes' => 'nullable|string|max:2000',
        ]);

        // user_id не обновляем, так как это кто совершил первое перемещение

        $stockMovement->update($validated);

        // Обновляем текущее местоположение актива
        $asset = Asset::find($validated['asset_id']);
        if ($asset) {
            if ($validated['type'] === 'in') {
                $asset->room_id = null;
            } else {
                $asset->room_id = $validated['to_location_id'];
            }
            $asset->save();
        }

        return redirect()->route('stock-movements.index')->with('status', __('Movement updated successfully!'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StockMovement $stockMovement): RedirectResponse
    {
        $this->authorize('delete_stock_movements'); // Или 'manage_stock_movements'

        $stockMovement->delete();

        return redirect()->route('stock-movements.index')->with('status', __('Movement deleted successfully!'));
    }
}
