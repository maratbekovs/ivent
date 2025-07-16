<?php

namespace App\Http\Controllers;

use App\Models\Request; // Важно: используем App\Models\Request
use App\Models\Asset;   // Для выпадающего списка
use App\Models\User;   // Для выпадающего списка
use Illuminate\Http\Request as HttpRequest; // Избегаем конфликта имен с моделью
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth; // Для получения текущего пользователя

class RequestController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $this->authorize('view_requests');

        // Загружаем связанные данные для отображения в таблице
        $requests = Request::with(['requester', 'assignedTo', 'asset'])
                           ->latest()
                           ->paginate(10);

        return view('requests.index', compact('requests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorize('create_requests');

        $assets = Asset::all(); // Все активы для выбора
        // Пользователи, которым можно назначить заявку (например, IT-отдел)
        // Пока что все пользователи, позже можно отфильтровать по ролям
        $users = User::all();

        return view('requests.create', compact('assets', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HttpRequest $httpRequest): RedirectResponse // Используем HttpRequest для Request $request
    {
        $this->authorize('create_requests');

        $validated = $httpRequest->validate([
            'type' => 'required|in:repair,setup,purchase,other',
            'description' => 'required|string|max:2000',
            'asset_id' => 'nullable|exists:assets,id',
            'priority' => 'required|in:low,medium,high,critical',
        ]);

        // Автоматически устанавливаем пользователя, который создал заявку
        $validated['requester_id'] = Auth::id();
        $validated['status'] = 'pending'; // Начальный статус

        Request::create($validated);

        return redirect()->route('requests.index')->with('status', __('Request created successfully!'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request): View
    {
        $this->authorize('edit_requests');

        $assets = Asset::all();
        $users = User::all(); // Все пользователи для назначения

        return view('requests.edit', ['request' => $request, 'assets' => $assets, 'users' => $users]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HttpRequest $httpRequest, Request $request): RedirectResponse // Используем HttpRequest
    {
        $this->authorize('edit_requests');

        $validated = $httpRequest->validate([
            'type' => 'required|in:repair,setup,purchase,other', // Тип может быть изменен, если нужно
            'description' => 'required|string|max:2000',
            'asset_id' => 'nullable|exists:assets,id',
            'priority' => 'required|in:low,medium,high,critical',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'assigned_to_id' => 'nullable|exists:users,id',
        ]);

        // Если статус меняется на 'completed', устанавливаем completed_at
        if ($validated['status'] === 'completed' && is_null($request->completed_at)) {
            $validated['completed_at'] = now();
        } elseif ($validated['status'] !== 'completed') {
            $validated['completed_at'] = null;
        }

        $request->update($validated);

        return redirect()->route('requests.index')->with('status', __('Request updated successfully!'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $this->authorize('delete_requests');

        $request->delete();

        return redirect()->route('requests.index')->with('status', __('Request deleted successfully!'));
    }
}
