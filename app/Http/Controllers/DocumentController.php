<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Asset;
use App\Models\AssetStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('view_documents');
        // Используем creator, так как мы исправили модель
        $documents = Document::with('creator')->latest()->paginate(20);
        return view('documents.index', compact('documents'));
    }

    public function create()
    {
        $this->authorize('create_documents');
        // Получаем только активы, которые еще не списаны
        $assets = Asset::whereHas('status', function ($query) {
            $query->where('name', '!=', 'Written Off');
        })->orderBy('inventory_number')->get();
        return view('documents.create', compact('assets'));
    }

    public function store(Request $request)
    {
        $this->authorize('create_documents');

        $validated = $request->validate([
            'type' => 'required|string|in:write_off_act,acceptance_act,warranty,other',
            'title' => 'required_if:type,warranty,other|nullable|string|max:255',
            'file' => 'required_if:type,warranty,other|nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
            'asset_ids' => 'required_if:type,write_off_act|array',
            'asset_ids.*' => 'exists:assets,id',
            'reason' => 'required_if:type,write_off_act|nullable|string',
            'commission' => 'required_if:type,write_off_act|nullable|string',
        ]);

        $documentData = [
            'type' => $validated['type'],
            'creator_id' => Auth::id(),
        ];

        // Логика для генерации "Акта на списание"
        if ($validated['type'] === 'write_off_act') {
            $commissionMembers = array_map('trim', explode(',', $validated['commission']));
            
            $documentData['title'] = 'Акт на списание №' . Str::uuid()->getHex()->toString();
            $documentData['reason'] = $validated['reason'];
            $documentData['commission_data'] = [
                'chairman' => $commissionMembers[0] ?? '',
                'members' => implode(', ', array_slice($commissionMembers, 1)),
            ];

            $document = Document::create($documentData);
            $document->assets()->attach($validated['asset_ids']);
            
            // Загружаем данные для PDF
            $document->load('assets.category');
            
            // Генерируем PDF
            $pdf = Pdf::loadView('pdf.write_off_act', [
                'document' => $document,
                'commission' => $document->commission_data,
            ]);
            
            $filename = 'write-off-act-' . $document->id . '.pdf';
            $filePath = 'documents/' . $filename;
            Storage::disk('public')->put($filePath, $pdf->output());

            // Обновляем путь к файлу и меняем статус активов
            $document->update(['file_path' => $filePath]);
            $statusWrittenOff = AssetStatus::where('name', 'Written Off')->first();
            if ($statusWrittenOff) {
                Asset::whereIn('id', $validated['asset_ids'])->update(['asset_status_id' => $statusWrittenOff->id]);
            }

        } else { // Логика для других типов документов (загрузка файла)
            $documentData['title'] = $validated['title'];
            if ($request->hasFile('file')) {
                $documentData['file_path'] = $request->file('file')->store('documents', 'public');
            }
            Document::create($documentData);
        }

        return redirect()->route('documents.index')->with('success', 'Document processed successfully.');
    }
    
    // Метод для скачивания сгенерированных документов
    public function download(Document $document)
    {
        $this->authorize('view_documents');

        if (!$document->file_path || !Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'File not found.');
        }

        return Storage::disk('public')->download($document->file_path);
    }

    public function destroy(Document $document)
    {
        $this->authorize('delete_documents');
        if ($document->file_path) {
            Storage::disk('public')->delete($document->file_path);
        }
        $document->delete();
        return redirect()->route('documents.index')->with('success', 'Document deleted successfully.');
    }
}
