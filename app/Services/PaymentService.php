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
        $clientId   = config('services.paypal.client_id');
        $secret     = config('services.paypal.secret');
        $isLive     = config('services.paypal.mode', 'sandbox') === 'live';
        $baseUrl    = $isLive ? 'https://api-m.paypal.com' : 'https://api-m.sandbox.paypal.com';

        if (!$clientId || !$secret) {
            throw new \Exception('PayPal non configuré (client_id / secret manquants).');
        }

        // 1. Obtenir un access token (OAuth2 Client Credentials)
        $tokenResponse = \Illuminate\Support\Facades\Http::asForm()
            ->withBasicAuth($clientId, $secret)
            ->post($baseUrl . '/v1/oauth2/token', [
                'grant_type' => 'client_credentials',
            ]);

        if (!$tokenResponse->successful()) {
            throw new \Exception('PayPal: impossible d\'obtenir le token (' . $tokenResponse->status() . ').');
        }

        $accessToken = $tokenResponse->json('access_token');

        // 2. Créer la commande PayPal
        $orderResponse = \Illuminate\Support\Facades\Http::withToken($accessToken)
            ->post($baseUrl . '/v2/checkout/orders', [
                'intent'              => 'CAPTURE',
                'purchase_units'      => [[
                    'reference_id' => $order->order_number,
                    'custom_id'    => (string) $order->id,
                    'description'  => "Commande #{$order->order_number}",
                    'amount'       => [
                        'currency_code' => $order->currency,
                        'value'         => number_format($order->total, 2, '.', ''),
                    ],
                ]],
                'application_context' => [
                    'return_url' => route('checkout.success', ['order' => $order->id]),
                    'cancel_url' => route('checkout.cancel', ['order' => $order->id]),
                    'brand_name' => config('hostclient.company_name', 'HostClient'),
                    'user_action' => 'PAY_NOW',
                ],
            ]);

        if (!$orderResponse->successful()) {
            Log::error('PayPal create order failed', [
                'status' => $orderResponse->status(),
                'body'   => $orderResponse->body(),
            ]);
            throw new \Exception('PayPal: création de commande échouée (' . $orderResponse->status() . ').');
        }

        $approvalLink = collect($orderResponse->json('links'))
            ->firstWhere('rel', 'approve')['href'] ?? null;

        if (!$approvalLink) {
            throw new \Exception('PayPal: lien d\'approbation manquant.');
        }

        return $approvalLink;
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

    /**
     * Capture un paiement PayPal après approbation de l'utilisateur.
     * Appelé par le contrôleur de retour (return URL).
     */
    public function capturePaypalOrder(string $paypalOrderId): bool
    {
        $clientId   = config('services.paypal.client_id');
        $secret     = config('services.paypal.secret');
        $isLive     = config('services.paypal.mode', 'sandbox') === 'live';
        $baseUrl    = $isLive ? 'https://api-m.paypal.com' : 'https://api-m.sandbox.paypal.com';

        if (!$clientId || !$secret) {
            throw new \Exception('PayPal non configuré.');
        }

        $tokenResponse = \Illuminate\Support\Facades\Http::asForm()
            ->withBasicAuth($clientId, $secret)
            ->post($baseUrl . '/v1/oauth2/token', ['grant_type' => 'client_credentials']);

        if (!$tokenResponse->successful()) {
            throw new \Exception('PayPal: token impossible.');
        }

        $captureResponse = \Illuminate\Support\Facades\Http::withToken($tokenResponse->json('access_token'))
            ->post($baseUrl . "/v2/checkout/orders/{$paypalOrderId}/capture");

        return $captureResponse->successful()
            && ($captureResponse->json('status') === 'COMPLETED');
    }
}
