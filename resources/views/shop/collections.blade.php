@extends('layouts.master')

@push('styles')
<style>
    .collections-hero {
        position: relative;
        background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)),
            url('https://images.unsplash.com/photo-1605100804763-247f67b3557e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        color: white;
        padding: 8rem 0 6rem;
        margin-bottom: 4rem;
        overflow: hidden;

    }


    .collections-hero h1 {
        font-size: 3rem;
        font-weight: 300;
        letter-spacing: 2px;
        margin-bottom: 1rem;
        margin-left: 19rem;
        text-transform: uppercase;
    }

    .collections-hero p {
        font-size: 1.25rem;
        max-width: 700px;
        margin: 0 auto 2rem;
        opacity: 0.9;
    }

    .collection-card {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 2rem;
        transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        height: 100%;
        min-height: 400px;
        background: #fff;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .collection-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 14px 28px rgba(0, 0, 0, 0.12), 0 10px 10px rgba(0, 0, 0, 0.08);
    }

    .collection-image {
        position: relative;
        width: 100%;
        padding-top: 100%;
        overflow: hidden;
    }

    .collection-image img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.8s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    .collection-card:hover .collection-image img {
        transform: scale(1.05);
    }

    .collection-content {
        padding: 1.5rem;
        text-align: center;
    }

    .collection-title {
        font-size: 1.5rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
        color: #222;
        letter-spacing: 0.5px;
    }

    .collection-description {
        color: #666;
        margin-bottom: 1.5rem;
        font-size: 0.95rem;
        line-height: 1.6;
    }

    .collection-count {
        display: inline-block;
        font-size: 0.85rem;
        color: #888;
        margin-bottom: 1.5rem;
        font-weight: 500;
    }

    .btn-view-collection {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.65rem 1.75rem;
        background: #000;
        color: white;
        border: none;
        border-radius: 30px;
        font-size: 0.9rem;
        font-weight: 500;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .btn-view-collection:hover {
        background: #333;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .btn-view-collection i {
        margin-left: 8px;
        transition: transform 0.3s ease;
    }

    .btn-view-collection:hover i {
        transform: translateX(4px);
    }

    .section-title {
        text-align: center;
        margin-bottom: 3.5rem;
        position: relative;
    }

    .section-title h2 {
        font-size: 2rem;
        font-weight: 300;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 1rem;
        color: #222;
    }

    .section-title .divider {
        width: 60px;
        height: 2px;
        background: #d4af37;
        margin: 0 auto;
    }

    .featured-section {
        padding: 5rem 0;
        background: #f9f9f9;
    }

    .featured-product {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
    }

    .featured-product:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .featured-image {
        position: relative;
        padding-top: 100%;
        overflow: hidden;
    }

    .featured-image img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .featured-product:hover .featured-image img {
        transform: scale(1.05);
    }

    .featured-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: #d4af37;
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        z-index: 2;
    }

    .featured-details {
        padding: 1.5rem;
        text-align: center;
    }

    .featured-category {
        font-size: 0.8rem;
        color: #888;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .featured-title {
        font-size: 1.1rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
        color: #333;
    }

    .featured-price {
        font-size: 1.2rem;
        font-weight: 600;
        color: #222;
        margin: 0.75rem 0;
    }

    .original-price {
        text-decoration: line-through;
        color: #999;
        font-size: 0.95rem;
        margin-right: 0.5rem;
    }

    /* Responsive adjustments */
    @media (max-width: 991.98px) {
        .collections-hero {
            padding: 6rem 0;
        }

        .collections-hero h1 {
            font-size: 2.5rem;
        }
    }

    @media (max-width: 767.98px) {
        .collections-hero {
            padding: 4rem 0;
        }

        .collections-hero h1 {
            font-size: 2rem;
        }

        .collections-hero p {
            font-size: 1rem;
            padding: 0 1rem;
        }

        .collection-card {
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }
    }
</style>
@endpush

@section('title', $title . ' | ' . config('app.name'))

@section('content')
<!-- Hero Section -->
<section class="collections-hero">
    <div class="container">
        <h1>Our Collections</h1>
        <p>Discover our carefully curated jewelry collections, each piece crafted with precision and designed to be
            treasured for generations.</p>
    </div>
</section>

