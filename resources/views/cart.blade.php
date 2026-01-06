@extends('layouts.master')

@section('title', 'Your Cart')

@push('styles')
<style>
    .cart-hero {
        background: linear-gradient(120deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 3rem 0;
        margin-bottom: 2rem;
    }
    .cart-table th {
        background-color: #f8f9fa;
    }
    .quantity-controls {
        display: flex;
        align-items: center;
    }
    .quantity-controls .btn {
        padding: 0.25rem 0.5rem;
    }
    .quantity-input {
        width: 60px;
        text-align: center;
        margin: 0 6px;
    }
    @media (max-width: 576px) {
        .cart-table thead { display: none; }
        .cart-table tr { display: block; margin-bottom: 1rem; }
        .cart-table td { display: flex; justify-content: space-between; padding: .75rem; border-top: 1px solid #f1f1f1; }
        .cart-table td:first-child { border-top: none; }
    }
</style>
@endpush

@section('content')
<div class="cart-hero">
    <div class="container">
        <h1 class="mb-0">Shopping Cart</h1>
    </div>
</div>

<div class="container py-4">
    @if($cart->items->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cart->items as $item)
                    <tr data-item-id="{{ $item->id }}">
                        <td data-label="Product">
                            <div class="d-flex align-items-center">
                                <img src="{{ $item->product?->image_url ?? asset('images/placeholder.jpg') }}"
                                     alt="{{ $item->product?->name }}"
                                     class="img-thumbnail me-3"
                                     style="width: 80px; height: 80px; object-fit: cover;">
                                <div>
                                    <h6 class="mb-1">{{ $item->product?->name }}</h6>
                                    @if($item->options)
                                        <small class="text-muted">
                                            @foreach($item->options as $key => $value)
                                                {{ ucfirst($key) }}: {{ $value }}@if(!$loop->last), @endif
                                            @endforeach
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td data-label="Price">₹{{ number_format($item->price, 2) }}</td>
                        <td data-label="Quantity">
                            <form action="{{ route('cart.update', $item->id) }}" method="POST" data-item-id="{{ $item->id }}" class="m-0">
                                @csrf
                                @method('PUT')
                                <div class="quantity-controls">
                                    <button type="button" class="btn btn-outline-secondary btn-sm quantity-decrement" aria-label="Decrease">-</button>
                                    <input type="number"
                                           name="quantity"
                                           class="form-control form-control-sm quantity-input"
                                           value="{{ $item->quantity }}"
                                           min="1"
                                           max="{{ (int)($item->product?->stock ?? 9999) }}"
                                           data-price="{{ $item->price }}">
                                    <button type="button" class="btn btn-outline-secondary btn-sm quantity-increment" aria-label="Increase">+</button>
                                </div>
                                <button type="submit" class="btn btn-link btn-sm p-0 mt-1" style="font-size: 0.85rem;">Update</button>
                            </form>
                        </td>
                        <td class="subtotal-{{ $item->id }}" data-label="Subtotal">₹{{ number_format($item->subtotal, 2) }}</td>
                        <td class="text-end">
                            <form action="{{ route('cart.destroy', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link text-danger p-0" aria-label="Remove item">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="row mt-4">
            <div class="col-md-6 d-flex align-items-center gap-2">
                <a href="{{ route('shop') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                </a>
                <form action="{{ route('cart.clear') }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="fas fa-trash me-2"></i>Clear Cart
                    </button>
                </form>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Order Summary</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>₹{{ number_format($cart->subtotal, 2) }}</span>
                        </div>
                        @if($cart->discount > 0)
                        <div class="d-flex justify-content-between mb-2">
                            <span>Discount:</span>
                            <span class="text-danger">-₹{{ number_format($cart->discount, 2) }}</span>
                        </div>
                        @endif
                        @if($cart->shipping_cost > 0)
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping:</span>
                            <span>₹{{ number_format($cart->shipping_cost, 2) }}</span>
                        </div>
                        @else
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping:</span>
                            <span class="text-success">Free</span>
                        </div>
                        @endif
                        @if($cart->tax > 0)
                        <div class="d-flex justify-content-between mb-3">
                            <span>Tax:</span>
                            <span>₹{{ number_format($cart->tax, 2) }}</span>
                        </div>
                        @endif
                        <div class="d-flex justify-content-between fw-bold fs-5">
                            <span>Total:</span>
                            <span class="cart-total">₹{{ number_format($cart->total, 2) }}</span>
                        </div>
                        <a href="{{ route('checkout') }}" class="btn btn-primary w-100 mt-3">
                            Proceed to Checkout <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-4x text-muted mb-4"></i>
            <h3>Your cart is empty</h3>
            <p class="text-muted mb-4">Looks like you haven't added any items to your cart yet.</p>
            <a href="{{ route('shop') }}" class="btn btn-primary">
                <i class="fas fa-shopping-bag me-2"></i>Continue Shopping
            </a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.quantity-increment').forEach(btn => {
            btn.addEventListener('click', function() {
                const input = this.parentElement.querySelector('.quantity-input');
                const max = parseInt(input.getAttribute('max')) || 9999;
                let val = parseInt(input.value) || 1;
                if (val < max) input.value = val + 1;
            });
        });
        document.querySelectorAll('.quantity-decrement').forEach(btn => {
            btn.addEventListener('click', function() {
                const input = this.parentElement.querySelector('.quantity-input');
                let val = parseInt(input.value) || 1;
                if (val > 1) input.value = val - 1;
            });
        });
    });
</script>
@endpush