<?php

/*
 * This file is part of the Hostclient project.
 * It is the property of the Hostclient association.
 *
 * Personal and non-commercial use of this source code is permitted.
 * However, any use in a project that generates profit (directly or indirectly),
 * or any reuse for commercial purposes, requires prior authorization from Hostclient.
 *
 * To request permission or for more information, please contact our support:
 * https://Hostclient.com/client/support
 *
 * Learn more about Hostclient License at:
 * https://Hostclient.com/eula
 *
 * Year: 2025
 */

namespace App\Console\Commands\Purge;

use App\Models\Billing\Invoice;
use Illuminate\Console\Command;

class InvoiceDeleteCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Hostclient:invoice-delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete pending invoices older than a specified number of days.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Running services:expire at '.now()->format('Y-m-d H:i:s'));
        $this->info('delete pending invoice...');
        $active = setting('remove_pending_invoice_type', 'cancel');
        $days = setting('remove_pending_invoice', 0);
        if ($days <= 0) {
            $this->info('Auto delete is disabled.');

            return;
        }
        $invoices = Invoice::whereIn('status', [Invoice::STATUS_PENDING, Invoice::STATUS_CANCELLED])->where('created_at', '<', now()->subDays($days))->get();
        foreach ($invoices as $invoice) {
            if ($active === 'delete') {
                $invoice->items()->delete();
                $invoice->delete();
                $this->info('Invoice #'.$invoice->id.' deleted.');
            } else {
                $invoice->cancel();
                $this->info('Invoice #'.$invoice->id.' canceled.');
            }
        }
    }
}
