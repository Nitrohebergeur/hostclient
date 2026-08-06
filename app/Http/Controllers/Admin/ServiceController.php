<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $services = Service::with(['user', 'product'])
            ->when($request->search, fn($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(20);

        return view('admin.services.index', compact('services'));
    }

    public function show(Service $service)
    {
        $service->load(['user', 'product', 'history', 'invoices']);

        return view('admin.services.show', compact('service'));
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'price'         => 'required|numeric|min:0',
            'billing_cycle' => 'required|string',
            'next_due_date' => 'nullable|date',
            'auto_renew'    => 'boolean',
            'notes'         => 'nullable|string',
        ]);

        $service->update($validated);

        return redirect()->route('admin.services.show', $service)
            ->with('success', 'Service mis à jour.');
    }

    public function activate(Service $service)
    {
        $service->activate();

        return back()->with('success', 'Service activé.');
    }

    public function suspend(Service $service)
    {
        $service->suspend(request('reason'));

        return back()->with('success', 'Service suspendu.');
    }

    public function terminate(Service $service)
    {
        $service->terminate(request('reason'));

        return back()->with('success', 'Service résilié.');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('admin.services.index')
            ->with('success', 'Service supprimé.');
    }
}
