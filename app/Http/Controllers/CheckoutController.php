<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Razorpay\Api\Api;
use PDF; // for bill download

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Show checkout page
    public function index()
    {
        $user = Auth::user();
    $cart = Cart::with('items.product')->where('user_id', $user->id)->first();

    if (!$cart || $cart->items->isEmpty()) {
        return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
    }

    return view('checkout.checkout', compact('cart', 'user'));
    }

    // Process Razorpay Payment (simple demo)
    public function razorpayPayment(Request $request)
    {
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Create Order
        $order = Order::create([
            'user_id' => $user->id,
            'order_number' => 'ORD-' . time(),
            'subtotal' => $cart->subtotal,
            'tax' => $cart->tax,
            'total' => $cart->total,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'country' => $request->country,
            'payment_method' => 'Razorpay',
            'payment_id' => $request->razorpay_payment_id ?? null,
            'status' => 'completed',
        ]);
        

        // Save order items
        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product->name,
                'price' => $item->product->price,
                'quantity' => $item->quantity,
                'subtotal' => $item->subtotal,
            ]);
        }

        // Clear cart
        $cart->items()->delete();
        $cart->delete();

        // Redirect to bill page
        return redirect()->route('checkout.bill', ['orderId' => $order->id])
                         ->with('success', 'Payment successful!');
    }

    // Show bill
    public function bill($orderId)
    {
        $order = Order::with('items')->findOrFail($orderId);
        return view('checkout.bill', compact('order'));
    }

    // Download bill as PDF
    public function downloadBill($orderId)
    {
        $order = Order::with('items')->findOrFail($orderId);
        $pdf = PDF::loadView('checkout.bill_pdf', compact('order'));
        return $pdf->download('Bill_' . $order->order_number . '.pdf');
        
    }

    // Success page
    public function success()
    {
        return redirect('/')->with('success', 'Payment successful!');
    }
}
