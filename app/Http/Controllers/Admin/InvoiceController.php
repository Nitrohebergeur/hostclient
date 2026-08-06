<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $invoices = Invoice::with('user')
            ->when($request->search, fn($q, $s) => $q->where('invoice_number', 'like', "%{$s}%"))
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(20);

        return view('admin.invoices.index', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['user', 'items', 'transactions', 'order']);

        return view('admin.invoices.show', compact('invoice'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'status'   => 'required|in:draft,unpaid,paid,cancelled,refunded,partially_paid',
            'due_date' => 'nullable|date',
            'notes'    => 'nullable|string',
        ]);

        $invoice->update($validated);

        return redirect()->route('admin.invoices.show', $invoice)
            ->with('success', 'Facture mise à jour.');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return redirect()->route('admin.invoices.index')
            ->with('success', 'Facture supprimée.');
    }
}
