<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        $currencies = [
            ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€', 'symbol_position' => 'left', 'exchange_rate' => 1.000000, 'is_default' => true],
            ['code' => 'USD', 'name' => 'US Dollar', 'symbol' => '$', 'symbol_position' => 'left', 'exchange_rate' => 1.090000],
            ['code' => 'GBP', 'name' => 'British Pound', 'symbol' => '£', 'symbol_position' => 'left', 'exchange_rate' => 0.860000],
            ['code' => 'CAD', 'name' => 'Canadian Dollar', 'symbol' => 'C$', 'symbol_position' => 'left', 'exchange_rate' => 1.480000],
            ['code' => 'CHF', 'name' => 'Swiss Franc', 'symbol' => 'CHF', 'symbol_position' => 'right', 'exchange_rate' => 0.960000],
            ['code' => 'JPY', 'name' => 'Japanese Yen', 'symbol' => '¥', 'symbol_position' => 'left', 'exchange_rate' => 160.500000, 'decimal_places' => 0],
            ['code' => 'AUD', 'name' => 'Australian Dollar', 'symbol' => 'A$', 'symbol_position' => 'left', 'exchange_rate' => 1.680000],
        ];

        foreach ($currencies as $currency) {
            Currency::updateOrCreate(
                ['code' => $currency['code']],
                array_merge([
                    'decimal_places' => 2,
                    'decimal_separator' => '.',
                    'thousands_separator' => ',',
                    'is_active' => true,
                    'rate_updated_at' => now(),
                ], $currency)
            );
        }
    }
}
