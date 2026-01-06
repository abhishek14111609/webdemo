@extends('layouts.master')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">{{ $title ?? 'Your Shopping Cart' }}</h1>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            
            @if($cart && $cart->items->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->product->image)
                                                <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                     alt="{{ $item->product->name }}" 
                                                     class="img-thumbnail me-3" 
                                                     style="width: 80px; height: 80px; object-fit: cover;">
                                            @else
                                                <div class="bg-light me-3" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                                                    <span class="text-muted">No image</span>
                                                </div>
                                            @endif
                                            <div>
                                                <h5 class="mb-0"><a href="{{ route('product.show', $item->product->id) }}">{{ $item->product->name }}</a></h5>
                                                @if($item->options && count($item->options) > 0)
                                                    <small class="text-muted">
                                                        @foreach($item->options as $key => $value)
                                                            <span>{{ ucfirst($key) }}: {{ $value }}</span>@if(!$loop->last), @endif
                                                        @endforeach
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ number_format($item->price, 2) }}</td>
                                    <td style="width: 150px;">
                                        <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex update-cart-form">
                                            @csrf
                                            @method('PUT')
                                            <input type="number" 
                                                   name="quantity" 
                                                   value="{{ $item->quantity }}" 
                                                   min="1" 
                                                   max="{{ $item->product->stock }}" 
                                                   class="form-control form-control-sm" 
                                                   style="width: 70px;">
                                            <button type="submit" class="btn btn-sm btn-outline-primary ms-2">
                                                <i class="fas fa-sync-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td>{{ number_format($item->subtotal, 2) }}</td>
                                    <td>
                                        <form action="{{ route('cart.destroy', $item->id) }}" method="POST" class="d-inline remove-cart-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
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
                    <div class="col-md-6">
                        <a href="{{ route('shop') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                        </a>
                        <form action="{{ route('cart.clear') }}" method="POST" class="d-inline clear-cart-form">
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
                                    <span>{{ number_format($cart->subtotal, 2) }}</span>
                                </div>
                                @if($cart->tax > 0)
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Tax:</span>
                                    <span>{{ number_format($cart->tax, 2) }}</span>
                                </div>
                                @endif
                                @if($cart->discount > 0)
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Discount:</span>
                                    <span>-{{ number_format($cart->discount, 2) }}</span>
                                </div>
                                @endif
                                @if($cart->shipping_cost > 0)
                                <div class="d-flex justify-content-between mb-3">
                                    <span>Shipping:</span>
                                    <span>{{ number_format($cart->shipping_cost, 2) }}</span>
                                </div>
                                @else
                                <div class="d-flex justify-content-between mb-3">
                                    <span>Shipping:</span>
                                    <span>Free</span>
                                </div>
                                @endif
                                <div class="d-flex justify-content-between fw-bold fs-5">
                                    <span>Total:</span>
                                    <span>{{ number_format($cart->total, 2) }}</span>
                                </div>
                                <a href="{{ route('checkout.index') }}" class="btn btn-primary w-100 mt-3">
                                    Proceed to Checkout <i class="fas fa-arrow-right ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

<script src="{{ asset('js/cart.js') }}"></script>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                      <i class="fas fa-shopping-cart fa-4x text-muted mb-4"></i>
                      <h3>Your cart is empty</h3>
                      <p class="text-muted">Looks like you haven't added any products to your cart yet.</p>
                      <a href="{{ route('shop') }}" class="btn btn-primary">
                          <i class="fas fa-shopping-bag me-2"></i> Continue Shopping</a>
                  </div>
              @endif
          </div>
      </div>
                    </div>

@if(session('success'))
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-success text-white">
                <strong class="me-auto">Success</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                {{ session('success') }}
            </div>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-danger text-white">
                <strong class="me-auto">Error</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                {{ session('error') }}
            </div>
        </div>
    </div>
@endif

@endsection

@push('scripts')
<script>
    // Auto-hide toasts after 3 seconds
    document.addEventListener('DOMContentLoaded', function() {
        var toastElList = [].slice.call(document.querySelectorAll('.toast'));
        var toastList = toastElList.map(function(toastEl) {
            return new bootstrap.Toast(toastEl, {delay: 3000});
        });
    });
</script>
@endpush
