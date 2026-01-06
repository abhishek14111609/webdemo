@extends('layouts.master')

@section('title', 'Checkout - Eternal Diamonds')

@push('styles')
<style>
/* (use your existing checkout CSS here; shortened for brevity) */
.checkout-hero { background: linear-gradient(120deg, #fffbe6 60%, #f9f9f9 100%); padding: 3rem 0; text-align:center; }
.order-summary, .shipping-form { background:#fff; border-radius:12px; padding:1.6rem; border:1.5px solid #f3e6b3; }
.btn-gold { background: linear-gradient(90deg, #d4af37 0%, #fffbe6 100%); color:#1a1a1a; font-weight:700; }
.product-image { width:60px; height:60px; object-fit:cover; border-radius:8px; margin-right:1rem; }
</style>
@endpush

@section('content')
<div class="checkout-hero">
    <h1>Checkout</h1>
</div>

<section class="container checkout-section">
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="shipping-form">
                <h3 class="mb-4">Shipping Information</h3>

                <form id="checkout-form" action="{{ route('checkout.razorpay.payment') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name">Full Name *</label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="phone">Phone *</label>
                        <input type="tel" id="phone" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="address">Shipping Address *</label>
                        <textarea id="address" name="address" class="form-control" rows="3" required>{{ old('address') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="country">Country *</label>
                        <select id="country" name="country" class="form-control" required>
                            <option value="">Select Country</option>
                            <option value="India" {{ old('country') == 'India' ? 'selected' : '' }}>India</option>
                            <option value="USA" {{ old('country') == 'USA' ? 'selected' : '' }}>USA</option>
                            <option value="UK" {{ old('country') == 'UK' ? 'selected' : '' }}>UK</option>
                            <option value="Canada" {{ old('country') == 'Canada' ? 'selected' : '' }}>Canada</option>
                            <option value="Australia" {{ old('country') == 'Australia' ? 'selected' : '' }}>Australia</option>
                        </select>
                    </div>

                    <button type="button" id="pay-button" class="btn btn-gold w-100 mt-3">
                        Pay Securely with Razorpay
                    </button>

                    <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary w-100 mt-2">Back to Cart</a>

                    {{-- Hidden inputs for razorpay response will be appended by JS --}}
                </form>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="order-summary">
                <h4>Order Summary</h4>
                <div class="mb-3">
                    @foreach($cart->items as $item)
                        <div class="d-flex align-items-center mb-2">
                            @if($item->product->image)
                                <img src="{{ asset('storage/' . $item->product->image) }}" class="product-image" alt="">
                            @else
                                <div class="bg-light product-image d-flex align-items-center justify-content-center">No image</div>
                            @endif
                            <div class="flex-grow-1">
                                <div>{{ $item->product->name }}</div>
                                <small>Qty: {{ $item->quantity }}</small>
                            </div>
                            <div class="fw-bold">₹{{ number_format($item->subtotal, 2) }}</div>
                        </div>
                    @endforeach
                </div>
                <hr>
                <div class="d-flex justify-content-between"><span>Subtotal</span><span>₹{{ number_format($cart->subtotal,2) }}</span></div>
                @if($cart->tax > 0)
                    <div class="d-flex justify-content-between"><span>Tax</span><span>₹{{ number_format($cart->tax,2) }}</span></div>
                @endif
                <div class="d-flex justify-content-between"><span>Shipping</span><span>{{ $cart->shipping_cost>0 ? '₹'.number_format($cart->shipping_cost,2) : 'Free' }}</span></div>
                <hr>
                <div class="d-flex justify-content-between fw-bold fs-5"><span>Total</span><span>₹{{ number_format($cart->total,2) }}</span></div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
document.getElementById('pay-button').addEventListener('click', function (e) {
    e.preventDefault();

    // basic client-side validation
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const address = document.getElementById('address').value.trim();
    const country = document.getElementById('country').value;

    if (!name || !email || !phone || !address || !country) {
        alert('Please fill in all required fields.');
        return;
    }

    const options = {
        key: "{{ config('services.razorpay.key') }}",
        amount: "{{ intval($cart->total * 100) }}", // paise
        currency: "INR",
        name: "Eternal Diamonds",
        description: "Order Payment",
        image: "{{ asset('images/logo.png') }}",
      // server generated order id
        handler: function (response) {
            // append razorpay fields to the form and submit
            const form = document.getElementById('checkout-form');

            ['razorpay_payment_id', 'razorpay_order_id', 'razorpay_signature'].forEach(function (field) {
                var existing = form.querySelector('input[name="'+field+'"]');
                if (existing) existing.remove();
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = field;
                input.value = response[field];
                form.appendChild(input);
            });

            form.submit();
        },
        prefill: {
            name: name,
            email: email,
            contact: phone
        },
        theme: {
            color: "#d4af37"
        }
    };

    var rzp = new Razorpay(options);
    rzp.open();
});
</script>
@endpush
