<?php

namespace App\Console\Commands;

use App\Models\Service;
use App\Services\InvoiceService;
use Illuminate\Console\Command;

class GenerateInvoicesCommand extends Command
{
    protected $signature   = 'invoices:generate';
    protected $description = 'Generate renewal invoices for services due soon';

    public function __construct(protected InvoiceService $invoiceService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $days     = config('hostclient.send_invoice_before_days', 7);
        $services = Service::active()
            ->where('auto_renew', true)
            ->where('next_invoice_date', '<=', now()->addDays($days))
            ->whereDoesntHave('invoices', fn($q) => $q->unpaid())
            ->with('user')
            ->get();

        $count = 0;

        foreach ($services as $service) {
            try {
                $invoice = $this->invoiceService->generateForService($service);
                $this->line("Invoice {$invoice->invoice_number} generated for service #{$service->id}");
                $count++;
            } catch (\Exception $e) {
                $this->error("Failed for service #{$service->id}: " . $e->getMessage());
            }
        }

        $this->info("Generated {$count} invoice(s).");

        return 0;
    }
}
