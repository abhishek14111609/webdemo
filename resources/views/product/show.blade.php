@extends('layouts.master')

@section('title', $title . ' | ' . config('app.name'))

@push('styles')
<style>
.product-view-container {
    max-width: 900px;
    margin: 3rem auto;
    background: #fff;
    border-radius: 1.2em;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.07);
    display: flex;
    flex-wrap: wrap;
    overflow: hidden;
}

.product-view-image {
    flex: 1 1 350px;
    min-width: 320px;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2.5rem 1.5rem;
}

.product-view-image img {
    max-width: 100%;
    max-height: 350px;
    border-radius: 1em;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
}

.product-view-details {
    flex: 2 1 400px;
    padding: 2.5rem 2.5rem 2.5rem 1.5rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.product-badge {
    display: inline-block;
    background: #d4af37;
    color: #fff;
    font-size: 0.85rem;
    font-weight: 600;
    border-radius: 2em;
    padding: 0.3em 1.2em;
    margin-bottom: 1rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.product-badge.sale {
    background: #dc3545;
}

.product-badge.new {
    background: #28a745;
}

.product-title {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: #222;
    font-family: 'Playfair Display', serif;
}

.product-price {
    font-size: 1.5rem;
    font-weight: 700;
    color: #181c2f;
    margin-bottom: 1rem;
}

.original-price {
    text-decoration: line-through;
    color: #999;
    font-size: 1.1rem;
    margin-right: 0.7rem;
}

.product-description {
    font-size: 1.1rem;
    color: #444;
    margin-bottom: 2rem;
    line-height: 1.7;
}

.product-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.btn-add-cart,
.btn-wishlist {
    padding: 0.85em 2.2em;
    font-size: 1.1rem;
    font-weight: 600;
    border-radius: 2em;
    border: none;
    transition: all 0.2s;
    cursor: pointer;
}

.btn-add-cart {
    background: #181c2f;
    color: #fff;
    border: 2px solid #181c2f;
}

.btn-add-cart:hover {
    background: #d4af37;
    color: #fff;
    border-color: #d4af37;
}

.btn-wishlist {
    background: #fff;
    color: #d4af37;
    border: 2px solid #d4af37;
}

.btn-wishlist:hover {
    background: #d4af37;
    color: #fff;
}

@media (max-width: 991.98px) {
    .product-view-container {
        flex-direction: column;
    }

    .product-view-image,
    .product-view-details {
        padding: 2rem 1.2rem;
    }
}
</style>
<style>
/* Review Styles */
.reviews-section {
    max-width: 900px;
    margin: 2rem auto 4rem;
    padding: 2rem;
    background: #fff;
    border-radius: 1.2em;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.07);
}

.reviews-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #eee;
}

.reviews-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #222;
    font-family: 'Playfair Display', serif;
}

.reviews-summary {
    display: flex;
    align-items: center;
}

.reviews-average {
    font-size: 2.5rem;
    font-weight: 700;
    color: #181c2f;
    margin-right: 1rem;
}

.reviews-count {
    font-size: 0.9rem;
    color: #666;
}

.star-rating {
    display: inline-flex;
    margin-right: 0.5rem;
}

.star-rating i {
    color: #ffc107;
    font-size: 1.2rem;
    margin-right: 0.1rem;
}

.star-rating i.far {
    color: #ddd;
}

.review-item {
    padding: 1.5rem 0;
    border-bottom: 1px solid #eee;
}

.review-item:last-child {
    border-bottom: none;
}

.review-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.5rem;
}

.review-author {
    font-weight: 600;
    color: #333;
}

.review-date {
    font-size: 0.85rem;
    color: #888;
}

.review-content {
    margin-top: 0.5rem;
    color: #444;
    line-height: 1.6;
}

.review-form {
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid #eee;
}

.review-form-title {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: #222;
}

.rating-input {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
    margin-bottom: 1rem;
}

.rating-input input {
    display: none;
}

