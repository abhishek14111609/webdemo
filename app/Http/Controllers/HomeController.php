<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use App\Models\Collection;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Display the website home page.
     */
    public function index()
    {
        // Cache categories for 1 hour
        $categories = Cache::remember('active_categories', 3600, function () {
            return Category::where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        });

        // Cache collections for 1 hour
        $collections = Cache::remember('featured_collections', 3600, function () {
            return Collection::where('is_active', true)
                ->where('is_featured', true)
                ->orderBy('sort_order')
                ->get();
        });

        // Cache featured products for 30 minutes
        $featuredProducts = Cache::remember('featured_products', 1800, function () {
            return Product::where('is_active', true)
                ->where('is_featured', true)
                ->with('category')
                ->orderBy('created_at', 'desc')
                ->limit(8)
                ->get();
        });

        // Cache sliders for 1 hour
        $sliders = Cache::remember('active_sliders', 3600, function () {
            return Slider::where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        });

        // Latest products (not cached as they change frequently)
        $latestProducts = Product::where('is_active', true)
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->limit(12)
            ->get();

        return view('welcome', [
            'categories' => $categories,
            'featuredCollections' => $collections,  // Match Blade template variable name
            'featuredProducts' => $featuredProducts,
            'sliders' => $sliders,
            'latestProducts' => $latestProducts,
            'title' => config('app.name') . ' - Premium Ethnic Wear',
            'description' => 'Discover our exquisite collection of traditional and contemporary ethnic wear',
        ]);
    }

    /**
     * Return the public image URL or fallback placeholder.
     */
    private function getImageUrl($path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            return asset('storage/' . $path);
        }

        return asset('images/no-image.jpg'); // fallback image
    }

    /**
     * Return the thumbnail URL if available.
     */
    private function getThumbnailUrl($path)
    {
        if (!$path) {
            return asset('images/no-image.jpg');
        }

        $info = pathinfo($path);
        $thumbPath = $info['dirname'] . '/thumbs/' . $info['basename'];

        if (Storage::disk('public')->exists($thumbPath)) {
            return asset('storage/' . $thumbPath);
        }

        return asset('images/no-image.jpg');
    }
}
