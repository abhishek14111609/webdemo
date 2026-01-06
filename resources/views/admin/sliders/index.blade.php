@extends('admin.layout')

@section('title', 'Manage Sliders')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Sliders</li>
                    </ol>
                </div>
                <h4 class="page-title">Manage Sliders</h4>
            </div>
        </div>
    </div>

    @include('admin.partials.alert')

    <div class="row mb-2">
        <div class="col-12">
            <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Slider
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($sliders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-centered table-striped" id="sortable-sliders">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Sort Order</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sliders as $slider)
                                <tr data-id="{{ $slider->id }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @if($slider->image)
                                            <img src="{{ Storage::url($slider->image) }}" alt="{{ $slider->title }}" class="img-thumbnail" style="max-width: 100px;">
                                        @else
                                            <span class="text-muted">No Image</span>
                                        @endif
                                    </td>
                                    <td>{{ $slider->title }}</td>
                                    <td>
                                        <span class="badge bg-{{ $slider->is_active ? 'success' : 'secondary' }}">
                                            {{ $slider->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>{{ $slider->sort_order }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.sliders.edit', $slider->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger delete-slider" data-id="{{ $slider->id }}" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <form id="delete-form-{{ $slider->id }}" action="{{ route('admin.sliders.destroy', $slider->id) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">No sliders found. <a href="{{ route('admin.sliders.create') }}">Create your first slider</a>.</div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script>
    $(document).ready(function() {
        // Set up CSRF token for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Make table rows sortable
        $("#sortable-sliders tbody").sortable({
            update: function(event, ui) {
                var order = [];
                $("#sortable-sliders tbody tr").each(function(index) {
                    order.push({
                        id: $(this).data('id'),
                        position: index + 1
                    });
                });

                // Send AJAX request to update order
                $.ajax({
                    url: '{{ route("admin.sliders.update-order") }}',
                    type: 'POST',
                    data: {
                        order: order
                    },
                    success: function(response) {
                        if (response.success) {
                            // Reload page to reflect changes
                            window.location.reload();
                        }
                    },
                    error: function(xhr) {
                        console.error('Error updating slider order:', xhr.responseText);
                        alert('Error updating slider order. Please try again.');
                    }
                });
            }
        }).disableSelection();

        // Handle delete button click
        $('.delete-slider').click(function(e) {
            e.preventDefault();
            var sliderId = $(this).data('id');

            if (confirm('Are you sure you want to delete this slider? This action cannot be undone.')) {
                $('#delete-form-' + sliderId).submit();
            }
        });
    });
</script>
@endpush

<style>
    .ui-sortable-helper {
        background-color: #f8f9fa;
        display: table;
    }
    .ui-sortable-helper td {
        background-color: #fff;
    }
    .ui-sortable-handle {
        cursor: move;
    }
</style>
@endsection
