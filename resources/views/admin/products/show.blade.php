@extends('admin.layout')

@section('title', 'Product Details')

@section('content')
<style>
    .product-detail-card {
        background: #23284a;
        color: #fff;
        border-radius: 1.5em;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.12);
        overflow: hidden;
        margin-bottom: 2rem;
    }
    
    .product-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #2c3154;
    }
    
    .product-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: #ffe082;
        margin: 0;
    }
    
    .product-actions {
        display: flex;
        gap: 0.7rem;
    }
    
    .btn-edit {
        background: #fbc02d;
        color: #23284a;
        font-weight: 600;
        border-radius: 2em;
        padding: 0.5em 1.5em;
        border: none;
        display: flex;
        align-items: center;
        gap: 0.5em;
    }
    
    .btn-delete {
        background: #b71c1c;
        color: #fff;
        font-weight: 600;
        border-radius: 2em;
        padding: 0.5em 1.5em;
        border: none;
        display: flex;
        align-items: center;
        gap: 0.5em;
    }
    
    .btn-back {
        background: #1976d2;
        color: #fff;
        font-weight: 600;
        border-radius: 2em;
        padding: 0.5em 1.5em;
        border: none;
        display: flex;
        align-items: center;
        gap: 0.5em;
    }
    
    .product-body {
        padding: 2rem;
    }
    
    .product-image-gallery {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }
    
    .product-main-image {
        flex: 1 1 300px;
        max-width: 400px;
        background: #181c2f;
        border-radius: 1rem;
        padding: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .product-main-image img {
        max-width: 100%;
        max-height: 300px;
        border-radius: 0.5rem;
        object-fit: contain;
    }
    
    .product-info {
        flex: 1 1 300px;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .info-group {
        display: flex;
        flex-direction: column;
        gap: 0.3rem;
    }
    
    .info-label {
        font-size: 0.9rem;
        color: #b0b3c7;
        font-weight: 500;
    }
    
    .info-value {
        font-size: 1.1rem;
        color: #fff;
        font-weight: 600;
    }
    
    .badge-status {
        display: inline-block;
        font-size: 0.9em;
        padding: 0.4em 1em;
        border-radius: 2em;
        font-weight: 600;
    }
    
    .badge-active {
        background: #388e3c;
        color: #fff;
    }
    
    .badge-inactive {
        background: #d32f2f;
        color: #fff;
    }
    
    .product-description {
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid #2c3154;
    }
    
    .description-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: #ffe082;
        margin-bottom: 1rem;
    }
    
    .description-content {
        color: #fff;
        line-height: 1.7;
    }
    
    .price-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #ffe082;
    }
    
    .original-price {
        text-decoration: line-through;
        color: #b0b3c7;
        margin-left: 0.5rem;
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-light">Product Details</h2>
    <a href="{{ route('admin.products.index') }}" class="btn btn-back">
        <i class="fas fa-arrow-left"></i> Back to Products
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="product-detail-card">
    <div class="product-header">
        <h3 class="product-title">{{ $product->name }}</h3>
        <div class="product-actions">
            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-edit">
                <i class="fas fa-edit"></i> Edit
            </a>
            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline" 
                  onsubmit="return confirm('Are you sure you want to delete this product? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-delete">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </form>
        </div>
    </div>
    
    <div class="product-body">
        <div class="product-image-gallery">
            <div class="product-main-image">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                @else
                    <img src="https://via.placeholder.com/400x300/181c2f/b0b3c7?text=No+Image" alt="No Image">
                @endif
            </div>
            
            <div class="product-info">
                <div class="info-group">
                    <div class="info-label">Category</div>
                    <div class="info-value">{{ $product->category->name ?? 'Uncategorized' }}</div>
                </div>
                
                <div class="info-group">
                    <div class="info-label">Price</div>
                    <div class="info-value price-value">
                        ${{ number_format($product->price, 2) }}
                        @if($product->original_price && $product->original_price > $product->price)
                            <span class="original-price">${{ number_format($product->original_price, 2) }}</span>
                        @endif
                    </div>
                </div>
                
                <div class="info-group">
                    <div class="info-label">Stock</div>
                    <div class="info-value">
                        <span class="badge {{ $product->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                            {{ $product->stock }} in stock
                        </span>
                    </div>
                </div>
                
                <div class="info-group">
                    <div class="info-label">Status</div>
                    <div class="info-value">
                        <span class="badge {{ $product->is_active ? 'badge-active' : 'badge-inactive' }} badge-status">
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        @if($product->is_featured)
                            <span class="badge bg-warning text-dark badge-status ms-1">Featured</span>
                        @endif
                    </div>
                </div>
                
                <div class="info-group">
                    <div class="info-label">Created</div>
                    <div class="info-value">{{ $product->created_at->format('M d, Y') }}</div>
                </div>
                
                <div class="info-group">
                    <div class="info-label">Last Updated</div>
                    <div class="info-value">{{ $product->updated_at->format('M d, Y') }}</div>
                </div>
            </div>
        </div>
        
        <div class="product-description">
            <h4 class="description-title">Description</h4>
            <div class="description-content">
                {!! $product->description !!}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-hide alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });
    });
</script>
@endpush

@endsection