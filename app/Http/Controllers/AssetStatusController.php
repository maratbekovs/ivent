<?php

namespace App\Http\Controllers;

use App\Models\AssetStatus;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Добавлено

class AssetStatusController extends Controller
{
    use AuthorizesRequests; // Добавлено

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // Проверяем разрешение на просмотр статусов
        $this->authorize('view_assets'); // Или 'manage_asset_statuses' если хотим более строгую проверку

        $statuses = AssetStatus::latest()->paginate(10); // Получаем последние 10 статусов

        return view('asset_statuses.index', compact('statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // Проверяем разрешение на создание статусов
        $this->authorize('manage_asset_statuses');

        return view('asset_statuses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Проверяем разрешение на создание статусов
        $this->authorize('manage_asset_statuses');

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:asset_statuses',
            'description' => 'nullable|string|max:1000',
        ]);

        AssetStatus::create($validated);

        return redirect()->route('asset-statuses.index')->with('status', __('Status created successfully!'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AssetStatus $assetStatus): View
    {
        // Проверяем разрешение на редактирование статусов
        $this->authorize('manage_asset_statuses');

        return view('asset_statuses.edit', ['status' => $assetStatus]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AssetStatus $assetStatus): RedirectResponse
    {
        // Проверяем разрешение на редактирование статусов
        $this->authorize('manage_asset_statuses');

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:asset_statuses,name,' . $assetStatus->id,
            'description' => 'nullable|string|max:1000',
        ]);

        $assetStatus->update($validated);

        return redirect()->route('asset-statuses.index')->with('status', __('Status updated successfully!'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AssetStatus $assetStatus): RedirectResponse
    {
        // Проверяем разрешение на удаление статусов
        $this->authorize('manage_asset_statuses');

        $assetStatus->delete();

        return redirect()->route('asset-statuses.index')->with('status', __('Status deleted successfully!'));
    }
}
