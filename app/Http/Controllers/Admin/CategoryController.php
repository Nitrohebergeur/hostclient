<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::withCount('products')->orderBy('sort_order')->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order'  => 'integer',
            'is_active'   => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        ProductCategory::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie créée.');
    }

    public function edit(ProductCategory $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, ProductCategory $category)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order'  => 'integer',
            'is_active'   => 'boolean',
        ]);

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie mise à jour.');
    }

    public function destroy(ProductCategory $category)
    {
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie supprimée.');
    }
}
