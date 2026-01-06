<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Display the main shop page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('shop.index', [
            'title' => 'Our Shop',
            'description' => 'Browse our collection of fine jewelry'
        ]);
    }

    /**
     * Display the collections page.
     *
     * @return \Illuminate\View\View
     */
    public function collections()
    {
        return view('shop.collections', [
            'title' => 'Our Collections',
            'description' => 'Explore our curated jewelry collections'
        ]);
    }
}
