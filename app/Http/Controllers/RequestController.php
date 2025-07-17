<?php

namespace App\Http\Controllers;

use App\Models\Request as ServiceRequest;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RequestController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('view_requests');
        // ИСПРАВЛЕНО: Используем правильную связь 'requester'
        $query = ServiceRequest::with(['asset', 'requester']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhereHas('asset', function ($assetQuery) use ($search) {
                      $assetQuery->where('serial_number', 'like', "%{$search}%")
                                 ->orWhere('inventory_number', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $requests = $query->latest()->paginate(20)->withQueryString();
        return view('requests.index', compact('requests'));
    }

    public function create()
    {
        $this->authorize('create_requests');
        $assets = Asset::all();
        return view('requests.create', compact('assets'));
    }

    public function store(Request $request)
    {
        $this->authorize('create_requests');
        $validated = $request->validate([
            'asset_id' => 'nullable|exists:assets,id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        // ИСПРАВЛЕНО: Используем 'requester_id' вместо 'user_id'
        $validated['requester_id'] = Auth::id();
        $validated['status'] = 'new';

        ServiceRequest::create($validated);
        return redirect()->route('requests.index')->with('success', 'Request created successfully.');
    }

    public function show(ServiceRequest $request)
    {
        $this->authorize('view_requests');
        // Убедимся, что модель передается с правильным именем
        return view('requests.show', ['serviceRequest' => $request]);
    }

    public function edit(ServiceRequest $request)
    {
        $this->authorize('edit_requests');
        // Убедимся, что модель передается с правильным именем
        return view('requests.edit', ['request' => $request]);
    }

    public function update(Request $requestData, ServiceRequest $request)
    {
        $this->authorize('edit_requests');
        $validated = $requestData->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:new,in_progress,completed,rejected',
        ]);
        $request->update($validated);
        return redirect()->route('requests.index')->with('success', 'Request updated successfully.');
    }

    public function destroy(ServiceRequest $request)
    {
        $this->authorize('delete_requests');
        $request->delete();
        return redirect()->route('requests.index')->with('success', 'Request deleted successfully.');
    }
}
