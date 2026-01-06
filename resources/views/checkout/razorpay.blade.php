@extends('layouts.master')

@section('title', 'Razorpay Payment')

@section('content')
<div class="container mt-5 text-center">
    <h2>Complete Your Payment</h2>
    <p>Amount: ₹{{ number_format($cart->subtotal, 2) }}</p>

    <button id="rzp-button1" class="btn btn-primary">Pay Now</button>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
     var orderId = "{{ $order->order_id }}";
var options = {
    "key": "{{ env('RAZORPAY_KEY') }}",
    "amount": "{{ $razorpayOrder->amount }}",
    "currency": "INR",
    "name": "Eternal Diamonds",
    "description": "Order Payment",
    "order_id": "{{ $razorpayOrder->id }}",
    "callback_url": "{{ route('checkout.razorpay.payment') }}",
    "prefill": {
        "name": "{{ $user->name }}",
        "email": "{{ $user->email }}",
        "contact": "{{ $user->phone ?? '' }}"
    },
    "theme": { "color": "#3399cc" }
};
var rzp1 = new Razorpay(options);
document.getElementById('rzp-button1').onclick = function(e){
    rzp1.open();
    e.preventDefault();
}
</script>
@endsection
