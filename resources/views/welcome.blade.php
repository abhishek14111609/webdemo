@extends('layouts.master')

@section('title', 'Eternal Diamonds - Exquisite Diamond Jewelry')

@push('styles')
    <style>
        /* === Hero Slider Styles === */
        .hero-slider {
            position: relative;
            width: 100%;
            height: 90vh;
            min-height: 600px;
            overflow: hidden;
            background: #f9f9f9;
        }

        .slider-container {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 0.8s ease-in-out;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .slide.active {
            opacity: 1;
            z-index: 2;
        }

        .slide-content {
            display: flex;
            max-width: 1400px;
            width: 100%;
            height: 100%;
            align-items: center;
            justify-content: space-between;
            gap: 2rem;
        }

        .slide-text {
            flex: 1;
            max-width: 600px;
            padding: 2rem;
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease 0.3s;
        }

        .slide.active .slide-text {
            opacity: 1;
            transform: translateY(0);
        }

        .slide-text h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: #1a1a1a;
        }

        .slide-text p {
            font-size: 1.25rem;
            margin-bottom: 2.5rem;
            color: #4a4a4a;
            max-width: 500px;
        }

        .cta-button {
            padding: 0.9rem 2.5rem;
            background-color: #1a1a1a;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            border: 2px solid #1a1a1a;
            transition: 0.3s;
        }

        .cta-button:hover {
            background: transparent;
            color: #1a1a1a;
        }

        .slide-image {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transform: scale(0.9);
            transition: all 0.8s ease 0.3s;
        }

        .slide.active .slide-image {
            opacity: 1;
            transform: scale(1);
        }

        .slide-image img {
            max-width: 100%;
            max-height: 80vh;
            object-fit: contain;
            border-radius: 8px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        }

        .slider-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.9);
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .slider-nav:hover {
            background: white;
            transform: translateY(-50%) scale(1.1);
        }

        .slider-nav.prev {
            left: 2rem;
        }

        .slider-nav.next {
            right: 2rem;
        }

        .slider-dots {
            position: absolute;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 0.75rem;
            z-index: 10;
        }

        .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.2);
            border: none;
            cursor: pointer;
            transition: 0.3s;
        }

        .dot.active {
            background: #1a1a1a;
            transform: scale(1.2);
        }

        /* === Collection & Category Cards === */
        .collection-card,
        .category-card {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.5s ease, box-shadow 0.5s ease;
        }

        .collection-card img,
        .category-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .collection-card:hover img,
        .category-card:hover img {
            transform: scale(1.05);
        }

        .collection-overlay,
        .category-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.7));
            opacity: 0;
            transition: opacity 0.5s ease;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 2rem;
        }

        .collection-card:hover .collection-overlay,
        .category-card:hover .category-overlay {
            opacity: 1;
        }
    </style>
@endpush

@section('content')

    <!-- Hero Slider -->
    <div class="hero-slider">
        <div class="slider-container">
            @forelse($sliders as $index => $slider)
                <div class="slide {{ $index === 0 ? 'active' : '' }}">
                    <div class="slide-content">
                        <div class="slide-text">
                            <h1>{{ $slider->title }}</h1>
                            <p>{{ $slider->description }}</p>
                            @if($slider->button_text)
                                <a href="{{ $slider->button_link ?? '#' }}" class="cta-button">{{ $slider->button_text }}</a>
                            @endif
                        </div>
                        <div class="slide-image">
                            <img src="{{ $slider->image_url }}" alt="{{ $slider->title }}">
                        </div>
                    </div>
                </div>
            @empty
                <div class="slide active">
                    <div class="slide-content">
                        <div class="slide-text">
                            <h1>Welcome to Eternal Diamonds</h1>
                            <p>Discover our exclusive collection of handcrafted diamond jewelry.</p>
                            <a href="{{ route('shop') }}" class="cta-button">Shop Now</a>
                        </div>
                        <div class="slide-image">
                            <img src="{{ asset('images/no-image.jpg') }}" alt="Diamond Jewelry">
                        </div>
                    </div>
                </div>
            @endforelse

            <button class="slider-nav prev"><i class="fas fa-chevron-left"></i></button>
            <button class="slider-nav next"><i class="fas fa-chevron-right"></i></button>

            <div class="slider-dots">
                @foreach($sliders as $index => $slider)
                    <button class="dot {{ $index === 0 ? 'active' : '' }}"></button>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Featured Collections -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <span class="text-uppercase text-muted d-block mb-2">Our Collections</span>
                <h2 class="display-5 fw-bold mb-3">Timeless Elegance, Enduring Beauty</h2>
            </div>
            <div class="row g-4">
                @forelse($featuredCollections as $collection)
                    <div class="col-lg-4 col-md-6">
                        <div class="collection-card h-100">
                            <img src="{{ $collection->image_url }}" alt="{{ $collection->title }}">
                            <div class="collection-overlay">
                                <h3 class="text-white mb-3">{{ $collection->title }}</h3>
                                <p class="text-white-50 mb-4">
                                    {{ \Illuminate\Support\Str::limit($collection->description, 120) }}</p>
                                <a href="{{ route('collection.show', ['slug' => $collection->slug]) }}"
                                    class="btn btn-outline-light px-4">Explore</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-muted">No featured collections found.</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Shop by Category -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <span class="text-uppercase text-muted d-block mb-2">Shop by Category</span>
                <h2 class="display-5 fw-bold mb-3">Explore Our Stunning Categories</h2>
            </div>
            <div class="row g-4">
                @forelse($categories as $category)
                    <div class="col-lg-4 col-md-6">
                        <div class="category-card h-100">
                            <img src="{{ $category->image_url }}" alt="{{ $category->name }}">
                            <div class="category-overlay">
                                <h3 class="text-white mb-3">{{ $category->name }}</h3>
                                <a href="{{ route('category', ['slug' => $category->slug]) }}"
                                    class="btn btn-outline-light px-4">View Products</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-muted">No categories found.</p>
                @endforelse
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const slides = document.querySelectorAll('.slide');
                const dots = document.querySelectorAll('.dot');
                const next = document.querySelector('.slider-nav.next');
                const prev = document.querySelector('.slider-nav.prev');
                let current = 0;
                let timer;

                function showSlide(index) {
                    slides.forEach((s, i) => s.classList.toggle('active', i === index));
                    dots.forEach((d, i) => d.classList.toggle('active', i === index));
                }

                function nextSlide() { current = (current + 1) % slides.length; showSlide(current); }
                function prevSlide() { current = (current - 1 + slides.length) % slides.length; showSlide(current); }
                function startAuto() { timer = setInterval(nextSlide, 7000); }

                next.addEventListener('click', () => { nextSlide(); clearInterval(timer); startAuto(); });
                prev.addEventListener('click', () => { prevSlide(); clearInterval(timer); startAuto(); });
                dots.forEach((dot, i) => dot.addEventListener('click', () => { current = i; showSlide(current); clearInterval(timer); startAuto(); }));

                showSlide(current);
                startAuto();
            });
        </script>
    @endpush

@endsection