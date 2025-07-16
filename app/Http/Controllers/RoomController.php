<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Floor;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
// use Illuminate\Support\Facades\Log; // Отладочные логи можно удалить, но пока оставим

class RoomController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $this->authorize('view_locations');

        // КЛЮЧЕВОЕ ИЗМЕНЕНИЕ: Получаем список кабинетов
        $rooms = Room::with(['floor.location'])->latest()->paginate(10);

        return view('rooms.index', compact('rooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorize('manage_locations');

        $floors = Floor::with('location')->get();

        return view('rooms.create', compact('floors'));
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
                'unique:rooms,name,NULL,id,floor_id,' . $request->floor_id,
            ],
            'floor_id' => 'required|exists:floors,id',
            'description' => 'nullable|string|max:1000',
        ]);

        $room = Room::create($validated);

        // Генерируем данные QR-кода и сохраняем их в базу
        $qrCodeData = "type=room&id={$room->id}&name=" . urlencode($room->name);
        $room->qr_code_data = $qrCodeData; // Сохраняем строку данных
        $room->save(); // Сохраняем кабинет повторно с qr_code_data

        return redirect()->route('rooms.index')->with('status', __('Room created successfully!'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room): View
    {
        $this->authorize('view_locations');

        $room->load(['floor.location', 'assets']); // Загружаем активы для отображения

        return view('rooms.show', compact('room'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room): View
    {
        $this->authorize('manage_locations');

        $floors = Floor::with('location')->get();

        return view('rooms.edit', compact('room', 'floors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Room $room): RedirectResponse
    {
        $this->authorize('manage_locations');

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:rooms,name,' . $room->id . ',id,floor_id,' . $request->floor_id,
            ],
            'floor_id' => 'required|exists:floors,id',
            'description' => 'nullable|string|max:1000',
        ]);

        $room->update($validated);

        // Обновляем данные QR-кода, если имя кабинета изменилось или данных нет
        $newQrCodeData = "type=room&id={$room->id}&name=" . urlencode($room->name);
        if ($room->isDirty('name') || !$room->qr_code_data || $room->qr_code_data !== $newQrCodeData) {
            $room->qr_code_data = $newQrCodeData;
            $room->save(); // Сохраняем кабинет повторно с обновленными данными QR-кода
        }

        return redirect()->route('rooms.index')->with('status', __('Room updated successfully!'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room): RedirectResponse
    {
        $this->authorize('manage_locations');
        // Логика удаления файла QR-кода больше не нужна
        $room->delete();
        return redirect()->route('rooms.index')->with('status', __('Room deleted successfully!'));
    }
}
