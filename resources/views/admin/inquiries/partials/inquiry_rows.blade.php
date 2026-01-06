@foreach($inquiries as $inquiry)
<tr class="{{ $inquiry->is_read ? '' : 'fw-bold' }}">
    <td>
        <input type="checkbox" class="inquiry-checkbox" value="{{ $inquiry->id }}">
    </td>
    <td>{{ $inquiry->id }}</td>
    <td>
        <a href="{{ route('admin.inquiries.show', $inquiry) }}" class="text-dark">
            {{ $inquiry->name }}
        </a>
    </td>
    <td>
        <a href="mailto:{{ $inquiry->email }}" class="text-primary">
            {{ $inquiry->email }}
        </a>
    </td>
    <td>{{ Str::limit($inquiry->subject, 50) }}</td>
    <td>
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
    </td>
    <td>{{ $inquiry->created_at->format('M d, Y') }}</td>
    <td>
        <div class="btn-group" role="group">
            <a href="{{ route('admin.inquiries.show', $inquiry) }}" 
               class="btn btn-sm btn-outline-primary" 
               title="View">
                <i class="fas fa-eye"></i>
            </a>
            <a href="{{ route('admin.inquiries.respond', $inquiry) }}" 
               class="btn btn-sm btn-outline-success"
               title="Respond">
                <i class="fas fa-reply"></i>
            </a>
            <button type="button" 
                    class="btn btn-sm {{ $inquiry->is_read ? 'btn-outline-secondary' : 'btn-outline-info' }}"
                    onclick="toggleReadStatus({{ $inquiry->id }}, {{ $inquiry->is_read ? 'false' : 'true' }})"
                    title="{{ $inquiry->is_read ? 'Mark as Unread' : 'Mark as Read' }}">
                <i class="fas {{ $inquiry->is_read ? 'fa-envelope' : 'fa-envelope-open' }}"></i>
            </button>
            <form action="{{ route('admin.inquiries.destroy', $inquiry) }}" 
                  method="POST" 
                  class="d-inline"
                  onsubmit="return confirm('Are you sure you want to delete this inquiry?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="8" class="text-center py-4">
        <div class="text-muted">
            <i class="fas fa-inbox fa-3x mb-3"></i>
            <p class="mb-0">No inquiries found</p>
        </div>
    </td>
</tr>
@endforeach
