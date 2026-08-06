<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentGateway;

class PaymentGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gateways = [
            [
                'name' => 'Stripe',
                'slug' => 'stripe',
                'provider' => 'stripe',
                'description' => 'Accept credit cards and other payment methods with Stripe',
                'is_active' => !empty(env('STRIPE_KEY')) && !empty(env('STRIPE_SECRET')),
                'config' => json_encode([
                    'public_key' => env('STRIPE_KEY', ''),
                    'secret_key' => env('STRIPE_SECRET', ''),
                    'webhook_secret' => env('STRIPE_WEBHOOK_SECRET', ''),
                ]),
                'sort_order' => 1,
            ],
            [
                'name' => 'PayPal',
                'slug' => 'paypal',
                'provider' => 'paypal',
                'description' => 'Accept payments via PayPal',
                'is_active' => !empty(env('PAYPAL_CLIENT_ID')) && !empty(env('PAYPAL_SECRET')),
                'config' => json_encode([
                    'mode' => env('PAYPAL_MODE', 'sandbox'),
                    'client_id' => env('PAYPAL_CLIENT_ID', ''),
                    'secret' => env('PAYPAL_SECRET', ''),
                ]),
                'sort_order' => 2,
            ],
            [
                'name' => 'Mollie',
                'slug' => 'mollie',
                'provider' => 'mollie',
                'description' => 'Accept payments with iDEAL, Credit Card, and more via Mollie',
                'is_active' => !empty(env('MOLLIE_KEY')),
                'config' => json_encode([
                    'api_key' => env('MOLLIE_KEY', ''),
                ]),
                'sort_order' => 3,
            ],
            [
                'name' => 'Balance',
                'slug' => 'balance',
                'provider' => 'balance',
                'description' => 'Pay using account balance',
                'is_active' => true,
                'config' => null,
                'sort_order' => 0,
            ],
            [
                'name' => 'Manual / Bank Transfer',
                'slug' => 'manual',
                'provider' => 'manual',
                'description' => 'Manual payment processing or bank transfer',
                'is_active' => true,
                'config' => json_encode([
                    'instructions' => 'Please transfer the amount to our bank account and include your invoice number in the reference.',
                ]),
                'sort_order' => 99,
            ],
        ];

        foreach ($gateways as $gateway) {
            PaymentGateway::firstOrCreate(
                ['slug' => $gateway['slug']],
                $gateway
            );
        }

        $this->command->info('✅ Payment gateways seeded successfully');
    }
}
