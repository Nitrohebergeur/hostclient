<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;

class PaymentGatewayController extends Controller
{
    public function index()
    {
        $gateways = PaymentGateway::orderBy('sort_order')->get();

        return view('admin.payment-gateways.index', compact('gateways'));
    }

    public function create()
    {
        return view('admin.payment-gateways.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'provider'   => 'required|string',
            'is_active'  => 'boolean',
            'config'     => 'nullable|array',
            'sort_order' => 'integer',
        ]);

        PaymentGateway::create($validated);

        return redirect()->route('admin.payment-gateways.index')
            ->with('success', 'Passerelle créée.');
    }

    public function edit(PaymentGateway $paymentGateway)
    {
        return view('admin.payment-gateways.edit', compact('paymentGateway'));
    }

    public function update(Request $request, PaymentGateway $paymentGateway)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'is_active'  => 'boolean',
            'config'     => 'nullable|array',
            'sort_order' => 'integer',
        ]);

        $paymentGateway->update($validated);

        return redirect()->route('admin.payment-gateways.index')
            ->with('success', 'Passerelle mise à jour.');
    }

    public function destroy(PaymentGateway $paymentGateway)
    {
        $paymentGateway->delete();

        return redirect()->route('admin.payment-gateways.index')
            ->with('success', 'Passerelle supprimée.');
    }
}
