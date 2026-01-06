@extends('layouts.master')

@section('title', $title . ' | ' . config('app.name'))

@push('styles')
<style>
    /* Base Styles */
    :root {
        --primary-color: #d4af37;
        --primary-dark: #b5942a;
        --dark: #1a1a1a;
        --light: #f8f9fa;
        --gray: #6c757d;
        --light-gray: #e9ecef;
        --transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    
    body {
        background-color: #fafafa;
        color: #333;
        font-family: 'Playfair Display', serif;
    }
    
    /* Hero Section */
    .category-hero {
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
    
    .category-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.4) 100%);
        z-index: 1;
    }
    
    .category-hero .container {
        position: relative;
        z-index: 2;
    }
    
    /* Breadcrumb */
    .breadcrumb {
        background: transparent;
        padding: 0.5rem 0;
        margin-bottom: 2rem;
    }
    
    .breadcrumb-item {
        font-size: 0.85rem;
        font-family: 'Montserrat', sans-serif;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }
    
    .breadcrumb-item a {
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        transition: var(--transition);
    }
    
    .breadcrumb-item a:hover {
        color: var(--primary-color);
    }
    
    .breadcrumb-item.active {
        color: var(--primary-color);
    }
    
    .breadcrumb-item + .breadcrumb-item::before {
        color: rgba(255, 255, 255, 0.5);
        content: '›';
    }
    
    /* Category Header */
    .category-header {
        text-align: center;
        margin-bottom: 4rem;
        position: relative;
        padding-bottom: 2rem;
    }
    
    .category-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 2px;
        background: var(--primary-color);
    }
    
    .category-title {
        font-size: 3rem;
        font-weight: 400;
        letter-spacing: 2px;
        margin-bottom: 1.5rem;
        text-transform: capitalize;
        font-family: 'Playfair Display', serif;
    }
    
    .category-description {
        font-size: 1.1rem;
        max-width: 700px;
        margin: 0 auto;
        color: rgba(255, 255, 255, 0.9);
        line-height: 1.8;
        font-weight: 300;
    }
    
    /* Filter Bar */
    .filter-bar {
        background: #fff;
        padding: 1.5rem 2rem;
        border-radius: 0;
        margin-bottom: 3rem;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 5px 30px rgba(0,0,0,0.05);
        border-bottom: 1px solid #f0f0f0;
    }
    
    .filter-group {
        display: flex;
        align-items: center;
        margin-right: 2rem;
        margin-bottom: 0.5rem;
    }
    
    .filter-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #666;
        margin-right: 1rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-family: 'Montserrat', sans-serif;
    }
    
    .filter-select {
        min-width: 200px;
        border: 1px solid #e0e0e0;
        border-radius: 0;
        padding: 0.6rem 1.2rem;
        font-size: 0.9rem;
        color: #333;
        background-color: #fff;
        cursor: pointer;
        transition: var(--transition);
        font-family: 'Montserrat', sans-serif;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 1em;
    }
    
    .filter-select:focus {
        border-color: var(--primary-color);
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, 0.15);
    }
    
    .product-count {
        font-size: 0.9rem;
        color: #777;
        letter-spacing: 0.5px;
        font-family: 'Montserrat', sans-serif;
    }
    
    /* Product Grid */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 2rem;
        margin-bottom: 4rem;
    }
    
    .product-card {
        background: #fff;
        border-radius: 0;
        overflow: hidden;
        transition: var(--transition);
        position: relative;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .product-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
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
        background: var(--primary-color);
        color: white;
        padding: 0.35rem 1rem;
        border-radius: 0;
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 1px;
        z-index: 2;
        text-transform: uppercase;
        font-family: 'Montserrat', sans-serif;
    }
    
    .product-badge.sale {
        background: #dc3545;
    }
    
    .product-badge.new {
        background: #28a745;
    }
    
    .product-actions {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        display: flex;
        justify-content: center;
        padding: 1.5rem;
        background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
        transform: translateY(100%);
        transition: var(--transition);
        z-index: 3;
    }
    
    .product-card:hover .product-actions {
        transform: translateY(0);
    }
    
    .btn-action {
        width: 42px;
        height: 42px;
        border-radius: 0;
        background: rgba(255,255,255,0.9);
        color: #333;
        border: none;
        margin: 0 0.35rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }
    
    .btn-action::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: var(--primary-color);
        transform: scale(0);
        border-radius: 50%;
        transition: var(--transition);
        z-index: -1;
    }
    
    .btn-action:hover {
        color: white;
        transform: translateY(-3px);
    }
    
    .btn-action:hover::after {
        transform: scale(2);
        opacity: 1;
    }
    
    .product-details {
        padding: 1.8rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        background: #fff;
    }
    
    .product-category {
        font-size: 0.75rem;
        color: #999;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-family: 'Montserrat', sans-serif;
    }
    
    .product-title {
        font-size: 1.1rem;
        font-weight: 500;
        margin-bottom: 0.75rem;
        color: #222;
        line-height: 1.4;
        font-family: 'Playfair Display', serif;
        letter-spacing: 0.5px;
    }
    
    .product-price {
        margin-top: auto;
        padding-top: 1rem;
        border-top: 1px solid #f0f0f0;
    }
    
    .current-price {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--dark);
    }
    
    .original-price {
        text-decoration: line-through;
        color: #999;
        font-size: 0.95rem;
        margin-right: 0.5rem;
    }
    
    .btn-view-details {
        display: block;
        width: 100%;
        padding: 0.8rem;
        background: var(--dark);
        color: white;
        border: 1px solid var(--dark);
        border-radius: 0;
        font-size: 0.85rem;
        font-weight: 500;
        text-align: center;
        text-decoration: none;
        transition: var(--transition);
        margin-top: 1.2rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-family: 'Montserrat', sans-serif;
    }
    
    .btn-view-details:hover {
        background: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
        transform: translateY(-2px);
    }
    
    /* Pagination */
    .pagination-container {
        margin: 4rem 0;
        text-align: center;
    }
    
    .pagination {
        display: inline-flex;
        border: 1px solid #eee;
        border-radius: 0;
        overflow: hidden;
    }
    
    .page-item {
        margin: 0;
    }
    
    .page-link {
        color: #666;
        border: none;
        padding: 0.75rem 1.25rem;
        margin: 0;
        border-radius: 0;
        transition: var(--transition);
        font-family: 'Montserrat', sans-serif;
        font-size: 0.9rem;
        min-width: 45px;
        text-align: center;
        border-right: 1px solid #eee;
    }
    
    .page-link:hover {
        background: #f8f9fa;
        color: var(--primary-color);
    }
    
    .page-item.active .page-link {
        background-color: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }
    
    .page-item:last-child .page-link {
        border-right: none;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 1199.98px) {
        .product-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
        }
    }
    
    @media (max-width: 991.98px) {
        .filter-bar {
            flex-direction: column;
            align-items: flex-start;
            padding: 1.5rem;
        }
        
        .filter-group {
            width: 100%;
            margin-bottom: 1rem;
        }
        
        .filter-select {
            width: 100%;
        }
        
        .product-count {
            width: 100%;
            text-align: center;
            margin-top: 1rem;
            margin-bottom: 0.5rem;
        }
        
        .category-title {
            font-size: 2.5rem;
        }
    }
    
    @media (max-width: 767.98px) {
        .category-hero {
            padding: 6rem 0 4rem;
            background-attachment: scroll;
        }
        
        .category-title {
            font-size: 2rem;
        }
        
        .category-description {
            font-size: 1rem;
            padding: 0 1rem;
        }
        
        .product-grid {
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 1.25rem;
        }
        
        .product-details {
            padding: 1.25rem;
        }
        
        .btn-view-details {
            padding: 0.65rem;
            font-size: 0.8rem;
        }
    }
    
    @media (max-width: 575.98px) {
        .product-grid {
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        
        .category-title {
            font-size: 1.75rem;
        }
        
        .pagination {
            flex-wrap: wrap;
        }
        
        .page-item {
            flex: 1;
            text-align: center;
        }
        
        .page-link {
            padding: 0.6rem 0.5rem;
            font-size: 0.8rem;
            min-width: auto;
        }
    }
    
    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
    }
