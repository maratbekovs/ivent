<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Asset;    // Для выпадающего списка
use App\Models\Request as RequestModel;  // Для выпадающего списка, используем псевдоним
use Illuminate\Http\Request as HttpRequest; // Добавлено: используем псевдоним для Illuminate\Http\Request
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth; // Для получения текущего пользователя

class DocumentController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $this->authorize('view_documents');

        // Загружаем связанные данные для отображения в таблице
        $documents = Document::with(['asset', 'relatedRequest', 'creator', 'signedBy'])
                             ->latest()
                             ->paginate(10);

        return view('documents.index', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorize('create_documents');

        $assets = Asset::all();
        $requests = RequestModel::all(); // Используем псевдоним RequestModel

        return view('documents.create', compact('assets', 'requests'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HttpRequest $request): RedirectResponse // Используем HttpRequest
    {
        $this->authorize('create_documents');

        $validated = $request->validate([
            'document_type' => 'required|in:acceptance,disposal,repair_request,inventory_order',
            'asset_id' => 'nullable|exists:assets,id',
            'related_request_id' => 'nullable|exists:requests,id',
            'notes' => 'nullable|string|max:2000',
            // file_path будет генерироваться позже, пока не требуется в форме
        ]);

        $validated['creator_id'] = Auth::id();
        // file_path пока оставим пустым, позже добавим логику генерации PDF/DOCX

        Document::create($validated);

        return redirect()->route('documents.index')->with('status', __('Document created successfully!'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document): View
    {
        $this->authorize('edit_documents');

        $assets = Asset::all();
        $requests = RequestModel::all(); // Используем псевдоним RequestModel

        return view('documents.edit', compact('document', 'assets', 'requests'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HttpRequest $request, Document $document): RedirectResponse // Используем HttpRequest
    {
        $this->authorize('edit_documents');

        $validated = $request->validate([
            'document_type' => 'required|in:acceptance,disposal,repair_request,inventory_order',
            'asset_id' => 'nullable|exists:assets,id',
            'related_request_id' => 'nullable|exists:requests,id',
            'notes' => 'nullable|string|max:2000',
        ]);

        $document->update($validated);

        return redirect()->route('documents.index')->with('status', __('Document updated successfully!'));
    }

    /**
     * Mark the specified document as signed.
     */
    public function sign(Document $document): RedirectResponse
    {
        $this->authorize('sign_documents');

        if (!$document->signed_at) {
            $document->update([
                'signed_by_user_id' => Auth::id(),
                'signed_at' => now(),
            ]);
            return redirect()->route('documents.index')->with('status', __('Document signed successfully!'));
        }

        return redirect()->route('documents.index')->with('error', __('Document is already signed.'));
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document): RedirectResponse
    {
        $this->authorize('delete_documents');

        $document->delete();

        return redirect()->route('documents.index')->with('status', __('Document deleted successfully!'));
    }
}
