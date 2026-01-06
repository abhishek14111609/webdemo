@extends('layouts.master')

@section('title', 'Order History')

@push('styles')
<style>
    .orders-hero {
        background: linear-gradient(120deg, #fffbe6 60%, #f9f9f9 100%);
        padding: 4rem 0 2rem 0;
        text-align: center;
        position: relative;
    }
    .orders-hero h1 {
        font-size: 2.5rem;
        font-weight: 800;
        color: #1a1a1a;
        margin-bottom: 1.2rem;
        letter-spacing: 1px;
    }
    .orders-section {
        padding: 2rem 0 3rem 0;
    }
    .order-table th, .order-table td {
        vertical-align: middle;
        text-align: center;
    }
    .order-table th {
        background: #fffbe6;
        color: #bfa13a;
        font-weight: 700;
        border-top: none;
    }
    .order-table td {
        background: #fff;
        border-top: none;
    }
    .empty-orders {
        text-align: center;
        padding: 3rem;
        background: #f9f9f9;
        border-radius: 8px;
        margin: 2rem 0;
    }
    .empty-orders h3 {
        margin-bottom: 1rem;
        color: #666;
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
    @media (max-width: 768px) {
        .orders-hero h1 { font-size: 1.5rem; }
        .orders-section { padding: 1rem 0 2rem 0; }
    }
</style>
@endpush

@section('content')
<div class="orders-hero">
    <h1>Order History</h1>
</div>
<section class="orders-section container">
    @if(session('success'))
    <div class="alert alert-success mb-4">
        {{ session('success') }}
    </div>
    @endif
    
    @if($orders->isEmpty())
    <div class="empty-orders">
        <h3>You haven't placed any orders yet</h3>
        <p>Browse our products and place your first order!</p>
        <a href="{{ route('shop') }}" class="btn btn-primary mt-3">Shop Now</a>
    </div>
    @else
    <div class="table-responsive">
        <table class="table order-table align-middle">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Date</th>
                    <th>Items</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->created_at->format('Y-m-d') }}</td>
                 
                    <td>
                        @php
                            $statusClass = 'status-' . str_replace('_', '-', $order->status);
                        @endphp
                        <span class="status-badge {{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
                    </td>
                    <td>₹{{ number_format($order->total, 2) }}</td>
                    <td>
                        <a href="{{ route('orders', $order->order_number) }}" class="btn btn-sm btn-primary">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</section>
@endsection