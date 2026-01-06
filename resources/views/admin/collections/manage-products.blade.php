@extends('admin.layout')

@section('title', 'Manage Collection Products')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Manage Products in "{{ $collection->title }}"</h4>
                    <div>
                        <a href="{{ route('admin.collections.show', $collection) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> View Collection
                        </a>
                        <a href="{{ route('admin.collections.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Collections
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.collections.update-products', $collection) }}" method="POST">
                        @csrf

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">Select Products to include in this Collection</label>
                                    <div class="form-text mb-3">
                                        Current products in collection: <span class="badge bg-info">{{ count($collectionProducts) }}</span>
                                    </div>
                                </div>

                                <div class="row">
                                    @foreach($products as $product)
                                        <div class="col-md-6 col-lg-4 mb-3">
                                            <div class="card h-100">
                                                <div class="card-body">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                               id="product_{{ $product->id }}"
                                                               name="product_ids[]"
                                                               value="{{ $product->id }}"
                                                               {{ in_array($product->id, $collectionProducts) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="product_{{ $product->id }}">
                                                            <strong>{{ $product->name }}</strong>
                                                        </label>
                                                    </div>

                                                    <div class="mt-2">
                                                        <small class="text-muted">
                                                            <strong>Price:</strong> ${{ number_format($product->price, 2) }}<br>
                                                            <strong>Status:</strong>
                                                            <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-danger' }} badge-sm">
                                                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                                                            </span>
                                                        </small>
                                                    </div>

                                                    @if($product->image)
                                                        <div class="mt-2">
                                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                                 alt="{{ $product->name }}"
                                                                 class="img-fluid rounded"
                                                                 style="max-height: 100px; width: auto;">
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                @if($products->count() == 0)
                                    <div class="text-center py-5">
                                        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                        <h5>No Products Available</h5>
                                        <p class="text-muted">You need to create products first before you can add them to collections.</p>
                                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Create Product
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12 text-end">
                                <button type="button" class="btn btn-secondary me-2" onclick="selectAll()">Select All</button>
                                <button type="button" class="btn btn-secondary me-2" onclick="clearAll()">Clear All</button>
                                <a href="{{ route('admin.collections.show', $collection) }}" class="btn btn-secondary me-2">Cancel</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Collection Products
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function selectAll() {
    const checkboxes = document.querySelectorAll('input[name="product_ids[]"]');
    checkboxes.forEach(checkbox => checkbox.checked = true);
}

function clearAll() {
    const checkboxes = document.querySelectorAll('input[name="product_ids[]"]');
    checkboxes.forEach(checkbox => checkbox.checked = false);
}
</script>
@endsection
