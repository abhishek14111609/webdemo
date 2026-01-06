<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display the specified category.
     *
     * @param  string  $slug
     * @return \Illuminate\View\View
     */
    public function show($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        
        return view('category.show', [
            'category' => $category,
            'title' => $category->name,
            'description' => $category->description ?? 'Browse our collection of ' . $category->name
        ]);
    }

    /**
     * Display a listing of all categories.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
            
        return view('category.index', [
            'categories' => $categories,
            'title' => 'All Categories',
            'description' => 'Browse all our jewelry categories.'
        ]);
    }
}
