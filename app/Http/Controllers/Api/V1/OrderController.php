<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = $request->user()
            ->orders()
            ->with('items.product')
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(20);

        return response()->json($orders);
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $order->load(['items.product', 'invoice', 'services']);

        return response()->json($order);
    }

    public function store(Request $request)
    {
        // Orders are typically created through the store checkout process
        return response()->json([
            'message' => 'Utilisez le processus de commande via l\'interface boutique.',
        ], 422);
    }

    public function update(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        // Clients can only cancel pending orders
        if ($request->action === 'cancel' && $order->status === 'pending') {
            $order->update(['status' => 'cancelled']);

            return response()->json([
                'message' => 'Commande annulée.',
                'order'   => $order,
            ]);
        }

        return response()->json([
            'message' => 'Action non autorisée.',
        ], 403);
    }

    public function destroy(Order $order)
    {
        $this->authorize('delete', $order);

        return response()->json([
            'message' => 'La suppression de commandes n\'est pas autorisée.',
        ], 403);
    }
}
