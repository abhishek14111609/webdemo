<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class AdminCollectionController extends Controller
{
    /**
     * Display a listing of the collections.
     */
    public function index()
    {
        $collections = Collection::orderBy('sort_order')->get();
        return view('admin.collections.index', compact('collections'));
    }

    /**
     * Show the form for creating a new collection.
     */
    public function create()
    {
        return view('admin.collections.create');
    }

    /**
     * Store a newly created collection in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:collections',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();

        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/collections', $imageName);
            $data['image'] = 'collections/' . $imageName;
        }

        // Set default sort order
        if (!isset($data['sort_order'])) {
            $data['sort_order'] = Collection::max('sort_order') + 1;
        }

        Collection::create($data);

        // Clear collections cache
        Cache::forget('featured_collections');

        return redirect()->route('admin.collections.index')->with('success', 'Collection created successfully.');
    }

    /**
     * Display the specified collection.
     */
    public function show(Collection $collection)
    {
        $products = $collection->products()->paginate(15);
        return view('admin.collections.show', compact('collection', 'products'));
    }

    /**
     * Show the form for editing the specified collection.
     */
    public function edit(Collection $collection)
    {
        return view('admin.collections.edit', compact('collection'));
    }

    /**
     * Update the specified collection in storage.
     */
    public function update(Request $request, Collection $collection)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:collections,slug,' . $collection->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();

        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($collection->image && Storage::exists('public/' . $collection->image)) {
                Storage::delete('public/' . $collection->image);
            }

            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/collections', $imageName);
            $data['image'] = 'collections/' . $imageName;
        }

        $collection->update($data);

        // Clear collections cache
        Cache::forget('featured_collections');

        return redirect()->route('admin.collections.index')->with('success', 'Collection updated successfully.');
    }

    /**
     * Remove the specified collection from storage.
     */
    public function destroy(Collection $collection)
    {
        // Delete image if exists
        if ($collection->image && Storage::exists('public/' . $collection->image)) {
            Storage::delete('public/' . $collection->image);
        }

        $collection->delete();

        // Clear collections cache
        Cache::forget('featured_collections');

        return redirect()->route('admin.collections.index')->with('success', 'Collection deleted successfully.');
    }

    /**
     * Update the sort order of collections.
     */
    public function updateOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'collections' => 'required|array',
            'collections.*.id' => 'required|integer|exists:collections,id',
            'collections.*.sort_order' => 'required|integer|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid data'], 422);
        }

        foreach ($request->collections as $collectionData) {
            Collection::where('id', $collectionData['id'])->update(['sort_order' => $collectionData['sort_order']]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Manage products in a collection.
     */
    public function manageProducts(Collection $collection)
    {
        $products = \App\Models\Product::all();
        $collectionProducts = $collection->products->pluck('id')->toArray();

        return view('admin.collections.manage-products', compact('collection', 'products', 'collectionProducts'));
    }

    /**
     * Update products in a collection.
     */
    public function updateProducts(Request $request, Collection $collection)
    {
        $validator = Validator::make($request->all(), [
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'integer|exists:products,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $productIds = $request->product_ids ?? [];

        // Sync products
        $collection->products()->sync($productIds);

        return redirect()->route('admin.collections.show', $collection)->with('success', 'Collection products updated successfully.');
    }
}
