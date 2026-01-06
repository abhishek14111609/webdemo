<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;

class AdminProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'gallery' => 'nullable',
            'gallery.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        // Main image upload → storage/app/public/products/
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products/' . date('Y/m'), 'public');
            $validated['image'] = $path;
        }

        // Gallery images upload → storage/app/public/products/gallery/
        if ($request->hasFile('gallery')) {
            $galleryPaths = [];
            foreach ($request->file('gallery') as $galleryImage) {
                $galleryPaths[] = $galleryImage->store('products/' . date('Y/m') . '/gallery', 'public');
            }
            $validated['gallery'] = $galleryPaths;
        }

        Product::create($validated);

        // Clear product caches
        Cache::forget('featured_products');

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'gallery' => 'nullable',
            'gallery.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        // Replace main image
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products/' . date('Y/m'), 'public');
        }

        // Replace gallery
        if ($request->hasFile('gallery')) {
            if (is_array($product->gallery)) {
                foreach ($product->gallery as $g) {
                    Storage::disk('public')->delete($g);
                }
            }

            $galleryPaths = [];
            foreach ($request->file('gallery') as $galleryImage) {
                $galleryPaths[] = $galleryImage->store('products/' . date('Y/m') . '/gallery', 'public');
            }
            $validated['gallery'] = $galleryPaths;
        }

        $product->update($validated);

        // Clear product caches
        Cache::forget('featured_products');

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        if (is_array($product->gallery)) {
            foreach ($product->gallery as $g) {
                Storage::disk('public')->delete($g);
            }
        }

        $product->delete();

        // Clear product caches
        Cache::forget('featured_products');

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