</style>
@endpush

@section('content')
<!-- Category Hero Section -->
<section class="category-hero">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
            </ol>
        </nav>
        
        <div class="text-center">
            <h1 class="category-title">{{ $category->name }}</h1>
            @if($category->description)
                <p class="category-description">{{ $category->description }}</p>
            @endif
        </div>
    </div>
</section>

<!-- Main Content -->
<div class="container">
    <!-- Filter Bar -->
    <div class="filter-bar">
        <div class="filter-group">
            <span class="filter-label">Sort By:</span>
            <select class="filter-select">
                <option>Featured</option>
                <option>Price: Low to High</option>
                <option>Price: High to Low</option>
                <option>Newest Arrivals</option>
                <option>Best Selling</option>
            </select>
        </div>
        
        <div class="filter-group">
            <span class="filter-label">Filter By:</span>
            <select class="filter-select">
                <option>All Items</option>
                <option>In Stock</option>
                <option>On Sale</option>
                <option>New Arrivals</option>
            </select>
        </div>
        
        <div class="product-count">
            Showing 1-9 of 24 products
        </div>
    </div>
    
    <!-- Products Grid -->
    <div class="product-grid">
        @forelse($category->products as $product)
        <div class="product-card animate-fade-in-up">
            <div class="product-image-container">
                <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://images.unsplash.com/photo-1605100804763-247f67b3557e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80' }}" alt="{{ $product->name }}" class="product-image">
                @if($product->badge)
                <span class="product-badge">{{ $product->badge }}</span>
                @endif
                <div class="product-actions">
                    <form action="{{ route('wishlist.add', ['product' => $product->id]) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn-action" title="Add to Wishlist">
                            <i class="far fa-heart"></i>
                        </button>
                    </form>
                    <a href="{{ route('product.show', ['slug' => $product->slug]) }}" class="btn-action" title="Quick View">
                        <i class="far fa-eye"></i>
                    </a>
                    @auth
                        @if((int)($product->stock ?? 0) > 0)
                            <form action="{{ route('cart.add', ['product' => $product->id]) }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn-action" title="Add to Cart">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </form>
                        @else
                            <button type="button" class="btn-action" title="Out of Stock" disabled>
                                <i class="fas fa-ban"></i>
                            </button>
                        @endif
                    @endauth
                    @guest
                        @if((int)($product->stock ?? 0) > 0)
                            <a href="{{ route('login') }}" class="btn-action" title="Login to add to cart">
                                <i class="fas fa-sign-in-alt"></i>
                            </a>
                        @else
                            <button type="button" class="btn-action" title="Out of Stock" disabled>
                                <i class="fas fa-ban"></i>
                            </button>
                        @endif
                    @endguest
                 </div>
            </div>
            <div class="product-details">
                <div class="product-category">{{ $category->name }}</div>
                <h3 class="product-title">{{ $product->name }}</h3>
                <div class="d-flex align-items-center mb-1">
                    <div class="star-rating">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= round($product->average_rating))
                                <i class="fas fa-star" style="color: #ffc107; font-size: 0.8rem;"></i>
                            @else
                                <i class="far fa-star" style="color: #ddd; font-size: 0.8rem;"></i>
                            @endif
                        @endfor
                    </div>
                    <span style="font-size: 0.7rem; color: #666; margin-left: 5px;">({{ $product->reviews_count }})</span>
                </div>
                <div class="product-price">
                    @if($product->original_price && $product->original_price > $product->price)
                    <span class="original-price">${{ number_format($product->original_price, 2) }}</span>
                    @endif
                    <span class="current-price">${{ number_format($product->price, 2) }}</span>
                </div>
                <a href="{{ route('product.show', ['slug' => $product->slug]) }}" class="btn-view-details">View Details</a>
            </div>
        </div>
        @empty
        <div class="col-12 text-center">
            <h3>No products found in this category</h3>
            <p>Check back later for new products.</p>
        </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    <div class="pagination-container">
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <li class="page-item">
                    <a class="page-link" href="#" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Add to cart animation
        const addToCartButtons = document.querySelectorAll('.btn-action');
        addToCartButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const icon = this.querySelector('i');
                icon.className = 'fas fa-check';
                setTimeout(() => {
                    icon.className = this.getAttribute('title') === 'Add to Wishlist' ? 'far fa-heart' : 
                                    this.getAttribute('title') === 'Quick View' ? 'far fa-eye' : 'fas fa-shopping-cart';
                }, 1000);
            });
        });
        
        // Animate elements on scroll
        const animateOnScroll = function() {
            const elements = document.querySelectorAll('.animate-fade-in-up:not(.animated)');
            elements.forEach(element => {
                const elementTop = element.getBoundingClientRect().top;
                const windowHeight = window.innerHeight;
                if (elementTop < windowHeight - 100) {
                    element.classList.add('animated');
                }
            });
        };
        
        // Run once on load
        animateOnScroll();
        
        // Run on scroll
        window.addEventListener('scroll', animateOnScroll);
    });
</script>
@endpush
