<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;

class PublicOfferController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::active()
            ->whereHas('products', fn($q) => $q->active()->inStock())
            ->with(['products' => fn($q) => $q->active()->inStock()->orderBy('sort_order')])
            ->orderBy('sort_order')
            ->get();

        $featured = Product::active()->inStock()->featured()->with('category')->take(6)->get();

        return view('public.offers', compact('categories', 'featured'));
    }
}
