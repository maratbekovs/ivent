<?php

namespace App\Http\Controllers;

use App\Models\AssetCategory;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Добавлено

class AssetCategoryController extends Controller
{
    use AuthorizesRequests; // Добавлено

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // Проверяем разрешение на просмотр категорий
        $this->authorize('view_assets'); // Или 'manage_asset_categories' если хотим более строгую проверку

        $categories = AssetCategory::latest()->paginate(10); // Получаем последние 10 категорий

        return view('asset_categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // Проверяем разрешение на создание категорий
        $this->authorize('manage_asset_categories');

        return view('asset_categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Проверяем разрешение на создание категорий
        $this->authorize('manage_asset_categories');

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:asset_categories',
            'description' => 'nullable|string|max:1000',
        ]);

        AssetCategory::create($validated);

        return redirect()->route('asset-categories.index')->with('status', __('Category created successfully!'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AssetCategory $assetCategory): View
    {
        // Проверяем разрешение на редактирование категорий
        $this->authorize('manage_asset_categories');

        return view('asset_categories.edit', ['category' => $assetCategory]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AssetCategory $assetCategory): RedirectResponse
    {
        // Проверяем разрешение на редактирование категорий
        $this->authorize('manage_asset_categories');

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:asset_categories,name,' . $assetCategory->id,
            'description' => 'nullable|string|max:1000',
        ]);

        $assetCategory->update($validated);

        return redirect()->route('asset-categories.index')->with('status', __('Category updated successfully!'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AssetCategory $assetCategory): RedirectResponse
    {
        // Проверяем разрешение на удаление категорий
        $this->authorize('manage_asset_categories');

        $assetCategory->delete();

        return redirect()->route('asset-categories.index')->with('status', __('Category deleted successfully!'));
    }
}
