@extends('admin.layout')

@section('title', 'About Us Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">About Us Content</h4>
                    <a href="{{ route('admin.about.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Content
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($abouts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Subtitle</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($abouts as $about)
                                        <tr>
                                            <td>{{ $about->id }}</td>
                                            <td>{{ Str::limit($about->title, 50) }}</td>
                                            <td>{{ Str::limit($about->subtitle ?? '', 50) }}</td>
                                            <td>
                                                <span class="badge {{ $about->status ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $about->status ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>{{ $about->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.about.show', $about) }}"
                                                       class="btn btn-sm btn-info" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.about.edit', $about) }}"
                                                       class="btn btn-sm btn-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.about.destroy', $about) }}"
                                                          method="POST" class="d-inline"
                                                          onsubmit="return confirm('Are you sure you want to delete this content?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                            <h5>No About Content Found</h5>
                            <p class="text-muted">Get started by creating your first about us content.</p>
                            <a href="{{ route('admin.about.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Create About Content
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
