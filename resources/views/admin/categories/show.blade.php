@extends('admin.layout')

@section('title', 'Category Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Category Details</h4>
                    <div>
                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Categories
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>ID:</strong></td>
                                    <td>{{ $category->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $category->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Slug:</strong></td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $category->slug }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Description:</strong></td>
                                    <td>{!! $category->description ? nl2br(e($category->description)) : '<em class="text-muted">No description</em>' !!}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge {{ $category->is_active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Sort Order:</strong></td>
                                    <td>{{ $category->sort_order }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Products Count:</strong></td>
                                    <td>
                                        <span class="badge bg-info">{{ $category->products()->count() }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td>{{ $category->created_at->format('M d, Y \a\t h:i A') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Last Updated:</strong></td>
                                    <td>{{ $category->updated_at->format('M d, Y \a\t h:i A') }}</td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Category Image</h6>
                                    @if($category->image)
                                        <img src="{{ asset('storage/' . $category->image) }}"
                                             alt="{{ $category->name }}" class="img-fluid rounded mb-2">
                                        <br>
                                        <a href="{{ asset('storage/' . $category->image) }}" target="_blank"
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

                    @if($category->products()->count() > 0)
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Products in this Category</h5>
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
                                                    @foreach($category->products()->limit(10)->get() as $product)
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

                                        @if($category->products()->count() > 10)
                                            <div class="text-center mt-3">
                                                <a href="{{ route('admin.products.index', ['category' => $category->id]) }}"
                                                   class="btn btn-primary btn-sm">
                                                    View All Products ({{ $category->products()->count() }})
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="row mt-3">
                        <div class="col-12 text-end">
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                  onsubmit="return confirm('Are you sure you want to delete this category? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> Delete Category
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
