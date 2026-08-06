<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $services = auth()->user()->services()
            ->with('product')
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(12);

        return view('client.services.index', compact('services'));
    }

    public function show(Service $service)
    {
        $this->authorize('view', $service);

        $service->load(['product', 'invoices', 'history']);

        return view('client.services.show', compact('service'));
    }

    public function create()
    {
        return redirect()->route('store.index');
    }

    public function store(Request $request)
    {
        return redirect()->route('store.index');
    }

    public function update(Request $request, Service $service)
    {
        $this->authorize('update', $service);

        $validated = $request->validate([
            'auto_renew' => 'boolean',
        ]);

        $service->update($validated);

        return back()->with('success', 'Service mis à jour.');
    }

    public function destroy(Service $service)
    {
        $this->authorize('delete', $service);

        if (!$service->isTerminated()) {
            return back()->with('error', 'Seuls les services résiliés peuvent être supprimés.');
        }

        $service->delete();

        return redirect()->route('client.services.index')
            ->with('success', 'Service supprimé.');
    }
}
