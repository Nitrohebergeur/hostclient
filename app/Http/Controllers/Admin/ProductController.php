<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('category')
            ->when($request->search, fn($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->when($request->category, fn($q, $c) => $q->where('category_id', $c))
            ->when($request->type, fn($q, $t) => $q->where('type', $t))
            ->withCount('services')
            ->latest()
            ->paginate(20);

        $categories = ProductCategory::active()->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = ProductCategory::active()->orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id'         => 'required|exists:product_categories,id',
            'name'                => 'required|string|max:255',
            'description'         => 'nullable|string',
            'type'                => 'required|string',
            'price'               => 'required|numeric|min:0',
            'setup_fee'           => 'nullable|numeric|min:0',
            'billing_cycle'       => 'required|string',
            'stock'               => 'nullable|integer|min:0',
            'is_unlimited_stock'  => 'boolean',
            'is_active'           => 'boolean',
            'is_featured'         => 'boolean',
            'auto_setup'          => 'boolean',
            'module'              => 'nullable|string',
            'config'              => 'nullable|array',
            'features'            => 'nullable|array',
            'sort_order'          => 'integer',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produit créé avec succès.');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'services']);

        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = ProductCategory::active()->orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id'        => 'required|exists:product_categories,id',
            'name'               => 'required|string|max:255',
            'description'        => 'nullable|string',
            'type'               => 'required|string',
            'price'              => 'required|numeric|min:0',
            'setup_fee'          => 'nullable|numeric|min:0',
            'billing_cycle'      => 'required|string',
            'stock'              => 'nullable|integer|min:0',
            'is_unlimited_stock' => 'boolean',
            'is_active'          => 'boolean',
            'is_featured'        => 'boolean',
            'auto_setup'         => 'boolean',
            'module'             => 'nullable|string',
            'sort_order'         => 'integer',
        ]);

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produit mis à jour.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produit supprimé.');
    }
}
