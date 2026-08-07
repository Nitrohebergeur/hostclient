<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;

/**
 * Gestion des factures du client.
 */
class InvoiceController extends Controller
{
    /**
     * Liste les factures du client.
     */
    public function index(Request $request): View
    {
        // $invoices = Invoice::where('user_id', auth()->id())
        //     ->latest()
        //     ->paginate(15);

        return view('client.invoices.index');
    }

    /**
     * Affiche une facture.
     */
    public function show(Request $request, string $invoice): View
    {
        // $invoice = Invoice::where('user_id', auth()->id())
        //     ->where('number', strtoupper($invoice))
        //     ->firstOrFail();

        return view('client.invoices.show');
    }

    /**
     * Télécharge la facture en PDF.
     */
    public function downloadPdf(Request $request, string $invoice): Response
    {
        // $invoice = Invoice::where('user_id', auth()->id())
        //     ->where('number', strtoupper($invoice))
        //     ->firstOrFail();

        // $pdf = Pdf::loadView('pdf.invoice', compact('invoice'));
        // return $pdf->download("facture-{$invoice->number}.pdf");

        abort(404, 'Fonctionnalité en cours d\'implémentation');
    }

    /**
     * Affiche la page de paiement d'une facture.
     */
    public function pay(Request $request, string $invoice): View
    {
        return view('client.invoices.pay');
    }

    /**
     * Traite le paiement d'une facture.
     */
    public function processPayment(Request $request, string $invoice): RedirectResponse
    {
        $request->validate([
            'payment_method' => ['required', 'string', 'in:stripe,paypal,mollie,crypto,bank_transfer'],
        ]);

        // PaymentService::process($invoice, $request->payment_method);

        return redirect()->route('client.invoices.show', $invoice)
            ->with('success', 'Paiement traité avec succès.');
    }
}
