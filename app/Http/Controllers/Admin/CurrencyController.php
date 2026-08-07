<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function index()
    {
        $currencies = Currency::orderBy('is_default', 'desc')->orderBy('code')->get();
        return view('admin.currencies.index', compact('currencies'));
    }

    public function create()
    {
        // Proposer les devises courantes
        $suggestions = [
            ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€', 'symbol_position' => 'left'],
            ['code' => 'USD', 'name' => 'US Dollar', 'symbol' => '$', 'symbol_position' => 'left'],
            ['code' => 'GBP', 'name' => 'British Pound', 'symbol' => '£', 'symbol_position' => 'left'],
            ['code' => 'CAD', 'name' => 'Canadian Dollar', 'symbol' => 'C$', 'symbol_position' => 'left'],
            ['code' => 'CHF', 'name' => 'Swiss Franc', 'symbol' => 'CHF', 'symbol_position' => 'right'],
            ['code' => 'JPY', 'name' => 'Japanese Yen', 'symbol' => '¥', 'symbol_position' => 'left'],
            ['code' => 'AUD', 'name' => 'Australian Dollar', 'symbol' => 'A$', 'symbol_position' => 'left'],
            ['code' => 'BRL', 'name' => 'Brazilian Real', 'symbol' => 'R$', 'symbol_position' => 'left'],
            ['code' => 'INR', 'name' => 'Indian Rupee', 'symbol' => '₹', 'symbol_position' => 'left'],
            ['code' => 'MXN', 'name' => 'Mexican Peso', 'symbol' => 'MX$', 'symbol_position' => 'left'],
            ['code' => 'PLN', 'name' => 'Polish Złoty', 'symbol' => 'zł', 'symbol_position' => 'right'],
            ['code' => 'SEK', 'name' => 'Swedish Krona', 'symbol' => 'kr', 'symbol_position' => 'right'],
            ['code' => 'NOK', 'name' => 'Norwegian Krone', 'symbol' => 'kr', 'symbol_position' => 'right'],
            ['code' => 'DKK', 'name' => 'Danish Krone', 'symbol' => 'kr', 'symbol_position' => 'right'],
            ['code' => 'CZK', 'name' => 'Czech Koruna', 'symbol' => 'Kč', 'symbol_position' => 'right'],
            ['code' => 'HUF', 'name' => 'Hungarian Forint', 'symbol' => 'Ft', 'symbol_position' => 'right'],
            ['code' => 'RON', 'name' => 'Romanian Leu', 'symbol' => 'lei', 'symbol_position' => 'right'],
            ['code' => 'TRY', 'name' => 'Turkish Lira', 'symbol' => '₺', 'symbol_position' => 'left'],
            ['code' => 'RUB', 'name' => 'Russian Ruble', 'symbol' => '₽', 'symbol_position' => 'right'],
            ['code' => 'KRW', 'name' => 'South Korean Won', 'symbol' => '₩', 'symbol_position' => 'left'],
            ['code' => 'SGD', 'name' => 'Singapore Dollar', 'symbol' => 'S$', 'symbol_position' => 'left'],
            ['code' => 'HKD', 'name' => 'Hong Kong Dollar', 'symbol' => 'HK$', 'symbol_position' => 'left'],
            ['code' => 'NZD', 'name' => 'New Zealand Dollar', 'symbol' => 'NZ$', 'symbol_position' => 'left'],
            ['code' => 'ZAR', 'name' => 'South African Rand', 'symbol' => 'R', 'symbol_position' => 'left'],
            ['code' => 'MAD', 'name' => 'Moroccan Dirham', 'symbol' => 'MAD', 'symbol_position' => 'right'],
            ['code' => 'DZD', 'name' => 'Algerian Dinar', 'symbol' => 'DA', 'symbol_position' => 'right'],
            ['code' => 'TND', 'name' => 'Tunisian Dinar', 'symbol' => 'DT', 'symbol_position' => 'right'],
        ];

        return view('admin.currencies.create', compact('suggestions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'                => 'required|string|size:3|unique:currencies,code',
            'name'                => 'required|string|max:100',
            'symbol'              => 'required|string|max:10',
            'symbol_position'     => 'required|in:left,right',
            'decimal_places'      => 'required|integer|min:0|max:4',
            'decimal_separator'   => 'required|string|max:1',
            'thousands_separator' => 'required|string|max:1',
            'exchange_rate'       => 'required|numeric|min:0.000001',
            'is_default'          => 'boolean',
            'is_active'           => 'boolean',
        ]);

        $validated['code'] = strtoupper($validated['code']);

        if (!empty($validated['is_default'])) {
            Currency::where('is_default', true)->update(['is_default' => false]);
        }

        Currency::create($validated);

        return redirect()
            ->route('admin.currencies.index')
            ->with('success', "Devise {$validated['code']} ajoutée.");
    }

    public function edit(Currency $currency)
    {
        return view('admin.currencies.edit', compact('currency'));
    }

    public function update(Request $request, Currency $currency)
    {
        $validated = $request->validate([
            'name'                => 'required|string|max:100',
            'symbol'              => 'required|string|max:10',
            'symbol_position'     => 'required|in:left,right',
            'decimal_places'      => 'required|integer|min:0|max:4',
            'decimal_separator'   => 'required|string|max:1',
            'thousands_separator' => 'required|string|max:1',
            'exchange_rate'       => 'required|numeric|min:0.000001',
            'is_active'           => 'boolean',
        ]);

        $currency->update($validated);

        return redirect()
            ->route('admin.currencies.index')
            ->with('success', "Devise {$currency->code} mise à jour.");
    }

    public function destroy(Currency $currency)
    {
        if ($currency->is_default) {
            return redirect()->back()->with('error', 'Impossible de supprimer la devise par défaut.');
        }
        $currency->delete();

        return redirect()
            ->route('admin.currencies.index')
            ->with('success', "Devise {$currency->code} supprimée.");
    }

    public function setDefault(Currency $currency)
    {
        $currency->setAsDefault();

        return redirect()
            ->route('admin.currencies.index')
            ->with('success', "{$currency->code} définie comme devise par défaut.");
    }

    public function updateRates()
    {
        Currency::updateRates();

        return redirect()
            ->route('admin.currencies.index')
            ->with('success', 'Taux de change mis à jour depuis l\'API.');
    }
}