<!-- Main Collections -->
<div class="container">
    <div class="section-title">
        <h2>Explore Our Collections</h2>
        <div class="divider"></div>
    </div>

    <div class="row">
        @forelse($collections as $collection)
        <div class="col-lg-4 col-md-6">
            <div class="collection-card">
                <div class="collection-image">
                    <img src="{{ $collection->image ? asset('storage/' . $collection->image) : 'https://images.unsplash.com/photo-1605100804763-247f67b3557e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80' }}"
                        alt="{{ $collection->title }} Collection">
                </div>
                <div class="collection-content">
                    <h3 class="collection-title">{{ $collection->title }} Collection</h3>
                    <p class="collection-description">{{ $collection->description }}</p>
                    <span class="collection-count">{{ $collection->products->count() }} Products</span>
                    <div>
                        <a href="{{ route('collection', ['slug' => $collection->slug]) }}" class="btn-view-collection">
                            Shop Now <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <h3>No collections available at the moment.</h3>
            <p>Please check back later for our latest collections.</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Featured Products Section -->
<section class="featured-section">
    <div class="container">
        <div class="section-title">
            <h2>Featured Pieces</h2>
            <div class="divider"></div>
            <p class="text-muted">Handpicked selections from our finest collections</p>
        </div>

        <div class="row">
            @php
            // Get featured products from the database
            $featuredProducts = App\Models\Product::where('is_featured', true)
            ->where('is_active', true)
            ->with('category')
            ->take(3)
            ->get();
            @endphp

            @forelse($featuredProducts as $product)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="featured-product">
                    <div class="featured-image">
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://images.unsplash.com/photo-1605100804763-247f67b3557e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80' }}"
                            alt="{{ $product->name }}">
                        @if($product->badge)
                        <span class="featured-badge">{{ $product->badge }}</span>
                        @endif
                    </div>
                    <div class="featured-details">
                        <div class="featured-category">{{ $product->category->name ?? 'Jewelry' }}</div>
                        <h4 class="featured-title">{{ $product->name }}</h4>
                        <div class="d-flex align-items-center justify-content-center mb-1">
                            <div class="star-rating">
                                @php
                                $rating = $product->average_rating;
                                $reviewCount = $product->reviews_count;
                                @endphp
                                @for ($i = 1; $i <= 5; $i++) @if ($i <=$rating) <i class="fas fa-star"
                                    style="color: #ffc107; font-size: 0.8rem;"></i>
                                    @else
                                    <i class="far fa-star" style="color: #ddd; font-size: 0.8rem;"></i>
                                    @endif
                                    @endfor
                            </div>
                            <span style="font-size: 0.7rem; color: #666; margin-left: 5px;">({{ $reviewCount }})</span>
                        </div>
                        <div class="featured-price">
                            @if($product->original_price)
                            <span class="original-price">${{ number_format($product->original_price, 2) }}</span>
                            @endif
                            ${{ number_format($product->price, 2) }}
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            <form action="{{ route('cart.add', ['product' => $product->id]) }}" method="POST" class="me-2">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-dark btn-sm" title="Add to Cart">
                                    <i class="fas fa-shopping-cart"></i> Add to Cart
                                </button>
                            </form>
                            <form action="{{ route('wishlist.add', ['product' => $product->id]) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-dark btn-sm" title="Add to Wishlist">
                                    <i class="far fa-heart"></i> Wishlist
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <h3>No featured products available at the moment.</h3>
                <p>Please check back later for our featured products.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-light">
    <div class="container text-center py-5">
        <h2 class="mb-4">Can't Find What You're Looking For?</h2>
        <p class="lead mb-4">Our personal shoppers are here to help you find the perfect piece.</p>
        <a href="{{ route('contact') }}" class="btn btn-dark btn-lg px-5">Contact Us</a>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Add any custom JavaScript for the collections page here
    document.addEventListener('DOMContentLoaded', function() {
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Animation on scroll
        const animateOnScroll = function() {
            const elements = document.querySelectorAll('.collection-card, .featured-product');
            elements.forEach(element => {
                const elementTop = element.getBoundingClientRect().top;
                const windowHeight = window.innerHeight;
                if (elementTop < windowHeight - 100) {
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }
            });
        };

        // Set initial styles for animation
        document.querySelectorAll('.collection-card, .featured-product').forEach(element => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(30px)';
            element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        });

        // Run once on load
        animateOnScroll();

        // Run on scroll
        window.addEventListener('scroll', animateOnScroll);
    });
</script>
@endpush