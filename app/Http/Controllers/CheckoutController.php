<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function __construct(protected PaymentService $paymentService) {}

    /**
     * PayPal redirige ici après approbation de l'utilisateur.
     * On capture l'argent et on marque la commande comme payée.
     */
    public function success(Request $request, Order $order)
    {
        // Sécurité : la commande doit appartenir à l'utilisateur courant
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $paypalOrderId = $request->query('token');

        if (!$paypalOrderId) {
            return redirect()->route('client.orders.show', $order)
                ->with('warning', 'Identifiant de paiement PayPal manquant.');
        }

        try {
            $captured = $this->paymentService->capturePaypalOrder($paypalOrderId);

            if ($captured) {
                $order->markAsCompleted();

                return redirect()->route('checkout.success.page', $order);
            }

            return redirect()->route('client.orders.show', $order)
                ->with('error', 'Le paiement PayPal n\'a pas pu être finalisé.');
        } catch (\Exception $e) {
            Log::error('PayPal capture error', [
                'order_id' => $order->id,
                'paypal_id' => $paypalOrderId,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('client.orders.show', $order)
                ->with('error', 'Erreur lors de la finalisation : ' . $e->getMessage());
        }
    }

    /**
     * Page de confirmation affichée après un paiement réussi.
     */
    public function successPage(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('checkout.success', compact('order'));
    }

    /**
     * L'utilisateur a annulé sur PayPal / Stripe / Mollie.
     */
    public function cancel(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // On remet la commande en pending (ou cancelled selon ta logique métier)
        $order->update(['status' => 'cancelled']);

        return redirect()->route('store.cart')
            ->with('warning', 'Paiement annulé. Votre panier est toujours disponible.');
    }
}