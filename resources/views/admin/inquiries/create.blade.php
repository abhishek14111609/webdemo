@extends('admin.layout')

@section('title', 'Create Inquiry')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Create New Inquiry</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.inquiries.index') }}">Inquiries</a></li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </nav>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Create New Inquiry</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.inquiries.store') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name *</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                           value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                           value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="text" class="form-control" id="phone" name="phone"
                                           value="{{ old('phone') }}" placeholder="(555) 123-4567">
                                    @error('phone')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="subject" class="form-label">Subject *</label>
                                    <input type="text" class="form-control" id="subject" name="subject"
                                           value="{{ old('subject') }}" placeholder="Brief description of inquiry" required>
                                    @error('subject')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Message *</label>
                            <textarea class="form-control" id="message" name="message"
                                      rows="6" placeholder="Detailed inquiry message..." required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status *</label>
                                    <select class="form-control" id="status" name="status" required>
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="resolved" {{ old('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                        <option value="spam" {{ old('status') == 'spam' ? 'selected' : '' }}>Spam</option>
                                    </select>
                                    @error('status')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">IP Address (Auto-filled)</label>
                                    <input type="text" class="form-control" value="{{ request()->ip() }}" readonly>
                                    <small class="text-muted">This will be automatically set when the form is submitted</small>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.inquiries.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Inquiries
                            </a>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Inquiry
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-resize textarea
document.getElementById('message').addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = (this.scrollHeight) + 'px';
});

// Character counter for message
document.getElementById('message').addEventListener('input', function() {
    const counter = document.getElementById('message-counter');
    const remaining = 2000 - this.value.length;
    counter.textContent = remaining + ' characters remaining';

    if (remaining < 100) {
        counter.className = 'text-danger small';
    } else if (remaining < 200) {
        counter.className = 'text-warning small';
    } else {
        counter.className = 'text-muted small';
    }
});
</script>

<style>
#message-counter {
    float: right;
    margin-top: 5px;
}
</style>
@endpush
