<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function process(Order $order, string $method): ?string
    {
        return match ($method) {
            'stripe'  => $this->processStripe($order),
            'paypal'  => $this->processPaypal($order),
            'mollie'  => $this->processMollie($order),
            'balance' => $this->processBalance($order),
            default   => throw new \InvalidArgumentException("Unknown payment method: {$method}"),
        };
    }

    public function processInvoice(Invoice $invoice, string $method): bool
    {
        $user = $invoice->user;

        if ($method === 'balance') {
            if (!$user->hasEnoughBalance($invoice->balance)) {
                throw new \Exception('Solde insuffisant.');
            }

            $user->deductBalance($invoice->balance);
            $invoice->markAsPaid();

            Transaction::create([
                'transaction_id' => Transaction::generateId(),
                'user_id'        => $user->id,
                'invoice_id'     => $invoice->id,
                'type'           => 'payment',
                'status'         => 'completed',
                'amount'         => $invoice->balance,
                'currency'       => $invoice->currency,
                'payment_method' => 'balance',
            ]);

            return true;
        }

        return false;
    }

    protected function processStripe(Order $order): string
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items'           => $this->buildStripeLineItems($order),
            'mode'                 => 'payment',
            'success_url'          => route('client.orders.show', $order) . '?payment=success',
            'cancel_url'           => route('store.cart'),
            'metadata'             => ['order_id' => $order->id],
        ]);

        return $session->url;
    }

    protected function processPaypal(Order $order): string
    {
        // PayPal redirect URL would be generated here
        // For now return a placeholder
        throw new \Exception('PayPal not configured.');
    }

    protected function processMollie(Order $order): string
    {
        $mollie  = app(\Mollie\Api\MollieApiClient::class);

        $payment = $mollie->payments->create([
            'amount'      => ['currency' => $order->currency, 'value' => number_format($order->total, 2, '.', '')],
            'description' => "Commande #{$order->order_number}",
            'redirectUrl' => route('client.orders.show', $order),
            'webhookUrl'  => route('webhooks.mollie'),
            'metadata'    => ['order_id' => $order->id],
        ]);

        return $payment->getCheckoutUrl();
    }

    protected function processBalance(Order $order): ?string
    {
        $user = $order->user;

        if (!$user->hasEnoughBalance($order->total)) {
            throw new \Exception('Solde insuffisant.');
        }

        $user->deductBalance($order->total);
        $order->markAsCompleted();

        Transaction::create([
            'transaction_id' => Transaction::generateId(),
            'user_id'        => $user->id,
            'type'           => 'payment',
            'status'         => 'completed',
            'amount'         => $order->total,
            'currency'       => $order->currency,
            'payment_method' => 'balance',
        ]);

        return null;
    }

    protected function buildStripeLineItems(Order $order): array
    {
        return $order->items->map(fn($item) => [
            'price_data' => [
                'currency'     => strtolower($order->currency),
                'product_data' => ['name' => $item->name],
                'unit_amount'  => (int) ($item->unit_price * 100),
            ],
            'quantity' => $item->quantity,
        ])->toArray();
    }
}
