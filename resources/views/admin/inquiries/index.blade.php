@extends('admin.layout')

@section('title', 'Inquiry Management')

@push('styles')
<style>
    .filter-card {
        background: #f8f9fa;
        border-radius: 0.5rem;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
    }
    .bulk-actions {
        display: none;
        margin-bottom: 1rem;
        padding: 0.75rem 1.25rem;
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 0.25rem;
    }
    .bulk-actions.active {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .select-all-checkbox {
        margin-right: 0.5rem;
    }
    .status-badge {
        padding: 0.35em 0.65em;
        font-size: 0.75em;
        font-weight: 700;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 0.25rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Inquiries</li>
                    </ol>
                </div>
                <h4 class="page-title">Manage Inquiries</h4>
            </div>
        </div>
    </div>

    @include('admin.partials.alert')

    <div class="row">
        <div class="col-12">
            <div class="filter-card">
                <form id="filter-form" method="GET" action="{{ route('admin.inquiries.index') }}">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" placeholder="Search inquiries..." 
                                       value="{{ request('search') }}">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="status" onchange="this.form.submit()">
                                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Statuses</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="spam" {{ request('status') == 'spam' ? 'selected' : '' }}>Spam</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}" 
                                   placeholder="From date">
                        </div>
                        <div class="col-md-2">
                            <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}" 
                                   placeholder="To date">
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-outline-primary w-100">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div id="bulk-actions" class="bulk-actions">
                <div class="form-check">
                    <input class="form-check-input select-all-checkbox" type="checkbox" id="selectAll">
                    <label class="form-check-label me-3" for="selectAll">
                        <span id="selected-count">0</span> selected
                    </label>
                </div>
                <select class="form-select form-select-sm bulk-action-select" style="width: 200px;">
                    <option value="">Bulk Actions</option>
                    <option value="mark_read">Mark as Read</option>
                    <option value="mark_unread">Mark as Unread</option>
                    <option value="change_status">Change Status</option>
                    <option value="delete">Delete</option>
                </select>
                <div class="status-selector" style="display: none; width: 200px;">
                    <select class="form-select form-select-sm" id="status-select">
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="resolved">Resolved</option>
                        <option value="spam">Spam</option>
                    </select>
                </div>
                <button class="btn btn-sm btn-primary" id="apply-bulk-action">Apply</button>
                <button class="btn btn-sm btn-outline-secondary" id="cancel-bulk-action">Cancel</button>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive" id="inquiries-table">
                        <table class="table table-centered table-striped" id="inquiries-datatable">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" class="select-all-checkbox"></th>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Subject</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($inquiries as $inquiry)
                                <tr>
                                    <td><input type="checkbox" class="inquiry-checkbox" value="{{ $inquiry->id }}"></td>
                                    <td>{{ $inquiry->id }}</td>
                                    <td>{{ $inquiry->name }}</td>
                                    <td><a href="mailto:{{ $inquiry->email }}">{{ $inquiry->email }}</a></td>
                                    <td>{{ Str::limit($inquiry->subject, 50) }}</td>
                                    <td>
                                        @php
                                            $statusClasses = [
                                                'new' => 'bg-primary',
                                                'in_progress' => 'bg-info',
                                                'resolved' => 'bg-success',
                                                'spam' => 'bg-secondary',
                                            ][$inquiry->status] ?? 'bg-secondary';
                                        @endphp
                                        <span class="badge {{ $statusClasses }}">
                                            {{ ucfirst(str_replace('_', ' ', $inquiry->status)) }}
                                        </span>
                                    </td>
                                    <td>{{ $inquiry->created_at->format('M d, Y h:i A') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.inquiries.show', $inquiry) }}"
                                               class="btn btn-sm btn-primary"
                                               title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button"
                                                    class="btn btn-sm {{ $inquiry->is_read ? 'btn-secondary' : 'btn-info' }}"
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
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No inquiries found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $inquiries->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Response Templates Modal -->
<div class="modal fade" id="responseTemplatesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Response Templates</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="list-group" id="template-list">
                            <a href="#" class="list-group-item list-group-item-action active" data-template="thank_you">
                                <h6 class="mb-1">Thank You</h6>
                                <small>Standard thank you response</small>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action" data-template="more_info">
                                <h6 class="mb-1">Request More Info</h6>
                                <small>When you need more details</small>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action" data-template="resolved">
                                <h6 class="mb-1">Issue Resolved</h6>
                                <small>When the issue has been fixed</small>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">Subject</label>
                            <input type="text" class="form-control" id="template-subject">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Message</label>
                            <textarea class="form-control" id="template-body" rows="8"></textarea>
                        </div>
                        <div class="alert alert-info">
                            <small>You can use the following placeholders: [Customer Name], [Your Company Name]</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="use-template">Use Template</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    const table = $('#inquiries-datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("admin.inquiries.index") }}',
            data: function(d) {
                d.search = $('input[name=search]').val();
                d.status = $('select[name=status]').val();
                d.date_from = $('input[name=date_from]').val();
                d.date_to = $('input[name=date_to]').val();
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'subject', name: 'subject' },
            { data: 'status', name: 'status' },
            { data: 'created_at', name: 'created_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        order: [[0, 'desc']],
        drawCallback: function() {
            // Update checkboxes after table redraw
            updateCheckboxes();
        }
    });

    // Handle search form submission
    $('#filter-form').on('submit', function(e) {
        e.preventDefault();
        table.draw();
    });

    // Handle bulk actions
    let selectedInquiries = [];

    // Toggle select all checkboxes
    $(document).on('change', '.select-all-checkbox', function() {
        $('.inquiry-checkbox').prop('checked', $(this).prop('checked'));
        updateSelectedCount();
    });

    // Update selected count when checkboxes change
    $(document).on('change', '.inquiry-checkbox', function() {
        updateSelectedCount();
    });

    // Show/hide status selector based on bulk action
    $('.bulk-action-select').on('change', function() {
        if ($(this).val() === 'change_status') {
            $('.status-selector').show();
        } else {
            $('.status-selector').hide();
        }
    });

    // Apply bulk action
    $('#apply-bulk-action').on('click', function() {
        const action = $('.bulk-action-select').val();
        const status = $('#status-select').val();
        
        if (!action) {
            alert('Please select an action');
            return;
        }

        if (action === 'change_status' && !status) {
            alert('Please select a status');
            return;
        }

        const data = {
            action: action,
            selected_ids: selectedInquiries,
            _token: '{{ csrf_token() }}'
        };

        if (action === 'change_status') {
            data.status = status;
        }

        $.post('{{ route("admin.inquiries.bulk-action") }}', data)
            .done(function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    table.draw();
                    resetBulkActions();
                }
            })
            .fail(function() {
                toastr.error('An error occurred while processing your request');
            });
    });

    // Cancel bulk action
    $('#cancel-bulk-action').on('click', resetBulkActions);

    // Load response templates
    $('#responseTemplatesModal').on('show.bs.modal', function() {
        // Pre-select first template
        loadTemplate('thank_you');
    });

    // Handle template selection
    $(document).on('click', '#template-list a', function(e) {
        e.preventDefault();
        const template = $(this).data('template');
        loadTemplate(template);
        $(this).addClass('active').siblings().removeClass('active');
    });

    // Use selected template
    $('#use-template').on('click', function() {
        const subject = $('#template-subject').val();
        const body = $('#template-body').val();
        
        // Replace placeholders with actual values
        const customerName = '{{ $inquiry->name ?? "Customer" }}';
        const companyName = '{{ config("app.name") }}';
        
        let processedBody = body
            .replace(/\[Customer Name\]/g, customerName)
            .replace(/\[Your Company Name\]/g, companyName);
            
        // Set values in the respond form
        $('input[name="subject"]').val(subject);
        $('textarea[name="response"]').val(processedBody);
        
        // Close modal
        $('#responseTemplatesModal').modal('hide');
    });

    // Helper functions
    function updateSelectedCount() {
        selectedInquiries = [];
        $('.inquiry-checkbox:checked').each(function() {
            selectedInquiries.push($(this).val());
        });
        
        const count = selectedInquiries.length;
        $('#selected-count').text(count);
        
        if (count > 0) {
            $('#bulk-actions').addClass('active');
        } else {
            $('#bulk-actions').removeClass('active');
        }
    }
    
    function resetBulkActions() {
        $('.select-all-checkbox, .inquiry-checkbox').prop('checked', false);
        $('.bulk-action-select').val('');
        $('.status-selector').hide();
        $('#bulk-actions').removeClass('active');
        selectedInquiries = [];
    }
    
    function loadTemplate(template) {
        $.get('{{ route("admin.inquiries.templates") }}')
            .done(function(templates) {
                if (templates[template]) {
                    $('#template-subject').val(templates[template].subject);
                    $('#template-body').val(templates[template].body);
                }
            });
    }
    
    function updateCheckboxes() {
        $('.inquiry-checkbox').each(function() {
            if (selectedInquiries.includes($(this).val())) {
                $(this).prop('checked', true);
            }
        });
    }
});
</script>
@endpush
