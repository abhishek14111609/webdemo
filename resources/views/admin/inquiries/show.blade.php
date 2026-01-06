@extends('admin.layout')

@section('title', 'Inquiry Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Inquiry #{{ $inquiry->id }}</h1>
    <div>
        <a href="{{ route('admin.inquiries.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Inquiries
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-header bg-light py-3">
                <h5 class="card-title mb-0">Inquiry Details</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6>From:</h6>
                    <p class="mb-1"><strong>{{ $inquiry->name }}</strong></p>
                    <p class="mb-1">
                        <a href="mailto:{{ $inquiry->email }}">{{ $inquiry->email }}</a>
                    </p>
                    <p class="text-muted small mb-0">
                        {{ $inquiry->created_at->format('F j, Y \a\t g:i A') }}
                        ({{ $inquiry->created_at->diffForHumans() }})
                    </p>
                </div>

                <div class="mb-3">
                    <h6>Message:</h6>
                    <div class="border rounded p-3 bg-light">
                        {!! nl2br(e($inquiry->message)) !!}
                    </div>
                </div>

                @if($inquiry->admin_notes)
                <div class="mb-3">
                    <h6>Admin Notes:</h6>
                    <div class="border rounded p-3 bg-light">
                        {!! nl2br(e($inquiry->admin_notes)) !!}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header bg-light py-3">
                <h5 class="card-title mb-0">Update Status</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.inquiries.update', $inquiry) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="pending" {{ $inquiry->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ $inquiry->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="resolved" {{ $inquiry->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="spam" {{ $inquiry->status === 'spam' ? 'selected' : '' }}>Spam</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="admin_notes" class="form-label">Admin Notes</label>
                        <textarea name="admin_notes" id="admin_notes" rows="5" class="form-control"
                                  placeholder="Add any internal notes about this inquiry...">{{ old('admin_notes', $inquiry->admin_notes) }}</textarea>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update Inquiry
                        </button>
                    </div>
                </form>

                <hr class="my-4">

                <div class="d-flex justify-content-between">
                    <div>
                        <button type="button" class="btn {{ $inquiry->is_read ? 'btn-outline-secondary' : 'btn-outline-info' }}"
                                onclick="toggleReadStatus({{ $inquiry->id }}, {{ $inquiry->is_read ? 'false' : 'true' }})">
                            <i class="fas {{ $inquiry->is_read ? 'fa-envelope' : 'fa-envelope-open' }} me-1"></i>
                            {{ $inquiry->is_read ? 'Mark as Unread' : 'Mark as Read' }}
                        </button>
                    </div>

                    <form action="{{ route('admin.inquiries.destroy', $inquiry) }}" method="POST"
                          onsubmit="return confirm('Are you sure you want to delete this inquiry? This cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="fas fa-trash me-1"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-header bg-light py-3">
                <h5 class="card-title mb-0">Inquiry Information</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>Status:</span>
                        @php
                            $statusClasses = [
                                'pending' => 'bg-warning',
                                'in_progress' => 'bg-info',
                                'resolved' => 'bg-success',
                                'spam' => 'bg-secondary',
                            ][$inquiry->status] ?? 'bg-secondary';
                        @endphp
                        <span class="badge {{ $statusClasses }}">
                            {{ ucfirst(str_replace('_', ' ', $inquiry->status)) }}
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>Submitted:</span>
                        <span>{{ $inquiry->created_at->format('M j, Y') }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>Last Updated:</span>
                        <span>{{ $inquiry->updated_at->format('M j, Y') }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>Read Status:</span>
                        <span>{{ $inquiry->is_read ? 'Read' : 'Unread' }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleReadStatus(inquiryId, markAsRead) {
    const url = markAsRead
        ? '{{ route('admin.inquiries.mark-read', ['inquiry' => $inquiry->id]) }}'
        : '{{ route('admin.inquiries.mark-unread', ['inquiry' => $inquiry->id]) }}';

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert('Failed to update inquiry status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the inquiry status');
    });
}
</script>
@endpush
