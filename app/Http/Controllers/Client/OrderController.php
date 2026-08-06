<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = auth()->user()->orders()
            ->with('items.product')
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(15);

        return view('client.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $order->load(['items.product', 'invoice', 'services']);

        return view('client.orders.show', compact('order'));
    }

    public function create()
    {
        return redirect()->route('store.index');
    }

    public function store(Request $request)
    {
        return redirect()->route('store.index');
    }

    public function update(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        // Clients can only cancel pending orders
        if ($request->action === 'cancel' && $order->status === 'pending') {
            $order->update(['status' => 'cancelled']);

            return back()->with('success', 'Commande annulée.');
        }

        return back()->with('error', 'Action non autorisée.');
    }

    public function destroy(Order $order)
    {
        $this->authorize('delete', $order);

        return back()->with('error', 'La suppression de commandes n\'est pas autorisée.');
    }
}
