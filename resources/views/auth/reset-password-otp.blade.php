@extends('layouts.master')

@section('title', 'Reset Password - OTP')

@section('content')
<div class="container py-5" style="max-width:480px;">
    <h3 class="mb-3">Reset your password</h3>
    <p class="text-muted">Enter the 6-digit OTP sent to your email and set a new password.</p>

    @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('password.otp.reset') }}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $email) }}" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="otp" class="form-label">6-digit OTP</label>
            <input type="text" id="otp" name="otp" maxlength="6" pattern="\d{6}" class="form-control @error('otp') is-invalid @enderror" value="{{ old('otp') }}" required>
            @error('otp')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">New Password</label>
            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label">Confirm New Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Reset Password</button>
    </form>

    <div class="mt-3 d-flex justify-content-between">
        <a href="{{ route('password.otp.request') }}">Request a new OTP</a>
        <a href="{{ route('login') }}">Back to login</a>
    </div>
</div>
@endsection
