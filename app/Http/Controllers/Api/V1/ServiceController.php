<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $services = $request->user()
            ->services()
            ->with('product')
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(20);

        return response()->json($services);
    }

    public function show(Service $service)
    {
        $this->authorize('view', $service);

        $service->load(['product', 'invoices', 'history']);

        return response()->json($service);
    }

    public function update(Request $request, Service $service)
    {
        $this->authorize('update', $service);

        $validated = $request->validate([
            'auto_renew' => 'boolean',
        ]);

        $service->update($validated);

        return response()->json($service);
    }

    public function destroy(Service $service)
    {
        $this->authorize('delete', $service);

        if (!$service->isTerminated()) {
            return response()->json([
                'message' => 'Seuls les services résiliés peuvent être supprimés.',
            ], 422);
        }

        $service->delete();

        return response()->json(['message' => 'Service supprimé.']);
    }

    public function renew(Service $service)
    {
        $this->authorize('view', $service);

        // Logic to create renewal invoice
        $invoiceService = app(\App\Services\InvoiceService::class);
        $invoice        = $invoiceService->generateForService($service);

        return response()->json([
            'message' => 'Facture de renouvellement créée.',
            'invoice' => $invoice,
        ]);
    }

    public function cancel(Service $service)
    {
        $this->authorize('view', $service);

        $service->update(['auto_renew' => false]);
        $service->addHistory('cancelled', 'Renouvellement automatique annulé par le client');

        return response()->json([
            'message' => 'Renouvellement automatique annulé.',
            'service' => $service,
        ]);
    }
}
