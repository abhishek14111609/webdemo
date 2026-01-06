@extends('layouts.master')

@section('title', 'Contact Us - Eternal Diamonds')

@push('styles')
<style>
    .contact-hero {
        background: linear-gradient(120deg, #fffbe6 60%, #f9f9f9 100%);
        padding: 5rem 0 3rem 0;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .contact-hero::before {
        content: '';
        position: absolute;
        top: -80px;
        left: 50%;
        transform: translateX(-50%);
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, #fffbe6 0%, #d4af37 100%);
        opacity: 0.08;
        z-index: 0;
        border-radius: 50%;
    }
    .contact-hero h1 {
        font-size: 2.8rem;
        font-weight: 800;
        color: #1a1a1a;
        margin-bottom: 1.1rem;
        letter-spacing: 1px;
        z-index: 1;
        position: relative;
    }
    .contact-hero p {
        font-size: 1.2rem;
        color: #4a4a4a;
        max-width: 650px;
        margin: 0 auto;
        z-index: 1;
        position: relative;
    }
    .divider {
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, #d4af37 0%, #fffbe6 100%);
        margin: 2rem auto 2.5rem auto;
        border-radius: 2px;
    }
    .contact-section {
        padding: 3rem 0 2rem 0;
    }
    .contact-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 4px 24px rgba(212,175,55,0.07);
        padding: 2.5rem 2rem 2rem 2rem;
        border: 1.5px solid #f3e6b3;
        margin-bottom: 2rem;
    }
    .contact-card h2 {
        font-size: 2rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 1.2rem;
    }
    .contact-details {
        font-size: 1.08rem;
        color: #4a4a4a;
        margin-bottom: 1.5rem;
    }
    .contact-details i {
        color: #d4af37;
        margin-right: 0.7rem;
        font-size: 1.1rem;
    }
    .form-label {
        font-weight: 600;
        color: #1a1a1a;
    }
    .form-control, .form-control:focus {
        border-radius: 8px;
        border: 1.5px solid #f3e6b3;
        box-shadow: none;
        background: #fffbe6;
        color: #1a1a1a;
    }
    .btn-gold {
        background: linear-gradient(90deg, #000000 0%, #000000 100%);
        color: #ffffff;
        border: none;
        font-weight: 700;
        padding: 0.7rem 2.2rem;
        border-radius: 8px;
        transition: background 0.3s, color 0.3s;
        box-shadow: 0 2px 8px rgba(212,175,55,0.09);
        width: 100%;
        margin-top: 1.2rem;
    }
    .btn-gold:hover {
        background: #d4af37;
        color: #fff;
    }
   
    .map-responsive {
        overflow: hidden;
        padding-bottom: 56.25%;
        position: relative;
        height: 0;
        border-radius: 14px;
        box-shadow: 0 4px 24px rgba(212,175,55,0.07);
        border: 1.5px solid #f3e6b3;
    }
    .map-responsive iframe {
        left: 0;
        top: 0;
        height: 100%;
        width: 100%;
        position: absolute;
        border: 0;
        border-radius: 14px;
    }
    @media (max-width: 768px) {
        .contact-hero h1 { font-size: 1.7rem; }
        .contact-card h2 { font-size: 1.3rem; }
        .contact-section { padding: 2rem 0 1rem 0; }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<div class="contact-hero">
    <h1>Contact Us</h1>
    <div class="divider"></div>
    <p>
        We would love to hear from you. Whether you have a question about our collections, need assistance, or want to book a private consultation, our team is here to help.
    </p>
</div>

<!-- Contact Section -->
<section class="contact-section container">
    <div class="row g-4 align-items-stretch">
        <!-- Contact Form -->
        <div class="col-lg-6">
            <div class="contact-card h-100">
                <h2>Send Us a Message</h2>
                <form method="POST" action="{{ route('contact.submit') }}">
                    @csrf
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
               
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name"  value="{{ old('name') }}">
                        @error('name')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
                        @error('email')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="5">{{ old('message') }}</textarea>
                        @error('message')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-gold" >Send Message</button>
                </form>
            </div>
        </div>
        <!-- Contact Details -->
        <div class="col-lg-6">
            <div class="contact-card h-100">
                <h2>Our Boutique</h2>
                <div class="contact-details mb-3">
                    <div class="mb-2"><i class="fas fa-map-marker-alt"></i> 123 Diamond Avenue, New York, NY 10001</div>
                    <div class="mb-2"><i class="fas fa-phone"></i> +1 (555) 123-4567</div>
                    <div class="mb-2"><i class="fas fa-envelope"></i> info@eternaldiamonds.com</div>
                    <div><i class="fas fa-clock"></i> Mon - Sat: 10:00 AM - 7:00 PM</div>
                </div>
                <div class="map-responsive">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3021.870964099857!2d-73.9903186845936!3d40.74881707932737!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c259af18e7e8b1%3A0x6e4b77ec2a3f8b0!2sEmpire%20State%20Building!5e0!3m2!1sen!2sus!4v1680000000000!5m2!1sen!2sus" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection 