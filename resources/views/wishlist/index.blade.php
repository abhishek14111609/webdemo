@extends('layouts.master')

@section('title', 'My Wishlist | ' . config('app.name'))

@push('styles')
<style>
    .wishlist-container {
        padding: 3rem 0;
    }
    
    .wishlist-header {
        margin-bottom: 2rem;
    }
    
    .wishlist-title {
        font-weight: 600;
        font-size: 1.8rem;
        margin-bottom: 1rem;
        color: #333;
    }
    
    .wishlist-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        overflow: hidden;
        margin-bottom: 2rem;
    }
    
    .wishlist-table {
        margin-bottom: 0;
    }
    
    .wishlist-table th {
        font-weight: 600;
        color: #444;
        border-top: none;
        border-bottom: 2px solid #f0f0f0;
        padding: 1.2rem 1rem;
    }
    
    .wishlist-table td {
        vertical-align: middle;
        padding: 1.2rem 1rem;
        border-color: #f0f0f0;
    }
    
    .product-image {
        width: 90px;
        height: 90px;
        object-fit: cover;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }
    
    .product-image-placeholder {
        width: 90px;
        height: 90px;
        border-radius: 8px;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #aaa;
    }
    
    .product-name {
        font-weight: 600;
        color: #333;
        text-decoration: none;
        transition: color 0.2s;
    }
    
    .product-name:hover {
        color: #d4af37;
        text-decoration: none;
    }
    
    .product-price {
        font-weight: 600;
        color: #333;
    }
    
    .stock-badge {
        display: inline-block;
        padding: 0.4em 1em;
        font-size: 0.85rem;
        font-weight: 500;
        border-radius: 2em;
    }
    
    .stock-badge.in-stock {
        background-color: #e8f5e9;
        color: #2e7d32;
    }
    
    .stock-badge.out-of-stock {
        background-color: #ffebee;
        color: #c62828;
    }
    
    .btn-wishlist-action {
        border-radius: 6px;
        font-weight: 500;
        padding: 0.5rem 1rem;
        transition: all 0.2s;
    }
    
    .btn-move-to-cart {
        background-color: #333;
        border-color: #333;
    }
    
    .btn-move-to-cart:hover {
        background-color: #000;
        border-color: #000;
    }
    
    .btn-remove {
        background-color: #fff;
        border-color: #dc3545;
        color: #dc3545;
    }
    
    .btn-remove:hover {
        background-color: #dc3545;
        border-color: #dc3545;
        color: #fff;
    }
    
    .notes-form .input-group {
        border-radius: 6px;
        overflow: hidden;
    }
    
    .notes-form .form-control {
        border-color: #e0e0e0;
    }
    
    .notes-form .btn {
        background-color: #f0f0f0;
        border-color: #e0e0e0;
        color: #555;
    }
    
    .empty-wishlist {
        text-align: center;
        padding: 3rem 1rem;
    }
    
    .empty-wishlist-icon {
        font-size: 3rem;
        color: #ccc;
        margin-bottom: 1.5rem;
    }
    
    .empty-wishlist-message {
        font-size: 1.2rem;
        color: #777;
        margin-bottom: 2rem;
    }
</style>
@endpush

@section('content')
<div class="container wishlist-container">
    <div class="row">
        <div class="col-12">
            <div class="wishlist-header">
                <h1 class="wishlist-title">{{ $title ?? 'My Wishlist' }}</h1>
            </div>
            
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

            @if(session('info'))
            <div class="alert alert-info">
                {{ session('info') }}
            </div>
            @endif
        </div>
    </div>

    <div class="row">
        @if($wishlist && $wishlist->count() > 0)
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Stock Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($wishlist as $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}"
                                        alt="{{ $item->product->name }}" class="img-thumbnail mr-3"
                                        style="width: 80px; height: 80px; object-fit: cover;">
                                    @else
                                    <div class="bg-light mr-3"
                                        style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                                        <span class="text-muted">No image</span>
                                    </div>
                                    @endif
                                    <div>
                                        <h5 class="mb-1"><a
                                                href="{{ route('product.show', $item->product->id) }}">{{ $item->product->name }}</a>
                                        </h5>
                                        @if($item->product->short_description)
                                        <p class="text-muted small mb-0">
                                            {{ Str::limit($item->product->short_description, 100) }}</p>
                                        @endif
                                        <div class="mt-2">
                                            <form action="{{ route('wishlist.updateNotes', $item->id) }}" method="POST"
                                                class="update-notes-form">
                                                @csrf
                                                @method('PATCH')
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="form-control form-control-sm" name="notes"
                                                        placeholder="Add notes" value="{{ $item->notes }}">
                                                    <button type="submit"
                                                        class="btn btn-outline-secondary btn-sm">Save</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $item->product->price }}</td>
                            <td>
                                @if($item->product->stock > 0)
                                <span class="badge badge-success">In Stock</span>
                                @else
                                <span class="badge badge-danger">Out of Stock</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    @if($item->product->stock > 0)
                                    <form action="{{ route('wishlist.moveToCart', $item->product->id) }}" method="POST"
                                        class="d-inline move-to-cart-form">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm move-to-cart"
                                            data-product-id="{{ $item->product->id }}">Add to Cart</button>
                                    </form>
                                    @endif
                                    <form action="{{ route('wishlist.remove', $item->product->id) }}" method="POST"
                                        class="d-inline ml-2 remove-from-wishlist-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm remove-from-wishlist"
                                            data-product-id="{{ $item->product->id }}">Remove</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="col-12">
            <div class="alert alert-info">
                <p class="mb-0">Your wishlist is empty.</p>
            </div>
            <div class="mt-3">
                <a href="{{ route('shop') }}" class="btn btn-primary">Continue Shopping</a>
            </div>
        </div>
        @endif
    </div>
</div>

<script src="{{ asset('js/wishlist.js') }}"></script>
@endsection