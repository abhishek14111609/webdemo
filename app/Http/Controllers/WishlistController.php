<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display the user's wishlist.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $wishlist = Wishlist::with('product')
            ->where('user_id', $user->id)
            ->get();
        
        return view('wishlist.index', [
            'wishlist' => $wishlist,
            'title' => 'My Wishlist'
        ]);
    }

    /**
     * Add a product to the wishlist.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addToWishlist(Request $request, Product $product)
    {
        $user = Auth::user();
        
        // Check if product already exists in wishlist
        $exists = Wishlist::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->exists();
            
        if ($exists) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product is already in your wishlist!'
                ]);
            }
            
            return back()->with('info', 'Product is already in your wishlist!');
        }
        
        // Add product to wishlist
        Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $product->id
        ]);
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product added to wishlist successfully!'
            ]);
        }
        
        return back()->with('success', 'Product added to wishlist successfully!');
    }
    
    /**
     * Move a product from wishlist to cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function moveToCart(Request $request, $id)
    {
        $user = Auth::user();
        
        // Check if product exists in wishlist
        $wishlistItem = Wishlist::where('user_id', $user->id)
            ->where('product_id', $id)
            ->first();
            
        if (!$wishlistItem) {
            return back()->with('error', 'Product not found in your wishlist!');
        }
        
        // Find the product
        $product = Product::findOrFail($id);
        
        // Remove from wishlist
        $wishlistItem->delete();
        
        // Add to cart using CartController
        $cartController = new CartController();
        $cartRequest = new Request(['quantity' => 1]);
        
        return $cartController->addToCart($cartRequest, $product);
    }

    /**
     * Remove a product from the wishlist.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeFromWishlist(Request $request, $id)
    {
        $user = Auth::user();
        
        // Find and delete wishlist item
        $deleted = Wishlist::where('user_id', $user->id)
            ->where('product_id', $id)
            ->delete();
            
        if (!$deleted) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found in your wishlist!'
                ]);
            }
            
            return back()->with('error', 'Product not found in your wishlist!');
        }
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product removed from wishlist!'
            ]);
        }
        
        return back()->with('success', 'Product removed from wishlist!');
    }
    
    /**
     * Update notes for a wishlist item.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateNotes(Request $request, $id)
    {
        $user = Auth::user();
        
        // Find wishlist item
        $wishlistItem = Wishlist::where('id', $id)
            ->where('user_id', $user->id)
            ->first();
            
        if (!$wishlistItem) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Wishlist item not found!'
                ]);
            }
            
            return back()->with('error', 'Wishlist item not found!');
        }
        
        // Update notes
        $wishlistItem->notes = $request->notes;
        $wishlistItem->save();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Notes updated successfully!'
            ]);
        }
        
        return back()->with('success', 'Notes updated successfully!');
    }
}