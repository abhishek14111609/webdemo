@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0">Order #{{ $order->order_number }}</h5>
                        <span class="badge bg-warning text-dark">{{ ucfirst($order->status) }}</span>
                    </div>
                    <div class="card-body p-4">
                        <!-- Progress Bar -->
                        <div class="position-relative m-4">
                            <div class="progress" style="height: 2px;">
                                @php
                                    $status = $order->status;
                                    $width = 0;
                                    if ($status == 'processing')
                                        $width = 33;
                                    elseif ($status == 'shipped')
                                        $width = 66;
                                    elseif ($status == 'delivered')
                                        $width = 100;
                                @endphp
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $width }}%;"></div>
                            </div>
                            <style>
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
                                    background: #198754;
                                    color: white;
                                }
                            </style>
                            <div class="step-dot {{ in_array($status, ['pending', 'processing', 'shipped', 'delivered']) ? 'active' : '' }}"
                                style="left: 0%;">1</div>
                            <div class="step-dot {{ in_array($status, ['processing', 'shipped', 'delivered']) ? 'active' : '' }}"
                                style="left: 33%;">2</div>
                            <div class="step-dot {{ in_array($status, ['shipped', 'delivered']) ? 'active' : '' }}"
                                style="left: 66%;">3</div>
                            <div class="step-dot {{ in_array($status, ['delivered']) ? 'active' : '' }}"
                                style="left: 100%;">4</div>
                        </div>
                        <div class="d-flex justify-content-between mb-5">
                            <small>Order Placed</small>
                            <small>Processing</small>
                            <small>Shipped</small>
                            <small>Delivered</small>
                        </div>

                        @if($order->tracking_number)
                            <div class="alert alert-info">
                                <i class="fas fa-truck me-2"></i> Tracking Number:
                                <strong>{{ $order->tracking_number }}</strong>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <h5>Shipping Address</h5>
                                <p class="text-muted">
                                    {{ $order->name }}<br>
                                    {{ $order->address }}<br>
                                    {{ $order->city }}, {{ $order->state }} {{ $order->zip }}<br>
                                    {{ $order->country }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h5>Order Items</h5>
                                <ul class="list-group list-group-flush">
                                    @foreach($order->items as $item)
                                        <li class="list-group-item d-flex justify-content-between px-0">
                                            <span>{{ $item->quantity }}x {{ $item->product->name ?? 'Product' }}</span>
                                            <span>₹{{ number_format($item->subtotal, 2) }}</span>
                                        </li>
                                    @endforeach
                                    <li class="list-group-item d-flex justify-content-between px-0 fw-bold">
                                        <span>Total</span>
                                        <span>₹{{ number_format($order->total, 2) }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h5 class="mt-4">Order History</h5>
                        <ul class="list-group list-group-flush">
                            @foreach($order->statusHistory as $history)
                                <li class="list-group-item px-0">
                                    <small class="text-muted">{{ $history->created_at->format('M d, H:i') }}</small>
                                    <strong>{{ ucfirst($history->status) }}</strong> - {{ $history->notes ?? 'Status updated' }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="text-center">
                    <a href="{{ route('orders.track') }}" class="btn btn-outline-dark">Track Another Order</a>
                </div>
            </div>
        </div>
    </div>
@endsection