.rating-input label {
    cursor: pointer;
    font-size: 1.5rem;
    color: #ddd;
    margin-right: 0.25rem;
    transition: color 0.2s;
}

.rating-input label:hover,
.rating-input label:hover~label,
.rating-input input:checked~label {
    color: #ffc107;
}

.review-textarea {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 0.5rem;
    margin-bottom: 1rem;
    font-family: inherit;
    resize: vertical;
    min-height: 100px;
}

.btn-submit-review {
    background: #181c2f;
    color: #fff;
    border: none;
    border-radius: 2em;
    padding: 0.75em 2em;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-submit-review:hover {
    background: #d4af37;
}

.no-reviews {
    text-align: center;
    padding: 2rem 0;
    color: #666;
}

.alert {
    padding: 1rem;
    border-radius: 0.5rem;
    margin-bottom: 1rem;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}
</style>
@endpush

@section('content')
<div class="product-view-container">
    <div class="product-view-image">
        <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://images.unsplash.com/photo-1605100804763-247f67b3557e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80' }}"
            alt="{{ $product->name }}">
    </div>
    <div class="product-view-details">
        @if($product->badge)
        <span class="product-badge">{{ $product->badge }}</span>
        @endif
        <div class="product-title">{{ $product->name }}</div>
        <div class="d-flex align-items-center mb-2">
            <div class="star-rating">
                @for ($i = 1; $i <= 5; $i++) @if ($i <=round($product->average_rating))
                    <i class="fas fa-star"></i>
                    @else
                    <i class="far fa-star"></i>
                    @endif
                    @endfor
            </div>
            <span class="reviews-count">({{ $product->reviews_count }}
                {{ Str::plural('review', $product->reviews_count) }})</span>
        </div>
        <div class="product-price">
            @if($product->original_price && $product->original_price > $product->price)
            <span class="original-price">${{ number_format($product->original_price, 2) }}</span>
            @endif
            ${{ number_format($product->price, 2) }}
        </div>
        <div class="mb-2">
            @if((int)($product->stock ?? 0) > 0)
            <span class="badge bg-success">In Stock</span>
            @else
            <span class="badge bg-danger">Out of Stock</span>
            @endif
        </div>
        <div class="product-description">{{ $product->description ?? 'No description available.' }}</div>
        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <div class="product-actions">
            @auth
            @if((int)($product->stock ?? 0) > 0)
            <form action="{{ route('cart.add', ['product' => $product->id]) }}" method="POST" class="d-inline-block me-2">
                @csrf
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="btn-add-cart">
                    <i class="fas fa-shopping-cart me-2"></i> Add to Cart
                </button>
            </form>
            @else
            <button type="button" class="btn-add-cart" disabled>
                <i class="fas fa-ban me-2"></i> Out of Stock
            </button>
            @endif
            @endauth
            @guest
            @if((int)($product->stock ?? 0) > 0)
            <a href="{{ route('login') }}" class="btn-add-cart">
                <i class="fas fa-sign-in-alt me-2"></i> Login to Add to Cart
            </a>
            @else
            <button type="button" class="btn-add-cart" disabled>
                <i class="fas fa-ban me-2"></i> Out of Stock
            </button>
            @endif
            @endguest
            <form action="{{ route('wishlist.add', ['product' => $product->id]) }}" method="POST" class="d-inline-block">
                @csrf
                <button type="submit" class="btn-wishlist">
                    <i class="far fa-heart me-2"></i> Wishlist
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Reviews Section -->
<div class="reviews-section">
    <div class="reviews-header">
        <div class="reviews-title">Customer Reviews</div>
        <div class="reviews-summary">
            <div class="reviews-average">{{ number_format($product->average_rating, 1) }}</div>
            <div>
                <div class="star-rating">
                    @for ($i = 1; $i <= 5; $i++) @if ($i <=round($product->average_rating))
                        <i class="fas fa-star"></i>
                        @else
                        <i class="far fa-star"></i>
                        @endif
                        @endfor
                </div>
                <div class="reviews-count">{{ $product->reviews_count }}
                    {{ Str::plural('review', $product->reviews_count) }}</div>
            </div>
        </div>
    </div>

    <!-- Display success/error messages -->
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <!-- Reviews List -->
    <div class="reviews-list">
        @forelse ($reviews as $review)
        <div class="review-item">
            <div class="review-header">
                <div class="review-author">{{ $review->user->name }}</div>
                <div class="review-date">{{ $review->created_at->format('M d, Y') }}</div>
            </div>
            <div class="star-rating">
                @for ($i = 1; $i <= 5; $i++) @if ($i <=$review->rating)
                    <i class="fas fa-star"></i>
                    @else
                    <i class="far fa-star"></i>
                    @endif
                    @endfor
            </div>
            @if ($review->comment)
            <div class="review-content">{{ $review->comment }}</div>
            @endif

            <!-- Edit/Delete buttons for user's own reviews -->
            @auth
            @if (Auth::id() === $review->user_id)
            <div class="mt-2">
                <button class="btn btn-sm btn-outline-secondary"
                    onclick="toggleEditForm({{ $review->id }})">Edit</button>
                <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger"
                        onclick="return confirm('Are you sure you want to delete this review?')">Delete</button>
                </form>
            </div>

            <!-- Hidden Edit Form -->
            <div id="edit-form-{{ $review->id }}" class="mt-3" style="display: none;">
                <form action="{{ route('reviews.update', $review) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="rating-input mb-2">
                        @for ($i = 5; $i >= 1; $i--)
                        <input type="radio" id="edit-star{{ $i }}-{{ $review->id }}" name="rating" value="{{ $i }}"
                            {{ $review->rating == $i ? 'checked' : '' }}>
                        <label for="edit-star{{ $i }}-{{ $review->id }}"><i class="fas fa-star"></i></label>
                        @endfor
                    </div>
                    <textarea name="comment" class="review-textarea">{{ $review->comment }}</textarea>
                    <button type="submit" class="btn btn-sm btn-primary">Update Review</button>
                    <button type="button" class="btn btn-sm btn-secondary"
                        onclick="toggleEditForm({{ $review->id }})">Cancel</button>
                </form>
            </div>
            @endif
            @endauth
        </div>
        @empty
        <div class="no-reviews">
            <p>There are no reviews yet for this product. Be the first to leave a review!</p>
        </div>
        @endforelse
    </div>

    <!-- Review Form -->
    @auth
    @if (!Auth::user()->hasReviewed($product->id))
    <div class="review-form">
        <h3 class="review-form-title">Write a Review</h3>
        <form action="{{ route('reviews.store') }}" method="POST">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            <div class="rating-input">
                <input type="radio" id="star5" name="rating" value="5" required>
                <label for="star5"><i class="fas fa-star"></i></label>
                <input type="radio" id="star4" name="rating" value="4">
                <label for="star4"><i class="fas fa-star"></i></label>
                <input type="radio" id="star3" name="rating" value="3">
                <label for="star3"><i class="fas fa-star"></i></label>
                <input type="radio" id="star2" name="rating" value="2">
                <label for="star2"><i class="fas fa-star"></i></label>
                <input type="radio" id="star1" name="rating" value="1">
                <label for="star1"><i class="fas fa-star"></i></label>
            </div>

            <textarea name="comment" class="review-textarea"
                placeholder="Share your experience with this product..."></textarea>

            <button type="submit" class="btn-submit-review">Submit Review</button>
        </form>
    </div>
    @else
    <div class="review-form">
        <p class="text-center">You have already reviewed this product. You can edit or delete your review above.</p>
    </div>
    @endif
    @else
    <div class="review-form">
        <p class="text-center">Please <a href="{{ route('login') }}">login</a> to leave a review.</p>
    </div>
    @endauth
</div>

<script>
function toggleEditForm(reviewId) {
    const form = document.getElementById(`edit-form-${reviewId}`);
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}
</script>
@endsection