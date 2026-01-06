<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    public function apply(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string|max:50'
        ]);

        $code = strtoupper($request->coupon_code);
        $coupon = Coupon::where('code', $code)->active()->first();

        if (!$coupon) {
            return back()->with('error', 'Invalid or expired coupon code');
        }

        // Get cart total from session or calculate
        $cart = \App\Models\Cart::where('user_id', Auth::id())->first();

        if (!$cart || $cart->items->isEmpty()) {
            return back()->with('error', 'Your cart is empty');
        }

        $cartTotal = $cart->subtotal;

        // Validate coupon
        $validation = $coupon->canBeUsed(Auth::id(), $cartTotal);

        if (!$validation['valid']) {
            return back()->with('error', $validation['message']);
        }

        // Calculate discount
        $discount = $coupon->calculateDiscount($cartTotal);

        // Store in session
        session([
            'applied_coupon' => [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'discount' => $discount,
                'type' => $coupon->type,
                'value' => $coupon->value,
            ]
        ]);

        return back()->with('success', 'Coupon applied! You saved ₹' . number_format($discount, 2));
    }

    public function remove()
    {
        session()->forget('applied_coupon');
        return back()->with('success', 'Coupon removed');
    }
}
