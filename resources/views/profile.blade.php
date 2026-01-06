@extends('layouts.master')

@section('title', 'Profile - Eternal Diamonds')

@push('styles')
<style>
    .profile-hero {
        background: linear-gradient(120deg, #fffbe6 60%, #f9f9f9 100%);
        padding: 5rem 0 3rem 0;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .profile-hero::before {
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
    .profile-hero h1 {
        font-size: 2.7rem;
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
    .profile-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 8px 40px rgba(212,175,55,0.10);
        padding: 2.5rem 2rem 2rem 2rem;
        max-width: 440px;
        margin: -90px auto 2rem auto;
        position: relative;
        z-index: 2;
        border: 1.5px solid #f3e6b3;
        text-align: center;
        transition: box-shadow 0.3s;
    }
    .profile-card:hover {
        box-shadow: 0 16px 48px rgba(212,175,55,0.18);
    }
    .profile-img {
        width: 110px;
        height: 110px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid #d4af37;
        margin-bottom: 1.2rem;
        box-shadow: 0 2px 12px rgba(212,175,55,0.13);
        background: #fffbe6;
    }
    .profile-card h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 0.3rem;
    }
    .profile-card .email {
        color: #bfa13a;
        font-size: 1.08rem;
        margin-bottom: 1.2rem;
    }
    .profile-details {
        text-align: left;
        margin: 2rem 0 1.5rem 0;
        padding: 1.5rem 1.2rem;
        background: linear-gradient(90deg, #fffbe6 60%, #fff 100%);
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(212,175,55,0.07);
        border: 1px solid #f3e6b3;
    }
    .profile-details .detail-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
        font-size: 1.08rem;
    }
    .profile-details .detail-label {
        color: #bfa13a;
        font-weight: 600;
    }
    .btn-gold-outline {
        background: transparent;
        color: #d4af37;
        border: 2px solid #d4af37;
        font-weight: 700;
        padding: 0.6rem 2rem;
        border-radius: 8px;
        transition: background 0.3s, color 0.3s;
        box-shadow: 0 2px 8px rgba(212,175,55,0.09);
        margin-top: 1.2rem;
        text-decoration: none;
        display: inline-block;
    }
    .btn-gold-outline:hover {
        background: #d4af37;
        color: #fff;
        text-decoration: none;
    }
    @media (max-width: 768px) {
        .profile-hero h1 { font-size: 1.5rem; }
        .profile-card { padding: 1.5rem 0.5rem; }
        .profile-details { padding: 1rem 0.5rem; }
    }
</style>
@endpush

@section('content')
@if(Auth::check())
<div class="profile-hero">
    <h1>My Profile</h1>
    <div class="divider"></div>
</div>



<div class="profile-card">
@if(session('success'))
    <div class="container">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif
    <img src="{{ Auth::user()->profile_pic ? asset('storage/' . Auth::user()->profile_pic) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=d4af37&color=fff' }}" 
         alt="Profile" class="profile-img">
    <h3>{{ Auth::user()->name }}</h3>
    <div class="email">{{ Auth::user()->email }}</div>
    
    <div class="profile-details">
        <div class="detail-row">
            <span class="detail-label">City:</span> 
            <span>{{ Auth::user()->city ?? 'Not set' }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Phone:</span> 
            <span>{{ Auth::user()->phone ?? 'Not set' }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Gender:</span> 
            <span>{{ Auth::user()->gender ? ucfirst(Auth::user()->gender) : 'Not set' }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Member Since:</span> 
            <span>{{ Auth::user()->created_at->format('M d, Y') }}</span>
        </div>
    </div>
    
    <div class="d-flex gap-2 justify-content-center">
        <a href="{{ route('profile.edit') }}" class="btn-gold-outline">
            <i class="fas fa-edit me-2"></i>Edit Profile
        </a>
        <button type="button" class="btn-gold-outline" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
            <i class="fas fa-key me-2"></i>Change Password
        </button>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" action="{{ route('profile.password.update') }}">
        @csrf
        @method('PUT')
        <div class="modal-body">
            @if($errors->has('current_password') || $errors->has('password') || $errors->has('password_confirmation'))
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @error('current_password')<li>{{ $message }}</li>@enderror
                        @error('password')<li>{{ $message }}</li>@enderror
                        @error('password_confirmation')<li>{{ $message }}</li>@enderror
                    </ul>
                </div>
            @endif

            <div class="form-floating mb-3">
                <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" placeholder="Current Password" required>
                <label for="current_password">Current Password</label>
                @error('current_password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <small class="text-muted">Forgot your current password? <a href="{{ route('password.otp.request') }}">Reset it via Email OTP</a>.</small>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="New Password" minlength="8" required>
                <label for="password">New Password</label>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-floating mb-2">
                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" placeholder="Confirm New Password" minlength="8" required>
                <label for="password_confirmation">Confirm New Password</label>
                @error('password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-text">Password must be at least 8 characters.</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary"><i class="fas fa-key me-2"></i>Update Password</button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-open modal if there were validation errors for password fields
        const hasPwdErrors = @json($errors->has('current_password') || $errors->has('password') || $errors->has('password_confirmation'));
        if (hasPwdErrors) {
            const modalEl = document.getElementById('changePasswordModal');
            if (modalEl) {
                const modal = new bootstrap.Modal(modalEl);
                modal.show();
            }
        }
    });
</script>
@endpush
@else
<div class="container py-5">
    <div class="alert alert-warning">
        You need to be logged in to view your profile. 
        <a href="{{ route('login') }}" class="alert-link">Login here</a> or 
        <a href="{{ route('register') }}" class="alert-link">create an account</a>.
    </div>
</div>
@endif
@endsection