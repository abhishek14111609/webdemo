@extends('layouts.master')

@section('title', 'Bill - Eternal Diamonds')

@section('content')
<div class="container my-5">
    <h2>Order Bill</h2>
    <p>Order Number: {{ $order->order_number }}</p>
    <p>Name: {{ $order->name }}</p>
    <p>Email: {{ $order->email }}</p>
    <p>Phone: {{ $order->phone }}</p>
    <p>Address: {{ $order->address }}, {{ $order->country }}</p>
    <hr>
    <table class="table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->product_name }}</td>
                <td>₹{{ number_format($item->price,2) }}</td>
                <td>{{ $item->quantity }}</td>
                <td>₹{{ number_format($item->subtotal,2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <hr>
    <p>Subtotal: ₹{{ number_format($order->subtotal,2) }}</p>
    <p>Tax: ₹{{ number_format($order->tax,2) }}</p>
    <p><strong>Total: ₹{{ number_format($order->total,2) }}</strong></p>

    <a href="{{ route('checkout.bill.download', $order->id) }}" class="btn btn-primary mt-3" >Download PDF</a>
    <a href="{{ url('/') }}" class="btn btn-secondary mt-3">Back to Home</a>
</div>
@endsection
