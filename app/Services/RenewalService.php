<?php

namespace App\Services;

use App\Models\AutoRenewal;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Payment;
use App\Models\PaymentGateway;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RenewalService
{
    /**
     * Traiter tous les renouvellements dus aujourd'hui
     */
    public function processAllDueRenewals(): int
    {
        $processed = 0;

        // Services actifs dont la prochaine échéance est aujourd'hui ou dépassée
        $services = Service::with(['user', 'product', 'autoRenewal'])
            ->where('status', 'active')
            ->whereNotNull('next_due_date')
            ->where('next_due_date', '<=', now()->addDays(1))
            ->get();

        foreach ($services as $service) {
            try {
                $this->processServiceRenewal($service);
                $processed++;
            } catch (\Exception $e) {
                Log::error("Renewal failed for service #{$service->id}", [
                    'error' => $e->getMessage()
                ]);
            }
        }

        return $processed;
    }

    /**
     * Traiter le renouvellement d'un service
     */
    public function processServiceRenewal(Service $service): Invoice
    {
        return DB::transaction(function () use ($service) {
            $user    = $service->user;
            $product = $service->product;
            $renewal = $service->autoRenewal;

            // Créer la facture de renouvellement
            $invoice = Invoice::create([
                'user_id'   => $user->id,
                'number'    => Invoice::generateInvoiceNumber(),
                'status'    => 'pending',
                'issued_at' => now(),
                'due_at'    => now()->addDays(
                    (int) \App\Models\SystemSetting::get('invoice_due_days', 15)
                ),
                'subtotal'  => $service->price,
                'total'     => $service->price,
                'currency'  => $service->currency,
                'billing_address' => $this->buildBillingAddress($user),
            ]);

            InvoiceItem::create([
                'invoice_id'  => $invoice->id,
                'service_id'  => $service->id,
                'description' => "Renouvellement : {$service->name} ({$service->getBillingCycleLabel()})",
                'unit_price'  => $service->price,
                'quantity'    => 1,
                'discount'    => 0,
                'tax_rate'    => (float) \App\Models\SystemSetting::get('tax_rate', 0),
                'total'       => $service->price,
            ]);

            $invoice->calculateTotal();

            // Tentative de paiement automatique si renouvellement auto activé
            if ($renewal && $renewal->enabled) {
                $this->attemptAutoPayment($invoice, $renewal, $service);
            }

            // Envoyer notification email
            $this->sendRenewalNotification($user, $service, $invoice);

            Log::info("Renewal invoice created", [
                'service_id' => $service->id,
                'invoice_id' => $invoice->id,
                'amount'     => $invoice->total,
            ]);

            return $invoice;
        });
    }

    /**
     * Tenter un paiement automatique
     */
    protected function attemptAutoPayment(Invoice $invoice, AutoRenewal $renewal, Service $service): bool
    {
        if (!$renewal->canRetry()) {
            Log::warning("Max retry attempts reached for service #{$service->id}");
            return false;
        }

        // Essayer d'abord avec le crédit du compte
        $user = $service->user;
        if ($user->credit_balance >= $invoice->total) {
            $user->decrement('credit_balance', $invoice->total);

            Payment::create([
                'invoice_id'    => $invoice->id,
                'user_id'       => $user->id,
                'gateway'       => 'credit',
                'amount'        => $invoice->total,
                'currency'      => $invoice->currency,
                'status'        => 'completed',
                'processed_at'  => now(),
            ]);

            $invoice->markAsPaid();
            $this->activateRenewal($service, $invoice);
            $renewal->resetRetries();

            Log::info("Auto-renewal paid with credit for service #{$service->id}");
            return true;
        }

        // TODO: tentative via gateway configuré (Stripe, PayPal avec token sauvegardé)
        $renewal->incrementRetries();
        return false;
    }

    /**
     * Activer le renouvellement après paiement
     */
    public function activateRenewal(Service $service, Invoice $invoice): void
    {
        $newDueDate = $service->calculateNextDueDate($service->next_due_date ?? now());

        $service->update([
            'status'        => 'active',
            'next_due_date' => $newDueDate,
            'invoice_id'    => $invoice->id,
        ]);

        Log::info("Service #{$service->id} renewed until {$newDueDate->toDateString()}");
    }

    /**
     * Suspendre les services en retard
     */
    public function suspendOverdueServices(): int
    {
        $suspended = 0;
        $graceDays = (int) \App\Models\SystemSetting::get('auto_suspend_days', 3);

        $services = Service::where('status', 'active')
            ->whereNotNull('next_due_date')
            ->where('next_due_date', '<=', now()->subDays($graceDays))
            ->get();

        foreach ($services as $service) {
            $service->suspend();
            $suspended++;
            Log::info("Service #{$service->id} auto-suspended (overdue by {$graceDays}+ days)");
        }

        return $suspended;
    }

    /**
     * Terminer les services suspendus depuis trop longtemps
     */
    public function terminateExpiredServices(): int
    {
        $terminated = 0;
        $terminateDays = (int) \App\Models\SystemSetting::get('auto_terminate_days', 30);

        $services = Service::where('status', 'suspended')
            ->whereNotNull('next_due_date')
            ->where('next_due_date', '<=', now()->subDays($terminateDays))
            ->get();

        foreach ($services as $service) {
            $service->terminate();
            $terminated++;
            Log::info("Service #{$service->id} auto-terminated ({$terminateDays}+ days suspended)");
        }

        return $terminated;
    }

    /**
     * Envoyer les rappels de paiement
     */
    public function sendPaymentReminders(): void
    {
        $reminderDays = [7, 3, 1, 0, -1, -3, -7]; // jours avant/après échéance

        foreach ($reminderDays as $days) {
            $targetDate = $days >= 0
                ? now()->addDays($days)->toDateString()
                : now()->subDays(abs($days))->toDateString();

            $invoices = Invoice::with('user')
                ->where('status', 'pending')
                ->whereDate('due_at', $targetDate)
                ->get();

            foreach ($invoices as $invoice) {
                // Vérifier qu'on n'a pas déjà envoyé ce rappel
                $alreadySent = \App\Models\PaymentReminder::where([
                    'invoice_id'     => $invoice->id,
                    'days_before_due' => $days,
                    'status'          => 'sent',
                ])->exists();

                if (!$alreadySent) {
                    $this->sendReminderEmail($invoice, $days);
                }
            }
        }
    }

    protected function sendRenewalNotification(User $user, Service $service, Invoice $invoice): void
    {
        // Notification in-app (logs ici, implémenter Mail/Notification selon setup)
        Log::info("Renewal notification sent to {$user->email} for service #{$service->id}");
    }

    protected function sendReminderEmail(Invoice $invoice, int $days): void
    {
        \App\Models\PaymentReminder::create([
            'invoice_id'      => $invoice->id,
            'user_id'         => $invoice->user_id,
            'days_before_due' => $days,
            'channel'         => 'email',
            'status'          => 'sent',
            'sent_at'         => now(),
        ]);

        Log::info("Payment reminder sent for invoice #{$invoice->id} ({$days} days)");
    }

    protected function buildBillingAddress(User $user): array
    {
        return [
            'name'     => $user->name,
            'company'  => $user->company,
            'address1' => $user->address1,
            'address2' => $user->address2,
            'city'     => $user->city,
            'state'    => $user->state,
            'postcode' => $user->postcode,
            'country'  => $user->country,
            'vat'      => $user->vat_number,
        ];
    }
}
