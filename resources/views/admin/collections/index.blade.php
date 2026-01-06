@extends('admin.layout')

@section('title', 'Collections Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Collections</h4>
                    <a href="{{ route('admin.collections.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Collection
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($collections->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Slug</th>
                                        <th>Image</th>
                                        <th>Featured</th>
                                        <th>Status</th>
                                        <th>Sort Order</th>
                                        <th>Products</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($collections as $collection)
                                        <tr>
                                            <td>{{ $collection->id }}</td>
                                            <td>{{ $collection->title }}</td>
                                            <td>
                                                <span class="badge bg-light text-dark">{{ $collection->slug }}</span>
                                            </td>
                                            <td>
                                                @if($collection->image)
                                                    <img src="{{ asset('storage/' . $collection->image) }}"
                                                         alt="{{ $collection->title }}" class="rounded" width="40" height="40">
                                                @else
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                         style="width: 40px; height: 40px;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge {{ $collection->is_featured ? 'bg-warning' : 'bg-secondary' }}">
                                                    {{ $collection->is_featured ? 'Featured' : 'Regular' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge {{ $collection->is_active ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $collection->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>{{ $collection->sort_order }}</td>
                                            <td>
                                                <span class="badge bg-info">{{ $collection->products()->count() }}</span>
                                            </td>
                                            <td>{{ $collection->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.collections.show', $collection) }}"
                                                       class="btn btn-sm btn-info" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.collections.edit', $collection) }}"
                                                       class="btn btn-sm btn-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="{{ route('admin.collections.manage-products', $collection) }}"
                                                       class="btn btn-sm btn-primary" title="Manage Products">
                                                        <i class="fas fa-boxes"></i>
                                                    </a>
                                                    <form action="{{ route('admin.collections.destroy', $collection) }}"
                                                          method="POST" class="d-inline"
                                                          onsubmit="return confirm('Are you sure you want to delete this collection?')">
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
                            <i class="fas fa-boxes fa-3x text-muted mb-3"></i>
                            <h5>No Collections Found</h5>
                            <p class="text-muted">Get started by creating your first collection.</p>
                            <a href="{{ route('admin.collections.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Create Collection
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
