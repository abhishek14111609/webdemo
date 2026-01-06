@extends('layouts.master')

@section('title', $title)

@push('styles')
<style>
    .order-detail-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }
    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }
    .order-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    .order-subtitle {
        color: #666;
        margin-bottom: 0;
    }
    .order-meta {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    .meta-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.8rem;
        flex-wrap: wrap;
    }
    .meta-label {
        font-weight: 600;
        min-width: 150px;
    }
    .meta-value {
        color: #333;
    }
    .order-items {
        margin-bottom: 2rem;
    }
    .item-card {
        display: flex;
        border: 1px solid #eee;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    .item-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 4px;
        margin-right: 1rem;
    }
    .item-details {
        flex-grow: 1;
    }
    .item-name {
        font-weight: 600;
        margin-bottom: 0.3rem;
    }
    .item-price {
        color: #666;
        font-size: 0.9rem;
    }
    .item-quantity {
        color: #666;
        font-size: 0.9rem;
    }
    .item-subtotal {
        font-weight: 600;
        text-align: right;
        align-self: center;
        min-width: 100px;
    }
    .order-summary {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1.5rem;
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.8rem;
    }
    .summary-row.total {
        font-weight: 700;
        font-size: 1.1rem;
        border-top: 1px solid #ddd;
        padding-top: 0.8rem;
        margin-top: 0.8rem;
    }
    .action-buttons {
        display: flex;
        justify-content: space-between;
        margin-top: 2rem;
    }
    .status-badge {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: capitalize;
    }
    .status-pending {
        background-color: #fff3cd;
        color: #856404;
    }
    .status-processing {
        background-color: #cce5ff;
        color: #004085;
    }
    .status-completed {
        background-color: #d4edda;
        color: #155724;
    }
    .status-cancelled {
        background-color: #f8d7da;
        color: #721c24;
    }
    .status-awaiting-payment {
        background-color: #e2e3e5;
        color: #383d41;
    }
    .status-payment-failed {
        background-color: #f8d7da;
        color: #721c24;
    }
</style>
@endpush

@section('content')
<div class="order-detail-container">
    <div class="order-header">
        <div>
            <h1 class="order-title">Order Details</h1>
            <p class="order-subtitle">Order #{{ $order->order_number }}</p>
        </div>
        <div>
            @php
                $statusClass = 'status-' . str_replace('_', '-', $order->status);
            @endphp
            <span class="status-badge {{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
        </div>
    </div>
    
    <div class="order-meta">
        <div class="meta-row">
            <span class="meta-label">Order Date:</span>
            <span class="meta-value">{{ $order->created_at->format('F d, Y h:i A') }}</span>
        </div>
        <div class="meta-row">
            <span class="meta-label">Payment Method:</span>
            <span class="meta-value">{{ ucfirst($order->payment_method) }}</span>
        </div>
        <div class="meta-row">
            <span class="meta-label">Shipping Address:</span>
            <span class="meta-value">{{ $order->address }}, {{ $order->city }}, {{ $order->state }} {{ $order->zip }}, {{ $order->country }}</span>
        </div>
        <div class="meta-row">
            <span class="meta-label">Contact:</span>
            <span class="meta-value">{{ $order->phone }}</span>
        </div>
    </div>
    
    <h2>Order Items</h2>
    <div class="order-items">
        @foreach($order->orderItems as $item)
        <div class="item-card">
            <img src="{{ $item->product ? asset($item->product->image) : asset('images/no-image.jpg') }}" alt="{{ $item->product_name }}" class="item-image">
            <div class="item-details">
                <h3 class="item-name">{{ $item->product_name }}</h3>
                <p class="item-price">Price: ₹{{ number_format($item->price, 2) }}</p>
                <p class="item-quantity">Quantity: {{ $item->quantity }}</p>
            </div>
            <div class="item-subtotal">
                ₹{{ number_format($item->subtotal, 2) }}
            </div>
        </div>
        @endforeach
    </div>
    
    <div class="order-summary">
        <div class="summary-row">
            <span>Subtotal:</span>
            <span>₹{{ number_format($order->subtotal, 2) }}</span>
        </div>
        <div class="summary-row">
            <span>Tax:</span>
            <span>₹{{ number_format($order->tax, 2) }}</span>
        </div>
        <div class="summary-row total">
            <span>Total:</span>
            <span>₹{{ number_format($order->total, 2) }}</span>
        </div>
    </div>
    
    <div class="action-buttons">
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">Back to Orders</a>
        <a href="{{ route('home') }}" class="btn btn-primary">Continue Shopping</a>
    </div>
</div>
@endsection