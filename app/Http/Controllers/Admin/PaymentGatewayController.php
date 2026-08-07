<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;

class PaymentGatewayController extends Controller
{
    public function index()
    {
        $gateways = PaymentGateway::orderBy('order')->get();

        return view('admin.payment-gateways.index', compact('gateways'));
    }

    public function create()
    {
        $availableGateways = [
            'stripe' => [
                'name' => 'Stripe',
                'description' => 'Accept credit cards and other payment methods',
                'fields' => ['api_key' => 'Publishable Key', 'api_secret' => 'Secret Key', 'webhook_secret' => 'Webhook Secret'],
                'supports_recurring' => true,
                'supports_refunds' => true,
                'supports_webhooks' => true,
            ],
            'paypal' => [
                'name' => 'PayPal',
                'description' => 'Accept PayPal payments',
                'fields' => ['client_id' => 'Client ID', 'client_secret' => 'Client Secret', 'mode' => 'Mode (sandbox/live)'],
                'supports_recurring' => true,
                'supports_refunds' => true,
                'supports_webhooks' => true,
            ],
            'mollie' => [
                'name' => 'Mollie',
                'description' => 'Accept various European payment methods',
                'fields' => ['api_key' => 'API Key'],
                'supports_recurring' => true,
                'supports_refunds' => true,
                'supports_webhooks' => true,
            ],
            'coinbase' => [
                'name' => 'Coinbase Commerce',
                'description' => 'Accept cryptocurrency payments',
                'fields' => ['api_key' => 'API Key', 'webhook_secret' => 'Webhook Secret'],
                'supports_recurring' => false,
                'supports_refunds' => false,
                'supports_webhooks' => true,
            ],
            'razorpay' => [
                'name' => 'Razorpay',
                'description' => 'Accept payments in India',
                'fields' => ['api_key' => 'Key ID', 'api_secret' => 'Key Secret', 'webhook_secret' => 'Webhook Secret'],
                'supports_recurring' => true,
                'supports_refunds' => true,
                'supports_webhooks' => true,
            ],
            'bank_transfer' => [
                'name' => 'Bank Transfer',
                'description' => 'Manual bank transfer',
                'fields' => ['bank_name' => 'Bank Name', 'account_number' => 'Account Number', 'iban' => 'IBAN', 'swift' => 'SWIFT/BIC'],
                'supports_recurring' => false,
                'supports_refunds' => false,
                'supports_webhooks' => false,
            ],
            'credit' => [
                'name' => 'Account Credit',
                'description' => 'Pay using account balance',
                'fields' => [],
                'supports_recurring' => true,
                'supports_refunds' => false,
                'supports_webhooks' => false,
            ],
        ];

        return view('admin.payment-gateways.create', compact('availableGateways'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:payment_gateways,slug',
            'description' => 'nullable|string',
            'logo' => 'nullable|string',
            'is_active' => 'boolean',
            'order' => 'nullable|integer',
            'config' => 'nullable|array',
            'supported_currencies' => 'nullable|array',
            'fee_fixed' => 'nullable|numeric|min:0',
            'fee_percentage' => 'nullable|numeric|min:0|max:100',
            'supports_recurring' => 'boolean',
            'supports_refunds' => 'boolean',
            'supports_webhooks' => 'boolean',
        ]);

        PaymentGateway::create($validated);

        return redirect()
            ->route('admin.payment-gateways.index')
            ->with('success', 'Payment gateway created successfully.');
    }

    public function edit(PaymentGateway $paymentGateway)
    {
        return view('admin.payment-gateways.edit', compact('paymentGateway'));
    }

    public function update(Request $request, PaymentGateway $paymentGateway)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:payment_gateways,slug,' . $paymentGateway->id,
            'description' => 'nullable|string',
            'logo' => 'nullable|string',
            'is_active' => 'boolean',
            'order' => 'nullable|integer',
            'config' => 'nullable|array',
            'supported_currencies' => 'nullable|array',
            'fee_fixed' => 'nullable|numeric|min:0',
            'fee_percentage' => 'nullable|numeric|min:0|max:100',
            'supports_recurring' => 'boolean',
            'supports_refunds' => 'boolean',
            'supports_webhooks' => 'boolean',
        ]);

        // Fusionner la nouvelle config avec l'ancienne pour ne pas perdre les clés non modifiées
        if (isset($validated['config'])) {
            $currentConfig = $paymentGateway->config ?? [];
            $validated['config'] = array_merge($currentConfig, array_filter($validated['config']));
        }

        $paymentGateway->update($validated);

        return redirect()
            ->route('admin.payment-gateways.index')
            ->with('success', 'Payment gateway updated successfully.');
    }

    public function destroy(PaymentGateway $paymentGateway)
    {
        $paymentGateway->delete();

        return redirect()
            ->route('admin.payment-gateways.index')
            ->with('success', 'Payment gateway deleted successfully.');
    }

    public function toggle(PaymentGateway $paymentGateway)
    {
        $paymentGateway->update([
            'is_active' => !$paymentGateway->is_active,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Payment gateway status updated.');
    }
}
