@extends('layouts.master')

@section('title', 'Forgot Password - OTP')

@section('content')
<div class="container py-5" style="max-width:480px;">
    <h3 class="mb-3">Forgot your password?</h3>
    <p class="text-muted">Enter your email address and we'll send you a 6-digit OTP to reset your password. The OTP will expire in 10 minutes.</p>

    @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('password.otp.send') }}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary w-100">Send OTP</button>
    </form>

    <div class="mt-3">
        <a href="{{ route('login') }}">Back to login</a>
    </div>
</div>
@endsection
