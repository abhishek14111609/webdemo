@extends('layouts.master')

@section('title', 'Edit Profile - Eternal Diamonds')

@push('styles')
<style>
    .edit-profile-hero {
        background: linear-gradient(120deg, #fffbe6 60%, #f9f9f9 100%);
        padding: 5rem 0 3rem 0;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .edit-profile-hero::before {
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
    .edit-profile-hero h1 {
        font-size: 2.2rem;
        font-weight: 800;
        color: #1a1a1a;
        margin-bottom: 1.2rem;
        letter-spacing: 1px;
        z-index: 1;
        position: relative;
    }
    .divider {
        width: 80px;
        height: 3px;
        background: linear-gradient(90deg, #d4af37 0%, #fffbe6 100%);
        margin: 1.5rem auto 2.5rem auto;
        border-radius: 2px;
    }
    .edit-profile-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 8px 40px rgba(212,175,55,0.10);
        padding: 2.5rem 2rem 2rem 2rem;
        max-width: 480px;
        margin: -90px auto 2rem auto;
        position: relative;
        z-index: 2;
        border: 1.5px solid #f3e6b3;
        text-align: center;
    }
    .profile-pic-preview {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
    }
    .profile-pic-preview img {
        width: 90px;
        height: 90px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #d4af37;
        box-shadow: 0 2px 12px rgba(212,175,55,0.13);
        background: #fffbe6;
    }
    .btn-gold {
        background: linear-gradient(90deg, #d4af37 0%, #fffbe6 100%);
        color: #1a1a1a;
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
    .btn-cancel {
        display: inline-block;
        margin-top: 1.2rem;
        color: #bfa13a;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.2s;
    }
    .btn-cancel:hover {
        color: #d4af37;
        text-decoration: underline;
    }
    @media (max-width: 576px) {
        .edit-profile-card { padding: 1.5rem 0.5rem; }
    }
    .form-floating > label {
        color: #6c757d;
    }
    .form-control:focus, .form-select:focus {
        border-color: #d4af37;
        box-shadow: 0 0 0 0.25rem rgba(212, 175, 55, 0.25);
    }
    .form-control, .form-select {
        border-radius: 8px;
        padding: 0.8rem 1rem;
        border: 1px solid #dee2e6;
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
                    preview.src = preview.dataset.default;
                }
            });
        }
    });
</script>
@endpush

@section('content')
@if(Auth::check())
<div class="edit-profile-hero">
    <h1>Edit Profile</h1>
    <div class="divider"></div>
</div>

<div class="container">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</div>

<div class="edit-profile-card">
    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="profile-pic-preview mb-3">
            <img id="profile-pic-preview-img" 
                 src="{{ old('profile_pic', $user->profile_pic ? asset('storage/' . $user->profile_pic) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=d4af37&color=fff' ) }}" 
                 data-default="{{ $user->profile_pic ? asset('storage/' . $user->profile_pic) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=d4af37&color=fff' }}" 
                 alt="Profile Preview"
                 class="img-thumbnail rounded-circle">
        </div>
        
        <div class="form-floating mb-3">
            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                   id="name" name="name" 
                   value="{{ old('name', $user->name) }}" 
                   placeholder="Full Name" required>
            <label for="name">Full Name</label>
        </div>
        
        <div class="form-floating mb-3">
            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                   id="email" name="email" 
                   value="{{ old('email', $user->email) }}" 
                   placeholder="Email Address" required>
            <label for="email">Email Address</label>
        </div>
        
        <div class="form-floating mb-3">
            <input type="text" class="form-control @error('city') is-invalid @enderror" 
                   id="city" name="city" 
                   value="{{ old('city', $user->city) }}" 
                   placeholder="City">
            <label for="city">City</label>
        </div>
        
        <div class="form-floating mb-3">
            <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                   id="phone" name="phone" 
                   value="{{ old('phone', $user->phone) }}" 
                   placeholder="Phone Number">
            <label for="phone">Phone Number</label>
        </div>
        
        <div class="form-floating mb-3">
            <select class="form-select @error('gender') is-invalid @enderror" 
                    id="gender" name="gender">
                <option value="" {{ old('gender', $user->gender) == '' ? 'selected' : '' }}>Select Gender</option>
                <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Other</option>
            </select>
            <label for="gender">Gender</label>
        </div>
        
        <div class="mb-4">
            <label for="profile_pic" class="form-label">Profile Picture</label>
            <input class="form-control @error('profile_pic') is-invalid @enderror" 
                   type="file" 
                   id="profile_pic" 
                   name="profile_pic" 
                   accept="image/*">
            <div class="form-text">Max file size: 2MB. Allowed formats: jpeg, png, jpg, gif</div>
        </div>
        
        <button type="submit" class="btn-gold">
            <i class="fas fa-save me-2"></i>Save Changes
        </button>
        
        <a href="{{ route('profile.show') }}" class="btn-cancel d-block mt-3">
            <i class="fas fa-times me-1"></i> Cancel
        </a>
    </form>
</div>
@else
<div class="container py-5">
    <div class="alert alert-warning">
        You need to be logged in to edit your profile. 
        <a href="{{ route('login') }}" class="alert-link">Login here</a> or 
        <a href="{{ route('register') }}" class="alert-link">create an account</a>.
    </div>
</div>
@endif
@endsection