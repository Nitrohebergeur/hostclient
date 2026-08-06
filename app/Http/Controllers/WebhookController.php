<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function stripe(Request $request): Response
    {
        $payload   = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret    = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\Exception $e) {
            Log::error('Stripe webhook error: ' . $e->getMessage());

            return response('Invalid signature', 400);
        }

        match ($event->type) {
            'payment_intent.succeeded'        => $this->handleStripePaymentSucceeded($event->data->object),
            'payment_intent.payment_failed'   => $this->handleStripePaymentFailed($event->data->object),
            default                           => null,
        };

        return response('OK', 200);
    }

    public function paypal(Request $request): Response
    {
        $payload = $request->all();

        Log::info('PayPal webhook received', $payload);

        if (($payload['event_type'] ?? '') === 'PAYMENT.CAPTURE.COMPLETED') {
            $orderId = $payload['resource']['custom_id'] ?? null;

            if ($orderId) {
                $order = Order::find($orderId);
                $order?->markAsCompleted();
            }
        }

        return response('OK', 200);
    }

    public function mollie(Request $request): Response
    {
        $paymentId = $request->input('id');

        if (!$paymentId) {
            return response('Missing payment ID', 400);
        }

        try {
            $mollie  = app(\Mollie\Api\MollieApiClient::class);
            $payment = $mollie->payments->get($paymentId);

            if ($payment->isPaid()) {
                $orderId = $payment->metadata->order_id ?? null;
                $order   = Order::find($orderId);
                $order?->markAsCompleted();
            }
        } catch (\Exception $e) {
            Log::error('Mollie webhook error: ' . $e->getMessage());
        }

        return response('OK', 200);
    }

    protected function handleStripePaymentSucceeded($paymentIntent): void
    {
        $orderId = $paymentIntent->metadata['order_id'] ?? null;
        $order   = Order::find($orderId);
        $order?->markAsCompleted();
    }

    protected function handleStripePaymentFailed($paymentIntent): void
    {
        $orderId = $paymentIntent->metadata['order_id'] ?? null;
        $order   = Order::find($orderId);
        $order?->update(['status' => 'cancelled']);
    }
}
