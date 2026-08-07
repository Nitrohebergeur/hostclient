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

namespace App\Console\Commands;

use App\Models\Billing\Invoice;
use Illuminate\Console\Command;

class DefineBillingAddressToInvoicesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Hostclient:define-billing-address-to-invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Define billing address to invoices';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $invoices = \App\Models\Billing\Invoice::whereNull('billing_address')->get();
        if ($invoices->isEmpty()) {
            $this->info('No invoices found without billing address.');

            return;
        }
        /** @var Invoice $invoice */
        foreach ($invoices as $invoice) {
            $customer = $invoice->customer;
            if ($customer) {
                $invoice->billing_address = $customer->generateBillingAddress();
                if ($invoice->save()) {
                    $invoice->generatePdf();
                    $this->info("Billing address set and PDF generated for invoice #{$invoice->id}");
                }
                $invoice->save();
                $this->info("Billing address set for invoice #{$invoice->id}");
            } else {
                $this->warn("No billing address found for customer of invoice #{$invoice->id}");
            }
        }
    }
}
