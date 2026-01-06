<?php

namespace App\Http\Controllers;

use Razorpay\Api\Api;
use Illuminate\Http\Request;

class RazorpayController extends Controller
{
    public function createOrder(Request $request)
    {
    $amount = $request->amount;

    $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

    $order = $api->order->create([
        'receipt' => 'order_receipt_' . time(),
        'amount' => $amount,
        'currency' => 'INR'
    ]);

    return response()->json([
        'razorpayOrderId' => $order['id'],
        'amount' => $order['amount']
    ]);
}
}
