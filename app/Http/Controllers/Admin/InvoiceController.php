<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class InvoiceController extends Controller
{
    public function index(): View
    {
        return view('admin.invoices.index');
    }

    public function show(int $invoice): View
    {
        return view('admin.invoices.show');
    }

    public function create(): View
    {
        return view('admin.invoices.create');
    }

    public function store(Request $request): RedirectResponse
    {
        return redirect()->route('admin.invoices.index')->with('success', 'Facture créée.');
    }

    public function edit(int $invoice): View
    {
        return view('admin.invoices.edit');
    }

    public function update(Request $request, int $invoice): RedirectResponse
    {
        return back()->with('success', 'Facture mise à jour.');
    }

    public function destroy(int $invoice): RedirectResponse
    {
        return redirect()->route('admin.invoices.index')->with('success', 'Facture supprimée.');
    }

    public function markPaid(int $invoice): RedirectResponse
    {
        return back()->with('success', 'Facture marquée comme payée.');
    }

    public function pdf(int $invoice): Response
    {
        abort(501, 'PDF — en cours d\'implémentation');
    }
}
