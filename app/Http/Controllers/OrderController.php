<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Show user's order history.
     */
    public function index()
    {
        $user = auth()->user();

        // Eager load order items and their products to prevent N+1 queries
        $orders = $user->orders()
            ->with(['orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('orders', [
            'orders' => $orders,
            'title' => 'My Orders',
        ]);
    }

    /**
     * Show specific order details.
     */
    public function show($id)
    {
        $order = Order::where('user_id', Auth::id())
            ->where('id', $id)
            ->with(['items.product', 'statusHistory'])
            ->firstOrFail();

        return view('orders.show', compact('order'));
    }

    public function track(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'order_number' => 'required|string',
                'email' => 'required|email',
            ]);

            $order = Order::where('order_number', $request->order_number)
                ->where('email', $request->email)
                ->with(['statusHistory', 'items.product'])
                ->first();

            if (!$order) {
                return back()->with('error', 'Order not found with provided details.');
            }

            return view('orders.track_result', compact('order'));
        }

        return view('orders.track');
    }
}
