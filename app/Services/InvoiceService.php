<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Service;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceService
{
    public function generateForService(Service $service): Invoice
    {
        $invoice = Invoice::create([
            'invoice_number' => $this->generateNumber(),
            'user_id'        => $service->user_id,
            'status'         => 'unpaid',
            'issue_date'     => now(),
            'due_date'       => now()->addDays(config('hostclient.invoice_due_days', 14)),
            'subtotal'       => $service->price,
            'tax_rate'       => config('hostclient.tax_rate', 0),
            'tax'            => $service->price * (config('hostclient.tax_rate', 0) / 100),
            'discount'       => 0,
            'currency'       => config('hostclient.currency', 'EUR'),
        ]);

        $invoice->items()->create([
            'service_id'  => $service->id,
            'description' => "Renouvellement : {$service->name}",
            'quantity'    => 1,
            'unit_price'  => $service->price,
            'amount'      => $service->price,
        ]);

        $invoice->update([
            'total'   => $invoice->subtotal + $invoice->tax,
            'balance' => $invoice->subtotal + $invoice->tax,
        ]);

        // Update service dates
        $service->update([
            'next_invoice_date' => now()->addMonth()->subDays(7),
            'next_due_date'     => now()->addMonth(),
        ]);

        return $invoice;
    }

    public function pay(Invoice $invoice, string $method): bool
    {
        $paymentService = app(PaymentService::class);

        return $paymentService->processInvoice($invoice, $method);
    }

    public function generatePDF(Invoice $invoice)
    {
        $invoice->load(['user', 'items', 'order']);

        return Pdf::loadView('pdf.invoice', compact('invoice'))
            ->setPaper('a4', 'portrait');
    }

    public function generateNumber(): string
    {
        $prefix = config('hostclient.invoice_prefix', 'INV-');
        $year   = date('Y');
        $count  = Invoice::whereYear('created_at', $year)->count() + 1;

        return $prefix . $year . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
    }
}
