@extends('layouts.master')

@section('title', $title . ' | ' . config('app.name'))

@push('styles')
<style>
    .collection-hero {
        position: relative;
        background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)),
        url('{{ $collection->image ? asset('storage/' . $collection->image) : 'https://via.placeholder.com/1200x600'}}');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        color: white;
        padding: 8rem 0 6rem;
        margin-bottom: 4rem;
        overflow: hidden;
        }

        .collection-hero h1 {
            font-size: 3rem;
            font-weight: 300;
            letter-spacing: 2px;
            margin-bottom: 1rem;
            text-transform: uppercase;
        }

        .collection-hero p {
            font-size: 1.25rem;
            max-width: 700px;
            margin: 0 auto 2rem;
            opacity: 0.9;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
            margin-bottom: 4rem;
        }

        .product-card {
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            position: relative;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .product-image-container {
            position: relative;
            padding-top: 120%;
            overflow: hidden;
            background: #f9f9f9;
        }

        .product-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 1.2s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .product-card:hover .product-image {
            transform: scale(1.05);
        }

        .product-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #d4af37;
            color: white;
            padding: 0.35rem 1rem;
            border-radius: 30px;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 1px;
            z-index: 2;
            text-transform: uppercase;
        }

        .product-details {
            padding: 1.5rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .product-category {
            font-size: 0.8rem;
            color: #888;
            margin-bottom: 0.25rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .product-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #333;
            line-height: 1.4;
        }

        .product-price {
            font-size: 1.1rem;
            font-weight: 700;
            color: #222;
            margin: 0.5rem 0;
        }

        .product-price .original {
            text-decoration: line-through;
            color: #999;
            font-size: 0.9rem;
            margin-right: 0.5rem;
        }

        .btn-view-details {
            display: inline-block;
            margin-top: auto;
            padding: 0.75rem 1.5rem;
            background: #000;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s ease;
            text-align: center;
        }

        .btn-view-details:hover {
            background: #333;
            color: white;
        }
</style>
@endpush

@section('content')
<!-- Collection Hero Section -->
<section class="collection-hero">
    <div class="container text-center">
        <h1>{{ $collection->title ?? 'Collection Title' }}</h1>
        <p>{{ $collection->description ?? 'Collection Description'  }}</p>
    </div>
</section>

<!-- Main Content -->
<div class="container">
    <!-- Products Grid -->
    <div class="product-grid">
        @forelse($products as $product)
        <div class="product-card">
            <div class="product-image-container">
                <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://images.unsplash.com/photo-1605100804763-247f67b3557e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80' }}"
                    alt="{{ $product->name }}" class="product-image">
                @if($product->badge)
                <span class="product-badge">{{ $product->badge }}</span>
                @endif
            </div>
            <div class="product-details">
                <div class="product-category">{{ $product->category->name ?? 'Uncategorized' }}</div>
                <h3 class="product-title">{{ $product->name }}</h3>
                <div class="mb-1">
                    @if((int)($product->stock ?? 0) > 0)
                        <span class="badge bg-success">In Stock</span>
                    @else
                        <span class="badge bg-danger">Out of Stock</span>
                    @endif
                </div>
                <div class="d-flex align-items-center mb-1">
                    <div class="star-rating">
                        @for ($i = 1; $i <= 5; $i++) @if ($i <=round($product->average_rating))
                            <i class="fas fa-star" style="color: #ffc107; font-size: 0.8rem;"></i>
                            @else
                            <i class="far fa-star" style="color: #ddd; font-size: 0.8rem;"></i>
                            @endif
                            @endfor
                    </div>
                    <span
                        style="font-size: 0.7rem; color: #666; margin-left: 5px;">({{ $product->reviews_count }})</span>
                </div>
                <div class="product-price">
                    @if($product->original_price && $product->original_price > $product->price)
                    <span class="original">${{ number_format($product->original_price, 2) }}</span>
                    @endif
                    <span class="current-price">${{ number_format($product->price, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mt-3">
                    @auth
                        @if((int)($product->stock ?? 0) > 0)
                            <form action="{{ route('cart.add', ['product' => $product->id]) }}" method="POST" class="me-2">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-dark btn-sm" title="Add to Cart">
                                    <i class="fas fa-shopping-cart"></i> Add to Cart
                                </button>
                            </form>
                        @else
                            <button type="button" class="btn btn-secondary btn-sm me-2" disabled>
                                <i class="fas fa-ban"></i> Out of Stock
                            </button>
                        @endif
                    @endauth
                    @guest
                        @if((int)($product->stock ?? 0) > 0)
                            <a href="{{ route('login') }}" class="btn btn-dark btn-sm me-2" title="Login to add to cart">
                                <i class="fas fa-sign-in-alt"></i> Login to Add
                            </a>
                        @else
                            <button type="button" class="btn btn-secondary btn-sm me-2" disabled>
                                <i class="fas fa-ban"></i> Out of Stock
                            </button>
                        @endif
                    @endguest
                     <form action="{{ route('wishlist.add', ['product' => $product->id]) }}" method="POST">
                         @csrf
                         <button type="submit" class="btn btn-outline-dark btn-sm" title="Add to Wishlist">
                             <i class="far fa-heart"></i> Wishlist
                         </button>
                     </form>
                 </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center">
            <h3>No products found in this collection</h3>
            <p>Check back later for new products.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection