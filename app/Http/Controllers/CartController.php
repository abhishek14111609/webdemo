<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Display the user's shopping cart.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $cart = Cart::with('items.product')->where('user_id', $user->id)->first();
        
        return view('cart.index', [
            'cart' => $cart,
            'title' => 'Shopping Cart'
        ]);
    }

    /**
     * Add a product to the cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addToCart(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'options' => 'nullable|array',
        ]);

        $user = Auth::user();
        $quantity = $request->input('quantity', 1);
        $options = $request->input('options', []);
        
        // Check if product is in stock
        if ($product->stock < $quantity) {
            return back()->with('error', 'Sorry, the requested quantity is not available in stock.');
        }

        // Get or create cart
        $cart = Cart::firstOrCreate(
            ['user_id' => $user->id],
            [
                'tax' => 0,
                'discount' => 0,
                'coupon_discount' => 0,
                'shipping_cost' => 0,
                'subtotal' => 0,
                'total' => 0,
            ]
        );

        // Check if product already exists in cart
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->where('options', json_encode($options))
            ->first();

        if ($cartItem) {
            // Update existing cart item
            $cartItem->quantity += $quantity;
            $cartItem->price = $product->price;
            $cartItem->subtotal = $cartItem->price * $cartItem->quantity;
            $cartItem->save();
        } else {
            // Create new cart item
            $cartItem = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->price,
                'subtotal' => $product->price * $quantity,
                'options' => $options,
            ]);
        }

        // Recalculate cart totals
        $cart->calculateTotals();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product added to cart successfully!',
                'cart_count' => $cart->items->sum('quantity'),
                'cart_total' => $cart->total
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Product added to cart successfully!');
    }

    /**
     * Update the quantity of a cart item.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CartItem  $cartItem
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        
        // Ensure the cart item belongs to the user
        if ($cartItem->cart->user_id !== $user->id) {
            return back()->with('error', 'You do not have permission to update this cart item.');
        }

        $quantity = $request->input('quantity');
        
        // Check if product is in stock
        if ($cartItem->product->stock < $quantity) {
            return back()->with('error', 'Sorry, the requested quantity is not available in stock.');
        }

        // Update cart item
        $cartItem->quantity = $quantity;
        $cartItem->subtotal = $cartItem->price * $quantity;
        $cartItem->save();

        // Recalculate cart totals
        $cartItem->cart->calculateTotals();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully!',
                'item_subtotal' => $cartItem->subtotal,
                'cart_subtotal' => $cartItem->cart->subtotal,
                'cart_total' => $cartItem->cart->total
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Cart updated successfully!');
    }

    /**
     * Remove a cart item.
     *
     * @param  \App\Models\CartItem  $cartItem
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, CartItem $cartItem)
    {
        $user = Auth::user();
        
        // Ensure the cart item belongs to the user
        if ($cartItem->cart->user_id !== $user->id) {
            return back()->with('error', 'You do not have permission to remove this cart item.');
        }

        $cart = $cartItem->cart;
        $cartItem->delete();

        // Recalculate cart totals
        $cart->calculateTotals();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart!',
                'cart_count' => $cart->items->sum('quantity'),
                'cart_subtotal' => $cart->subtotal,
                'cart_total' => $cart->total
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Item removed from cart!');
    }

    /**
     * Clear all items from the cart.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clear(Request $request)
    {
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->first();

        if ($cart) {
            // Delete all cart items
            $cart->items()->delete();
            
            // Reset cart totals
            $cart->subtotal = 0;
            $cart->total = 0;
            $cart->save();
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cart cleared successfully!'
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Cart cleared successfully!');
    }
}