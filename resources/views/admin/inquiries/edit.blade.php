@extends('admin.layout')

@section('title', 'Edit Inquiry')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Edit Inquiry #{{ $inquiry->id }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.inquiries.index') }}">Inquiries</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </nav>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Edit Inquiry Details</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.inquiries.update', $inquiry->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                           value="{{ old('name', $inquiry->name) }}" readonly>
                                    @error('name')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                           value="{{ old('email', $inquiry->email) }}" readonly>
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
                                           value="{{ old('phone', $inquiry->phone) }}" readonly>
                                    @error('phone')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="subject" class="form-label">Subject</label>
                                    <input type="text" class="form-control" id="subject" name="subject"
                                           value="{{ old('subject', $inquiry->subject) }}" readonly>
                                    @error('subject')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="5" readonly>{{ old('message', $inquiry->message) }}</textarea>
                            @error('message')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-control" id="status" name="status" required>
                                        <option value="active" {{ old('status', $inquiry->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="pending" {{ old('status', $inquiry->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="resolved" {{ old('status', $inquiry->status) == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                        <option value="spam" {{ old('status', $inquiry->status) == 'spam' ? 'selected' : '' }}>Spam</option>
                                    </select>
                                    @error('status')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Current Status</label>
                                    <div>
                                        @if($inquiry->is_read)
                                            <span class="badge bg-success">Read</span>
                                        @else
                                            <span class="badge bg-warning">Unread</span>
                                        @endif

                                        @if($inquiry->is_responded)
                                            <span class="badge bg-info">Responded</span>
                                        @else
                                            <span class="badge bg-secondary">Not Responded</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="admin_notes" class="form-label">Admin Notes</label>
                            <textarea class="form-control" id="admin_notes" name="admin_notes"
                                      rows="3" placeholder="Internal notes for this inquiry...">{{ old('admin_notes', $inquiry->admin_notes) }}</textarea>
                            @error('admin_notes')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Submitted</label>
                                    <p class="form-control-plaintext">{{ $inquiry->created_at->format('M j, Y \a\t g:i A') }}</p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">IP Address</label>
                                    <p class="form-control-plaintext">{{ $inquiry->ip_address ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        @if($inquiry->response)
                        <div class="mb-3">
                            <label class="form-label">Previous Response</label>
                            <div class="border p-3 bg-light">
                                {!! nl2br(e($inquiry->response)) !!}
                            </div>
                            <small class="text-muted">
                                Responded on: {{ $inquiry->responded_at ? $inquiry->responded_at->format('M j, Y \a\t g:i A') : 'N/A' }}
                            </small>
                        </div>
                        @endif

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.inquiries.show', $inquiry->id) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Inquiry
                            </a>

                            <div>
                                <a href="{{ route('admin.inquiries.respond', $inquiry->id) }}" class="btn btn-info">
                                    <i class="fas fa-reply"></i> Send Response
                                </a>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Inquiry
                                </button>
                            </div>
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
document.getElementById('admin_notes').addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = (this.scrollHeight) + 'px';
});
</script>
@endpush
