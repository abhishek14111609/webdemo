@extends('layouts.master')

@section('title', 'Your Wishlist - Eternal Diamonds')

@push('styles')
<style>
    .wishlist-hero {
        background: linear-gradient(120deg, #fffbe6 60%, #f9f9f9 100%);
        padding: 4rem 0 2rem 0;
        text-align: center;
        position: relative;
    }

    .wishlist-hero h1 {
        font-size: 2.5rem;
        font-weight: 800;
        color: #1a1a1a;
        margin-bottom: 1.2rem;
        letter-spacing: 1px;
    }

    .wishlist-section {
        padding: 2rem 0 3rem 0;
    }

    .wishlist-empty {
        text-align: center;
        margin: 4rem 0;
        color: #bfa13a;
    }

    .wishlist-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 2rem;
    }

    .wishlist-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 4px 24px rgba(212, 175, 55, 0.07);
        padding: 1.5rem 1rem;
        text-align: center;
        border: 1.5px solid #f3e6b3;
        position: relative;
    }

    .wishlist-card img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 10px;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(212, 175, 55, 0.07);
    }

    .wishlist-card h5 {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 0.5rem;
    }

    .wishlist-card .price {
        color: #bfa13a;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .btn-gold {
        background: linear-gradient(90deg, #d4af37 0%, #fffbe6 100%);
        color: #1a1a1a;
        border: none;
        font-weight: 700;
        padding: 0.5rem 1.5rem;
        border-radius: 8px;
        transition: background 0.3s, color 0.3s;
        box-shadow: 0 2px 8px rgba(212, 175, 55, 0.09);
        margin-right: 0.5rem;
    }

    .btn-gold:hover {
        background: #d4af37;
        color: #fff;
    }

    .btn-remove {
        background: none;
        border: none;
        color: #d9534f;
        font-size: 1.2rem;
        margin-left: 0.5rem;
        transition: color 0.2s;
    }

    .btn-remove:hover {
        color: #a94442;
    }

    @media (max-width: 768px) {
        .wishlist-hero h1 { font-size: 1.5rem; }
        .wishlist-section { padding: 1rem 0 2rem 0; }
        .wishlist-grid { gap: 1rem; }
    }
</style>
@endpush

@section('content')
<div class="wishlist-hero">
    <h1>Your Wishlist</h1>
</div>
<section class="wishlist-section container">
    @if(!Auth::check() || $wishlistItems->isEmpty())
    <div class="wishlist-empty" id="wishlist-empty">
        <i class="far fa-heart fa-3x mb-3"></i>
        <h4 class="mb-3">Your wishlist is empty</h4>
        <a href="{{ route('shop') }}" class="btn btn-gold">Browse Shop</a>
    </div>
    @else
    <div class="wishlist-grid" id="wishlist-items">
        @foreach($wishlistItems as $wishlistItem)
        @php($product = $wishlistItem->product)
        <div class="wishlist-card">
            <img src="{{ $product?->image_url ?? 'https://via.placeholder.com/100?text=No+Image' }}" alt="{{ $product?->name }}">
            <h5>{{ $product?->name ?? 'Unavailable product' }}</h5>
            <div class="price">₹{{ number_format((float)($product->price ?? 0), 2) }}</div>
            <div class="d-flex justify-content-center mt-2">
                @if($product)
                <form action="{{ route('wishlist.moveToCart', $wishlistItem->id) }}" method="POST" class="me-2">
                    @csrf
                    <button type="submit" class="btn btn-gold">Add to Cart</button>
                </form>
                @endif
                <form action="{{ route('wishlist.remove', $wishlistItem->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-remove" title="Remove"><i class="fas fa-trash"></i></button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</section>
@endsection