<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class StoreController extends Controller
{
    public function __construct(protected PaymentService $paymentService) {}

    public function index()
    {
        $categories = ProductCategory::active()
            ->with(['products' => fn($q) => $q->active()->inStock()->orderBy('sort_order')])
            ->orderBy('sort_order')
            ->get();

        $featured = Product::active()->inStock()->featured()->with('category')->take(6)->get();

        return view('store.index', compact('categories', 'featured'));
    }

    public function category(ProductCategory $category)
    {
        $products = $category->products()
            ->active()
            ->inStock()
            ->orderBy('sort_order')
            ->paginate(12);

        return view('store.category', compact('category', 'products'));
    }

    public function product(ProductCategory $category, Product $product)
    {
        abort_if(!$product->is_active, 404);

        return view('store.product', compact('category', 'product'));
    }

    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'product_id'    => 'required|exists:products,id',
            'billing_cycle' => 'required|string',
            'quantity'      => 'integer|min:1|max:10',
            'config'        => 'nullable|array',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        if (!$product->isInStock()) {
            return back()->with('error', 'Ce produit est en rupture de stock.');
        }

        $cart = Session::get('cart', []);
        $key  = $product->id . '_' . $validated['billing_cycle'];

        $cart[$key] = [
            'product_id'    => $product->id,
            'name'          => $product->name,
            'price'         => $product->getPriceForCycle($validated['billing_cycle']),
            'setup_fee'     => $product->setup_fee,
            'billing_cycle' => $validated['billing_cycle'],
            'quantity'      => $validated['quantity'] ?? 1,
            'config'        => $validated['config'] ?? [],
        ];

        Session::put('cart', $cart);

        return redirect()->route('store.cart')
            ->with('success', 'Produit ajouté au panier.');
    }

    public function cart()
    {
        $cart     = Session::get('cart', []);
        $subtotal = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);
        $setupFee = collect($cart)->sum(fn($i) => $i['setup_fee'] * $i['quantity']);
        $tax      = ($subtotal + $setupFee) * (config('hostclient.tax_rate', 0) / 100);
        $total    = $subtotal + $setupFee + $tax;

        $gateways = \App\Models\PaymentGateway::active()->orderBy('sort_order')->get();

        return view('store.cart', compact('cart', 'subtotal', 'setupFee', 'tax', 'total', 'gateways'));
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'payment_method' => 'required|string',
            'coupon_code'    => 'nullable|string',
        ]);

        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('store.cart')->with('error', 'Votre panier est vide.');
        }

        // Apply coupon if provided
        $discount = 0;
        if ($validated['coupon_code']) {
            $coupon = Coupon::where('code', strtoupper($validated['coupon_code']))->first();
            if ($coupon && $coupon->isValid()) {
                $subtotal = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);
                $discount = $coupon->calculateDiscount($subtotal);
            }
        }

        // Create order
        $order = Order::create([
            'order_number'   => Order::generateOrderNumber(),
            'user_id'        => auth()->id(),
            'status'         => 'pending',
            'subtotal'       => collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']),
            'tax'            => 0,
            'discount'       => $discount,
            'total'          => 0,
            'currency'       => config('hostclient.currency', 'EUR'),
            'payment_method' => $validated['payment_method'],
        ]);

        foreach ($cart as $item) {
            $order->items()->create([
                'product_id'    => $item['product_id'],
                'name'          => $item['name'],
                'quantity'      => $item['quantity'],
                'unit_price'    => $item['price'],
                'setup_fee'     => $item['setup_fee'],
                'total'         => $item['price'] * $item['quantity'],
                'billing_cycle' => $item['billing_cycle'],
                'config'        => $item['config'],
            ]);
        }

        $order->calculateTotal();

        // Process payment
        try {
            $paymentUrl = $this->paymentService->process($order, $validated['payment_method']);

            Session::forget('cart');

            if ($paymentUrl) {
                return redirect($paymentUrl);
            }

            return redirect()->route('client.orders.show', $order)
                ->with('success', 'Commande passée avec succès.');
        } catch (\Exception $e) {
            $order->delete();

            return back()->with('error', 'Erreur de paiement : ' . $e->getMessage());
        }
    }
}
