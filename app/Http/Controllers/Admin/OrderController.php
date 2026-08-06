<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with(['user', 'items'])
            ->when($request->search, fn($q, $s) => $q->where('order_number', 'like', "%{$s}%"))
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'invoice', 'services']);

        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled,refunded',
            'notes'  => 'nullable|string',
        ]);

        if ($validated['status'] === 'completed' && $order->status !== 'completed') {
            $order->markAsCompleted();
        } else {
            $order->update($validated);
        }

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Commande mise à jour.');
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Commande supprimée.');
    }
}
