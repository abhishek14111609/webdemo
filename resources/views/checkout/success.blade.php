@extends('layouts.master')

@section('title', 'Order Success')

@section('content')
<div class="container py-5">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($order)
        <div class="card p-4">
            <h3>Thank you — your payment is successful!</h3>
            <p>Order #: <strong>{{ $order->id }}</strong></p>
            <p>Amount paid: <strong>₹{{ number_format($order->amount, 2) }}</strong></p>
            <p>Payment ID: <strong>{{ $order->razorpay_payment_id }}</strong></p>

            <a href="{{ route('order.invoice', $order->id) }}" class="btn btn-primary mt-3">View Invoice</a>
            <a href="{{ route('order.invoice.download', $order->id) }}" class="btn btn-outline-secondary mt-3">Download Invoice (PDF)</a>
        </div>
    @else
        <div class="alert alert-info">No order found in session. Please check your orders in account.</div>
    @endif
</div>
@endsection
