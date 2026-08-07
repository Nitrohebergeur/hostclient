<?php

namespace Database\Seeders;

use App\Models\PaymentGateway;
use Illuminate\Database\Seeder;

class PaymentGatewaySeeder extends Seeder
{
    public function run(): void
    {
        $gateways = [
            [
                'name' => 'Stripe',
                'slug' => 'stripe',
                'description' => 'Accept credit cards and other payment methods with Stripe',
                'logo' => 'fab fa-stripe',
                'is_active' => false,
                'order' => 1,
                'config' => [
                    'api_key' => '',
                    'api_secret' => '',
                    'webhook_secret' => '',
                ],
                'supported_currencies' => ['USD', 'EUR', 'GBP', 'CAD', 'AUD'],
                'fee_fixed' => 0.30,
                'fee_percentage' => 2.9,
                'supports_recurring' => true,
                'supports_refunds' => true,
                'supports_webhooks' => true,
            ],
            [
                'name' => 'PayPal',
                'slug' => 'paypal',
                'description' => 'Accept payments via PayPal',
                'logo' => 'fab fa-paypal',
                'is_active' => false,
                'order' => 2,
                'config' => [
                    'client_id' => '',
                    'client_secret' => '',
                    'mode' => 'sandbox',
                ],
                'supported_currencies' => ['USD', 'EUR', 'GBP', 'CAD', 'AUD'],
                'fee_fixed' => 0.30,
                'fee_percentage' => 2.9,
                'supports_recurring' => true,
                'supports_refunds' => true,
                'supports_webhooks' => true,
            ],
            [
                'name' => 'Mollie',
                'slug' => 'mollie',
                'description' => 'Accept various European payment methods',
                'logo' => 'fas fa-credit-card',
                'is_active' => false,
                'order' => 3,
                'config' => [
                    'api_key' => '',
                ],
                'supported_currencies' => ['EUR', 'USD', 'GBP'],
                'fee_fixed' => 0.25,
                'fee_percentage' => 1.8,
                'supports_recurring' => true,
                'supports_refunds' => true,
                'supports_webhooks' => true,
            ],
            [
                'name' => 'Coinbase Commerce',
                'slug' => 'coinbase',
                'description' => 'Accept cryptocurrency payments',
                'logo' => 'fab fa-bitcoin',
                'is_active' => false,
                'order' => 4,
                'config' => [
                    'api_key' => '',
                    'webhook_secret' => '',
                ],
                'supported_currencies' => ['BTC', 'ETH', 'USDC', 'USDT'],
                'fee_fixed' => 0,
                'fee_percentage' => 1.0,
                'supports_recurring' => false,
                'supports_refunds' => false,
                'supports_webhooks' => true,
            ],
            [
                'name' => 'Razorpay',
                'slug' => 'razorpay',
                'description' => 'Accept payments in India',
                'logo' => 'fas fa-credit-card',
                'is_active' => false,
                'order' => 5,
                'config' => [
                    'api_key' => '',
                    'api_secret' => '',
                    'webhook_secret' => '',
                ],
                'supported_currencies' => ['INR'],
                'fee_fixed' => 0,
                'fee_percentage' => 2.0,
                'supports_recurring' => true,
                'supports_refunds' => true,
                'supports_webhooks' => true,
            ],
            [
                'name' => 'Bank Transfer',
                'slug' => 'bank_transfer',
                'description' => 'Manual bank transfer payments',
                'logo' => 'fas fa-university',
                'is_active' => true,
                'order' => 6,
                'config' => [
                    'bank_name' => 'Example Bank',
                    'account_number' => '1234567890',
                    'iban' => 'DE89370400440532013000',
                    'swift' => 'COBADEFFXXX',
                    'instructions' => 'Please include your invoice number in the transfer reference.',
                ],
                'supported_currencies' => [],
                'fee_fixed' => 0,
                'fee_percentage' => 0,
                'supports_recurring' => false,
                'supports_refunds' => false,
                'supports_webhooks' => false,
            ],
            [
                'name' => 'Account Credit',
                'slug' => 'credit',
                'description' => 'Pay using account balance',
                'logo' => 'fas fa-wallet',
                'is_active' => true,
                'order' => 7,
                'config' => [],
                'supported_currencies' => [],
                'fee_fixed' => 0,
                'fee_percentage' => 0,
                'supports_recurring' => true,
                'supports_refunds' => false,
                'supports_webhooks' => false,
            ],
        ];

        foreach ($gateways as $gateway) {
            PaymentGateway::create($gateway);
        }
    }
}
