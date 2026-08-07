<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Currency;

class HomeController extends Controller
{
    public function index()
    {
        // Catégories actives avec leurs produits
        $categories = ProductCategory::with([
            'products' => fn($q) => $q->where('is_active', true)->orderBy('order')
        ])
            ->where('is_active', true)
            ->orderBy('order')
            ->get()
            ->filter(fn($c) => $c->products->isNotEmpty());

        // Produits en vedette
        $featuredProducts = Product::with('category')
            ->where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('order')
            ->take(6)
            ->get();

        // Devise par défaut
        $currency = Currency::getDefault();
        $currencies = Currency::getActive();

        return view('welcome', compact('categories', 'featuredProducts', 'currency', 'currencies'));
    }

    public function products(?string $categorySlug = null)
    {
        $query = Product::with('category')->where('is_active', true);

        $category = null;
        if ($categorySlug) {
            $category = ProductCategory::where('slug', $categorySlug)
                ->where('is_active', true)
                ->firstOrFail();
            $query->where('category_id', $category->id);
        }

        $categories = ProductCategory::where('is_active', true)
            ->orderBy('order')
            ->get();

        $products = $query->orderBy('order')->paginate(12);
        $currency = Currency::getDefault();

        return view('products', compact('products', 'categories', 'category', 'currency'));
    }

    public function productDetail(string $slug)
    {
        $product = Product::with(['category', 'servers'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $related = Product::with('category')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(3)
            ->get();

        $currency = Currency::getDefault();

        return view('product-detail', compact('product', 'related', 'currency'));
    }
}
