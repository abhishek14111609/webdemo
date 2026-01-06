<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    /**
     * Display a listing of the collections.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $collections = Collection::where('is_active', true)
            ->orderBy('sort_order')
            ->withCount('products')
            ->get();
            
        return view('shop.collections', [
            'collections' => $collections,
            'title' => 'Our Collections',
            'description' => 'Discover our carefully curated jewelry collections.'
        ]);
    }

    /**
     * Display the specified collection.
     *
     * @param  string  $slug
     * @return \Illuminate\View\View
     */
    public function show($slug)
    {
        $collection = Collection::where('slug', $slug)
            ->with(['products' => function($query) {
                $query->where('is_active', true);
            }])
            ->firstOrFail();
        
        return view('collection.show', [
            'collection' => $collection,
            'products' => $collection->products,
            'title' => $collection->title,
            'description' => $collection->description
        ]);
    }
}
