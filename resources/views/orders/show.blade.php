@extends('layouts.master')

@section('title', 'Order #' . $order->order_number)

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

        .status-shipped {
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .status-delivered {
            background-color: #d4edda;
            color: #155724;
        }

        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }

        .step-dot {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #e9ecef;
            position: absolute;
            top: -14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .step-dot.active {
            background: #1a1a1a;
            color: white;
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
                <span class="status-badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
            </div>
        </div>

        <!-- Progress Tracker -->
        @if(!in_array($order->status, ['cancelled', 'refunded']))
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="position-relative m-2">
                        <div class="progress" style="height: 2px;">
                            @php
                                $width = 0;
                                if ($order->status == 'processing')
                                    $width = 33;
                                elseif ($order->status == 'shipped')
                                    $width = 66;
                                elseif ($order->status == 'delivered')
                                    $width = 100;
                            @endphp
                            <div class="progress-bar bg-dark" role="progressbar" style="width: {{ $width }}%;"></div>
                        </div>
                        <div class="step-dot {{ in_array($order->status, ['pending', 'processing', 'shipped', 'delivered']) ? 'active' : '' }}"
                            style="left: 0%;">1</div>
                        <div class="step-dot {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'active' : '' }}"
                            style="left: 33%;">2</div>
                        <div class="step-dot {{ in_array($order->status, ['shipped', 'delivered']) ? 'active' : '' }}"
                            style="left: 66%;">3</div>
                        <div class="step-dot {{ in_array($order->status, ['delivered']) ? 'active' : '' }}" style="left: 100%;">
                            4</div>
                    </div>
                    <div class="d-flex justify-content-between mt-3 text-muted small">
                        <span>Placed</span>
                        <span>Processing</span>
                        <span>Shipped</span>
                        <span>Delivered</span>
                    </div>
                </div>
            </div>
        @endif

        @if($order->tracking_number)
            <div class="alert alert-info mb-4">
                <i class="fas fa-shipping-fast me-2"></i> Tracking Number: <strong>{{ $order->tracking_number }}</strong>
            </div>
        @endif

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Shipping Address</h5>
                        <p class="card-text text-muted">
                            {{ $order->name }}<br>
                            {{ $order->address }}<br>
                            {{ $order->city }}, {{ $order->state }} {{ $order->zip }}<br>
                            {{ $order->country }}<br>
                            Phone: {{ $order->phone }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Order Summary</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>₹{{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax</span>
                            <span>₹{{ number_format($order->tax, 2) }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold">
                            <span>Total</span>
                            <span>₹{{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <h4 class="mb-3">Items</h4>
        <div class="card border-0 shadow-sm mb-4">
            <ul class="list-group list-group-flush">
                @foreach($order->orderItems as $item) // Changed from orderItems to items based on relation
                    <li class="list-group-item p-3">
                        <div class="d-flex align-items-center">
                            <img src="{{ $item->product->image_url ?? asset('images/no-image.jpg') }}" alt=""
                                style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;" class="me-3">
                            <div class="flex-grow-1">
                                <h6 class="mb-0">{{ $item->product_name }}</h6>
                                <small class="text-muted">Qty: {{ $item->quantity }}</small>
                            </div>
                            <div class="fw-bold">₹{{ number_format($item->subtotal, 2) }}</div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        @if($order->statusHistory->count() > 0)
            <h4 class="mb-3">Order History</h4>
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        @foreach($order->statusHistory as $history)
                            <li class="mb-3 pb-3 border-bottom last-no-border">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ ucfirst($history->status) }}</strong>
                                    <small class="text-muted">{{ $history->created_at->format('M d, Y h:i A') }}</small>
                                </div>
                                @if($history->notes)
                                    <p class="mb-0 text-muted small mt-1">{{ $history->notes }}</p>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="text-center mt-4">
            <a href="{{ route('orders.index') }}" class="btn btn-outline-dark">Back to My Orders</a>
        </div>
    </div>
@endsection