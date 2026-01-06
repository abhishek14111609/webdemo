@extends('layouts.master')

@section('title', 'Sign Up - Eternal Diamonds')

@push('styles')
<style>
    .auth-hero {
        min-height: 100vh;
        background: linear-gradient(120deg, #fffbe6 60%, #f9f9f9 100%), url('https://images.unsplash.com/photo-1519125323398-675f0ddb6308?auto=format&fit=crop&w=1500&q=80') center/cover no-repeat;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }
    .auth-glass {
        background: rgba(255, 255, 255, 0.92);
        box-shadow: 0 8px 40px rgba(212,175,55,0.10);
        border-radius: 24px;
        padding: 3.5rem 2.5rem 2.5rem 2.5rem;
        max-width: 450px;
        width: 100%;
        position: relative;
        z-index: 2;
        border: 1.5px solid #f3e6b3;
        animation: fadeInUp 1s cubic-bezier(.39,.575,.565,1.000);
    }
    @keyframes fadeInUp {
        0% { opacity: 0; transform: translateY(40px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .auth-glass h2 {
        font-size: 2.1rem;
        font-weight: 800;
        color: #1a1a1a;
        margin-bottom: 1.5rem;
        letter-spacing: 1px;
        text-align: center;
    }
    .auth-glass .divider {
        width: 60px;
        height: 3px;
        background: linear-gradient(90deg, #d4af37 0%, #fffbe6 100%);
        margin: 0 auto 2rem auto;
        border-radius: 2px;
    }
    .form-floating > .form-control, .form-floating > .form-label {
        height: 56px;
        padding: 1.25rem 1rem 0.5rem 1rem;
        font-size: 1.08rem;
        border-radius: 10px;
        background: #fffbe6;
        border: 1.5px solid #f3e6b3;
        color: #1a1a1a;
    }
    .form-floating > .form-label {
        color: #bfa13a;
        font-weight: 600;
        left: 1rem;
        top: 1.1rem;
        transition: all 0.2s;
        pointer-events: none;
    }
    .form-floating > .form-control:focus ~ label,
    .form-floating > .form-control:not(:placeholder-shown) ~ label {
        top: -0.7rem;
        left: 0.8rem;
        font-size: 0.98rem;
        color: #d4af37;
        background: #fffbe6;
        padding: 0 0.3rem;
        border-radius: 6px;
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
    .auth-link {
        display: block;
        text-align: center;
        margin-top: 1.5rem;
        color: #bfa13a;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.2s;
    }
    .auth-link:hover {
        color: #d4af37;
        text-decoration: underline;
    }
    .profile-pic-preview {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }
    .profile-pic-preview img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #d4af37;
        box-shadow: 0 2px 12px rgba(212,175,55,0.13);
    }
    @media (max-width: 576px) {
        .auth-glass { padding: 2rem 0.7rem 1.5rem 0.7rem; }
        .auth-glass h2 { font-size: 1.3rem; }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('profile_pic');
        const preview = document.getElementById('profile-pic-preview-img');
        if (input && preview) {
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(evt) {
                        preview.src = evt.target.result;
                    };
                    reader.readAsDataURL(file);
                } else {
                    preview.src = 'https://randomuser.me/api/portraits/men/32.jpg';
                }
            });
        }
    });
</script>
@endpush

@section('content')
<div class="auth-hero">
    <div class="auth-glass">
        <h2>Create Your Account</h2>
        <div class="divider"></div>
        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
            @csrf
            @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="name" name="name" placeholder=" "  autofocus value="{{ old('name') }}">
                <label for="name">Full Name</label>
                @error('name')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="email" name="email" placeholder=" " value="{{ old('email') }}">
                <label for="email">Email address</label>
                @error('email')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="password" name="password" placeholder=" ">
                <label for="password">Password</label>
                @error('password')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder=" ">
                <label for="password_confirmation">Confirm Password</label>
                @error('password_confirmation')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="city" name="city" placeholder=" " value="{{ old('city') }}">
                <label for="city">City</label>
                @error('city')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-floating mb-3">
                <input type="tel" class="form-control" id="phone" name="phone" placeholder=" " pattern="[0-9]{10,15}" value="{{ old('phone') }}">
                <label for="phone">Phone Number</label>
                @error('phone')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-floating mb-3">
                <select class="form-select" id="gender" name="gender">
                    <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Select Gender</option>
                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
                <label for="gender">Gender</label>
                @error('gender')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="profile_pic" class="form-label">Profile Picture</label>
                <input class="form-control" type="file" id="profile_pic" name="profile_pic" accept="image/*">
                @error('profile_pic')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-gold">Sign Up</button>
        </form>
        <a href="{{ route('login') }}" class="auth-link">Already have an account? Login</a>
    </div>
</div>
@endsection
