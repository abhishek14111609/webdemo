@extends('layouts.master')

@section('title', $title . ' | ' . config('app.name'))

@push('styles')
<style>
    .categories-hero {
        position: relative;
        background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)),
                    url('https://images.unsplash.com/photo-1605100804763-247f67b3557e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        color: white;
        padding: 7rem 0 5rem;
        margin-bottom: 3rem;
        overflow: hidden;
    }
    .categories-hero h1 {
        font-size: 2.5rem;
        font-weight: 300;
        letter-spacing: 2px;
        margin-bottom: 1rem;
        text-transform: uppercase;
        text-align: center;
    }
    .categories-hero p {
        font-size: 1.1rem;
        max-width: 700px;
        margin: 0 auto 2rem;
        opacity: 0.9;
        text-align: center;
    }
    .category-card {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 2rem;
        transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        height: 100%;
        background: #fff;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05), 0 1px 2px rgba(0,0,0,0.1);
        display: flex;
        flex-direction: column;
    }
    .category-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 14px 28px rgba(0,0,0,0.12), 0 10px 10px rgba(0,0,0,0.08);
    }
    .category-image {
        position: relative;
        width: 100%;
        padding-top: 70%;
        overflow: hidden;
    }
    .category-image img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.8s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    .category-card:hover .category-image img {
        transform: scale(1.05);
    }
    .category-content {
        padding: 1.5rem;
        text-align: center;
        flex: 1 1 auto;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .category-title {
        font-size: 1.3rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
        color: #222;
        letter-spacing: 0.5px;
    }
    .category-description {
        color: #666;
        margin-bottom: 1.2rem;
        font-size: 0.97rem;
        line-height: 1.5;
    }
    .btn-view-category {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.6rem 1.5rem;
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
    .btn-view-category:hover {
        background: #d4af37;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .btn-view-category i {
        margin-left: 8px;
        transition: transform 0.3s ease;
    }
    .btn-view-category:hover i {
        transform: translateX(4px);
    }
    @media (max-width: 991.98px) {
        .categories-hero {
            padding: 5rem 0;
        }
        .categories-hero h1 {
            font-size: 2rem;
        }
    }
    @media (max-width: 767.98px) {
        .category-card {
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="categories-hero">
    <div class="container">
        <h1>All Categories</h1>
        <p>Browse all our jewelry categories and discover the perfect piece for every occasion.</p>
    </div>
</section>

<!-- Categories Grid -->
<div class="container">
    <div class="row">
        @foreach($categories as $category)
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4 d-flex align-items-stretch">
            <div class="category-card w-100">
                <div class="category-image">
                    <img src="{{ $category->image ? asset('storage/' . $category->image) : 'https://images.unsplash.com/photo-1605100804763-247f67b3557e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80' }}" alt="{{ $category->name }}">
                </div>
                <div class="category-content">
                    <h3 class="category-title">{{ $category->name }}</h3>
                    <p class="category-description">{{ $category->description }}</p>
                    <a href="{{ route('category', ['slug' => $category->slug]) }}" class="btn-view-category">
                        View Products <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection