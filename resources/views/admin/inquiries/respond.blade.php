@extends('admin.layout')

@section('title', 'Inquiry Management')

@section('content')
<style>
    .admin-form-card {
        background: #23284a;
        color: #ffe082;
        border-radius: 1.5em;
        box-shadow: 0 2px 12px rgba(0,0,0,0.12);
        padding: 2em;
        margin-bottom: 2em;
    }
    .admin-form-card h3 {
        color: #ffe082;
        font-size: 1.6em;
        font-weight: 700;
        margin-bottom: 0.5em;
        display: flex;
        align-items: center;
        gap: 0.5em;
    }
    .admin-form-card h3 i {
        color: #d4af37;
        font-size: 1.2em;
    }
    .form-label { color: #ffe082; font-weight: 600; }
    .form-control, .form-select, .form-textarea {
        background: #181c2f;
        color: #ffe082;
        border: 2px solid #554c2a;
        border-radius: 0.7em;
    }
    .form-control:focus, .form-select:focus, .form-textarea:focus {
        border-color: #d4af37;
        box-shadow: none;
    }
    .form-textarea {
        min-height: 150px;
        resize: vertical;
    }
    .btn-send { background: #388e3c; color: #fff; font-weight: 600; border-radius: 2em; padding: 0.5em 2em; border: none; }
    .btn-send:hover { background: #256029; color: #ffe082; }
    .btn-cancel { background: #b71c1c; color: #fff; font-weight: 600; border-radius: 2em; padding: 0.5em 2em; border: none; }
    .btn-cancel:hover { background: #7f0000; color: #ffe082; }
    .admin-form-group { margin-bottom: 1.2em; }
    .inquiry-details {
        background: #181c2f;
        padding: 1.5em;
        border-radius: 1em;
        margin-bottom: 1.5em;
    }
    .inquiry-meta {
        display: flex;
        gap: 2em;
        margin-bottom: 1em;
    }
    .inquiry-meta-item {
        display: flex;
        flex-direction: column;
    }
    .inquiry-meta-label {
        font-size: 0.9em;
        color: #b0b3c7;
        margin-bottom: 0.3em;
    }
    .inquiry-meta-value {
        font-size: 1.1em;
        font-weight: 600;
    }
    .inquiry-message {
        white-space: pre-line;
        margin-top: 1em;
        padding-top: 1em;
        border-top: 1px solid #554c2a;
    }
</style>

<div class="admin-form-card">
    <h3><i class="fas fa-reply"></i> Respond to Inquiry</h3>

    <div class="inquiry-details">
        <div class="inquiry-meta">
            <div class="inquiry-meta-item">
                <span class="inquiry-meta-label">From</span>
                <span class="inquiry-meta-value">{{ $inquiry->name }}</span>
            </div>
            <div class="inquiry-meta-item">
                <span class="inquiry-meta-label">Email</span>
                <span class="inquiry-meta-value">{{ $inquiry->email }}</span>
            </div>
            <div class="inquiry-meta-item">
                <span class="inquiry-meta-label">Date</span>
                <span class="inquiry-meta-value">{{ $inquiry->created_at->format('M d, Y H:i') }}</span>
            </div>
        </div>

        <div class="inquiry-message">
            <strong>Original Message:</strong><br>
            {{ $inquiry->message }}
        </div>
    </div>

    <form action="{{ route('admin.inquiries.send-response', $inquiry->id) }}" method="POST">
        @csrf

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="admin-form-group">
            <label for="response" class="form-label">Your Response</label>
            <textarea class="form-control form-textarea @error('response') is-invalid @enderror" id="response" name="response" rows="6">{{ old('response') }}</textarea>
            @error('response')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('admin.inquiries.index') }}" class="btn btn-cancel">Cancel</a>
            <button type="submit" class="btn btn-send">Send Response</button>
        </div>
    </form>
</div>
@endsection
