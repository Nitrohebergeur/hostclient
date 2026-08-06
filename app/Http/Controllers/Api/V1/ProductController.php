<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('category')
            ->active()
            ->inStock()
            ->when($request->category, fn($q, $c) => $q->where('category_id', $c))
            ->when($request->type, fn($q, $t) => $q->where('type', $t))
            ->when($request->featured, fn($q) => $q->featured())
            ->orderBy('sort_order')
            ->paginate(20);

        return response()->json($products);
    }

    public function show(Product $product)
    {
        if (!$product->is_active) {
            return response()->json([
                'message' => 'Produit non disponible.',
            ], 404);
        }

        $product->load('category');

        return response()->json($product);
    }
}
