<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\InvoiceService;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function __construct(protected InvoiceService $invoiceService) {}

    public function index(Request $request)
    {
        $invoices = auth()->user()->invoices()
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(15);

        return view('client.invoices.index', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        $this->authorize('view', $invoice);

        $invoice->load(['items', 'transactions', 'order']);

        return view('client.invoices.show', compact('invoice'));
    }

    public function download(Invoice $invoice)
    {
        $this->authorize('view', $invoice);

        $pdf = $this->invoiceService->generatePDF($invoice);

        return $pdf->download("invoice-{$invoice->invoice_number}.pdf");
    }

    public function pay(Request $request, Invoice $invoice)
    {
        $this->authorize('view', $invoice);

        if ($invoice->isPaid()) {
            return back()->with('error', 'Cette facture est déjà payée.');
        }

        $validated = $request->validate([
            'payment_method' => 'required|string',
        ]);

        try {
            $result = $this->invoiceService->pay($invoice, $validated['payment_method']);

            return redirect()->route('client.invoices.show', $invoice)
                ->with('success', 'Paiement effectué avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur de paiement : ' . $e->getMessage());
        }
    }
}
