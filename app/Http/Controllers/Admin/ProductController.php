<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductGroup;
use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'servers'])
            ->orderBy('order')
            ->paginate(20);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = ProductCategory::where('is_active', true)
            ->orderBy('order')
            ->get();

        $groups = ProductGroup::where('is_active', true)
            ->orderBy('order')
            ->get();

        $servers = Server::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.products.create', compact('categories', 'groups', 'servers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:product_categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:products,slug',
            'description' => 'nullable|string',
            'type' => 'required|in:hosting,vps,dedicated,game,domain,custom',
            'module' => 'nullable|string',
            
            // Tarifs
            'allow_hourly_billing' => 'boolean',
            'price_hourly' => 'nullable|numeric|min:0',
            'price_monthly' => 'nullable|numeric|min:0',
            'price_quarterly' => 'nullable|numeric|min:0',
            'price_semiannually' => 'nullable|numeric|min:0',
            'price_annually' => 'nullable|numeric|min:0',
            'price_biennially' => 'nullable|numeric|min:0',
            'setup_fee' => 'nullable|numeric|min:0',
            'currency' => 'required|string|size:3',
            
            // Ressources
            'resources' => 'nullable|array',
            'config_options' => 'nullable|array',
            
            // Paramètres
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'auto_provision' => 'boolean',
            'stock' => 'nullable|integer|min:0',
            
            // Relations
            'groups' => 'nullable|array',
            'groups.*' => 'exists:product_groups,id',
            'servers' => 'nullable|array',
            'servers.*' => 'exists:servers,id',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $product = Product::create($validated);

        // Attacher les groupes
        if (!empty($validated['groups'])) {
            $product->groups()->attach($validated['groups']);
        }

        // Attacher les serveurs
        if (!empty($validated['servers'])) {
            $product->servers()->attach($validated['servers']);
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $product->load(['category', 'groups', 'servers']);

        $categories = ProductCategory::where('is_active', true)
            ->orderBy('order')
            ->get();

        $groups = ProductGroup::where('is_active', true)
            ->orderBy('order')
            ->get();

        $servers = Server::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.products.edit', compact('product', 'categories', 'groups', 'servers'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:product_categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:products,slug,' . $product->id,
            'description' => 'nullable|string',
            'type' => 'required|in:hosting,vps,dedicated,game,domain,custom',
            'module' => 'nullable|string',
            
            // Tarifs
            'allow_hourly_billing' => 'boolean',
            'price_hourly' => 'nullable|numeric|min:0',
            'price_monthly' => 'nullable|numeric|min:0',
            'price_quarterly' => 'nullable|numeric|min:0',
            'price_semiannually' => 'nullable|numeric|min:0',
            'price_annually' => 'nullable|numeric|min:0',
            'price_biennially' => 'nullable|numeric|min:0',
            'setup_fee' => 'nullable|numeric|min:0',
            'currency' => 'required|string|size:3',
            
            // Ressources
            'resources' => 'nullable|array',
            'config_options' => 'nullable|array',
            
            // Paramètres
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'auto_provision' => 'boolean',
            'stock' => 'nullable|integer|min:0',
            
            // Relations
            'groups' => 'nullable|array',
            'groups.*' => 'exists:product_groups,id',
            'servers' => 'nullable|array',
            'servers.*' => 'exists:servers,id',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $product->update($validated);

        // Synchroniser les groupes
        if (isset($validated['groups'])) {
            $product->groups()->sync($validated['groups']);
        } else {
            $product->groups()->detach();
        }

        // Synchroniser les serveurs
        if (isset($validated['servers'])) {
            $product->servers()->sync($validated['servers']);
        } else {
            $product->servers()->detach();
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        // Vérifier si le produit a des services actifs
        if ($product->services()->whereIn('status', ['active', 'pending'])->exists()) {
            return redirect()
                ->back()
                ->with('error', 'Cannot delete product with active services.');
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function duplicate(Product $product)
    {
        $newProduct = $product->replicate();
        $newProduct->name = $product->name . ' (Copy)';
        $newProduct->slug = $product->slug . '-copy-' . time();
        $newProduct->is_active = false;
        $newProduct->save();

        // Dupliquer les relations
        $newProduct->groups()->attach($product->groups->pluck('id'));
        $newProduct->servers()->attach($product->servers->pluck('id'));

        return redirect()
            ->route('admin.products.edit', $newProduct)
            ->with('success', 'Product duplicated successfully.');
    }
}
