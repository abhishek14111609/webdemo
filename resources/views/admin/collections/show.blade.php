@extends('admin.layout')

@section('title', 'Collection Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Collection Details</h4>
                    <div>
                        <a href="{{ route('admin.collections.edit', $collection) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.collections.manage-products', $collection) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-boxes"></i> Manage Products
                        </a>
                        <a href="{{ route('admin.collections.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Collections
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>ID:</strong></td>
                                    <td>{{ $collection->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Title:</strong></td>
                                    <td>{{ $collection->title }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Slug:</strong></td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $collection->slug }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Description:</strong></td>
                                    <td>{!! $collection->description ? nl2br(e($collection->description)) : '<em class="text-muted">No description</em>' !!}</td>
                                </tr>
                                <tr>
                                    <td><strong>Featured:</strong></td>
                                    <td>
                                        <span class="badge {{ $collection->is_featured ? 'bg-warning' : 'bg-secondary' }}">
                                            {{ $collection->is_featured ? 'Featured' : 'Regular' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge {{ $collection->is_active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $collection->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Sort Order:</strong></td>
                                    <td>{{ $collection->sort_order }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Products Count:</strong></td>
                                    <td>
                                        <span class="badge bg-info">{{ $collection->products()->count() }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td>{{ $collection->created_at->format('M d, Y \a\t h:i A') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Last Updated:</strong></td>
                                    <td>{{ $collection->updated_at->format('M d, Y \a\t h:i A') }}</td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Collection Image</h6>
                                    @if($collection->image)
                                        <img src="{{ asset('storage/' . $collection->image) }}"
                                             alt="{{ $collection->title }}" class="img-fluid rounded mb-2">
                                        <br>
                                        <a href="{{ asset('storage/' . $collection->image) }}" target="_blank"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-external-link-alt"></i> View Full Size
                                        </a>
                                    @else
                                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center mb-2"
                                             style="width: 100%; height: 200px;">
                                            <i class="fas fa-image fa-3x text-white"></i>
                                        </div>
                                        <small class="text-muted">No image uploaded</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($collection->products()->count() > 0)
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Products in this Collection</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Name</th>
                                                        <th>Price</th>
                                                        <th>Status</th>
                                                        <th>Created</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($products as $product)
                                                        <tr>
                                                            <td>{{ $product->id }}</td>
                                                            <td>{{ Str::limit($product->name, 50) }}</td>
                                                            <td>${{ number_format($product->price, 2) }}</td>
                                                            <td>
                                                                <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-danger' }}">
                                                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                                                </span>
                                                            </td>
                                                            <td>{{ $product->created_at->format('M d, Y') }}</td>
                                                            <td>
                                                                <a href="{{ route('admin.products.show', $product) }}"
                                                                   class="btn btn-sm btn-info">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        @if($products->hasPages())
                                            <div class="d-flex justify-content-center mt-3">
                                                {{ $products->links() }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="row mt-3">
                        <div class="col-12 text-end">
                            <form action="{{ route('admin.collections.destroy', $collection) }}" method="POST"
                                  onsubmit="return confirm('Are you sure you want to delete this collection? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> Delete Collection
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
