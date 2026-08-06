<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\InvoiceService;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function __construct(protected InvoiceService $invoiceService) {}

    public function index(Request $request)
    {
        $invoices = $request->user()
            ->invoices()
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(20);

        return response()->json($invoices);
    }

    public function show(Invoice $invoice)
    {
        $this->authorize('view', $invoice);

        $invoice->load(['items', 'transactions', 'order']);

        return response()->json($invoice);
    }

    public function pay(Request $request, Invoice $invoice)
    {
        $this->authorize('view', $invoice);

        if ($invoice->isPaid()) {
            return response()->json([
                'message' => 'Cette facture est déjà payée.',
            ], 422);
        }

        $validated = $request->validate([
            'payment_method' => 'required|string',
        ]);

        try {
            $result = $this->invoiceService->pay($invoice, $validated['payment_method']);

            return response()->json([
                'message' => 'Paiement effectué avec succès.',
                'invoice' => $invoice->fresh(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur de paiement : ' . $e->getMessage(),
            ], 422);
        }
    }

    public function download(Invoice $invoice)
    {
        $this->authorize('view', $invoice);

        $pdf = $this->invoiceService->generatePDF($invoice);

        return $pdf->download("invoice-{$invoice->invoice_number}.pdf");
    }
}
