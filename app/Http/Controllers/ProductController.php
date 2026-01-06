<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->with(['category', 'collections', 'reviews.user'])
            ->firstOrFail();

        // Get approved reviews for this product
        $reviews = $product->reviews()
            ->with('user')
            ->where('is_approved', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('product.show', [
            'product' => $product,
            'title' => $product->name,
            'reviews' => $reviews,
        ]);
    }

    public function index(Request $request)
    {
        $query = Product::query()->with('category');

        // Apply search filter
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%")
                    ->orWhereHas('category', function ($q2) use ($searchTerm) {
                        $q2->where('name', 'like', "%{$searchTerm}%");
                    });
            });
        }

        // Apply category filter
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Apply collection filter
        if ($request->filled('collection')) {
            $query->whereHas('collections', function ($q) use ($request) {
                $q->where('slug', $request->collection);
            });
        }

        // Apply price range filter
        if ($request->filled('min_price') && is_numeric($request->min_price)) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price') && is_numeric($request->max_price)) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sorting
        switch ($request->input('sort', 'newest')) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $products = $query->where('is_active', true)->paginate(12)->withQueryString();

        $categories = \App\Models\Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('shop.index', [
            'products' => $products,
            'categories' => $categories,
            'title' => 'Shop Our Products',
            'description' => 'Browse our collection of fine jewelry.',
            'request' => $request->all(),
        ]);
    }
}
