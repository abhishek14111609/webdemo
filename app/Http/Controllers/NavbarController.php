<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Collection;
use Illuminate\Support\Facades\View;

class NavbarController extends Controller
{
    /**
     * Share navbar data with all views.
     *
     * @return void
     */
    public function handle()
    {
        // Get active categories for navbar
        $navbarCategories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->take(5) // Limit to prevent overcrowding the navbar
            ->get();
            
        // Get active collections for navbar
        $navbarCollections = Collection::where('is_active', true)
            ->orderBy('sort_order')
            ->take(5) // Limit to prevent overcrowding the navbar
            ->get();
            
        // Make sure collections is not null before sharing
        if ($navbarCategories === null) {
            $navbarCategories = collect();
        }
        
        if ($navbarCollections === null) {
            $navbarCollections = collect();
        }
            
        // Share data with all views
        View::share('navbarCategories', $navbarCategories);
        View::share('navbarCollections', $navbarCollections);
    }
}