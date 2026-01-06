@extends('layouts.master')

@section('title', 'About Us')

@section('content')
<style>
    .section-title {
        font-weight: 700;
        font-size: 2.2rem;
        position: relative;
        display: inline-block;
    }
    .section-title::after {
        content: "";
        position: absolute;
        width: 60px;
        height: 4px;
        background: #0d6efd;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        border-radius: 2px;
    }
    .about-img {
        border-radius: 1rem;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }
    .value-card, .team-card {
        transition: all 0.3s ease;
    }
    .value-card:hover, .team-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }
    .team-img {
        width: 180px;
        height: 180px;
        object-fit: cover;
        border-radius: 50%;
        margin: 1rem auto;
        border: 4px solid #f8f9fa;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.15);
    }
    .team-card h5 {
        margin-top: 0.5rem;
        font-weight: 600;
    }
    .text-gradient {
        background: linear-gradient(90deg, #0d6efd, #6610f2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
</style>

<div class="container py-5">

    {{-- === About Section === --}}
    <div class="row align-items-center mb-5">
        <div class="col-lg-6 mb-4 mb-lg-0 text-center">
            <img src="{{ $about->image_url ?? asset('images/default-about.jpg') }}"
                 alt="{{ $about->title }}"
                 class="img-fluid about-img">
        </div>
        <div class="col-lg-6">
            <h1 class="fw-bold text-gradient mb-3">{{ $about->title }}</h1>
            @if($about->subtitle)
                <h5 class="text-muted mb-3">{{ $about->subtitle }}</h5>
            @endif
            <p class="lead text-secondary">{{ $about->description }}</p>
        </div>
    </div>

    {{-- === Mission & Vision === --}}
    <div class="row mb-5 g-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h3 class="fw-semibold mb-3 text-gradient">Our Mission</h3>
                    <p class="text-muted">{{ $about->mission ?? 'To provide the best products and services to our customers.' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h3 class="fw-semibold mb-3 text-gradient">Our Vision</h3>
                    <p class="text-muted">{{ $about->vision ?? 'To be a leading brand recognized for quality and innovation.' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- === Our Values === --}}
    @if(!empty($about->values))
    <div class="text-center mb-5">
        <h2 class="section-title mb-5">Our Core Values</h2>
        <div class="row g-4">
            @foreach($about->values as $value)
            <div class="col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm h-100 text-center value-card">
                    <div class="card-body py-4">
                        <h5 class="fw-semibold text-gradient mb-2">{{ $value['title'] }}</h5>
                        <p class="text-muted">{{ $value['description'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- === Our Team === --}}
    @if(!empty($about->team_info))
    <div class="text-center mb-5">
        <h2 class="section-title mb-5">Meet Our Team</h2>
        <div class="row g-4 justify-content-center">
            @foreach($about->team_info as $member)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm text-center h-100 team-card p-3">
                    <img src="{{ !empty($member['image']) && Storage::disk('public')->exists($member['image']) 
                                ? asset('storage/' . $member['image']) 
                                : asset('images/no-image.jpg') }}" 
                         class="team-img" alt="{{ $member['name'] }}">
                    <div class="card-body p-0">
                        <h5 class="fw-bold text-dark">{{ $member['name'] }}</h5>
                        <p class="text-muted small mb-2">{{ $member['position'] }}</p>
                        <p class="text-secondary small">{{ $member['bio'] ?? '' }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- === Company History === --}}
    @if(!empty($about->history))
    <div class="text-center mt-5">
        <h2 class="section-title mb-4">Our Journey</h2>
        <div class="col-lg-8 mx-auto">
            <p class="lead text-muted">{{ $about->history }}</p>
        </div>
    </div>
    @endif

</div>
@endsection
