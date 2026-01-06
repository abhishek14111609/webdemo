@if($products->isNotEmpty())
<div class="table-responsive">
    <table class="table table-dark table-striped table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th width="40">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="select-all-table">
                    </div>
                </th>
                <th width="80">Image</th>
                <th>Product</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Status</th>
                <th>Featured</th>
                <th width="120">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>
                    <div class="form-check">
                        <input class="form-check-input product-checkbox" type="checkbox" value="{{ $product->id }}" id="product-{{ $product->id }}">
                    </div>
                </td>
                <td>
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                    @elseif($product->gallery && count($product->gallery) > 0)
                        <img src="{{ asset('storage/' . $product->gallery[0]) }}" alt="{{ $product->name }}" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                    @else
                        <img src="https://via.placeholder.com/50x50/333/666?text=No+Image" alt="No Image" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                    @endif
                </td>
                <td>
                    <strong class="text-warning">{{ $product->name }}</strong>
                    <div class="small text-muted">{{ Str::limit(strip_tags($product->description), 50) ?: 'No description' }}</div>
                </td>
                <td>
                    <span class="badge bg-secondary">{{ $product->category->name ?? 'Uncategorized' }}</span>
                </td>
                <td>
                    @if($product->original_price && $product->original_price > $product->price)
                        <span class="text-decoration-line-through text-muted d-block">${{ number_format($product->original_price, 2) }}</span>
                        <span class="text-warning fw-bold">${{ number_format($product->price, 2) }}</span>
                    @else
                        <span class="text-light fw-bold">${{ number_format($product->price, 2) }}</span>
                    @endif
                </td>
                <td>
                    <span class="badge {{ $product->stock > 10 ? 'bg-success' : ($product->stock > 0 ? 'bg-warning text-dark' : 'bg-danger') }}">
                        {{ $product->stock > 0 ? $product->stock . ' in stock' : 'Out of Stock' }}
                    </span>
                </td>
                <td>
                    <div class="form-check form-switch">
                        <input class="form-check-input status-toggle" type="checkbox" data-product-id="{{ $product->id }}" {{ $product->is_active ? 'checked' : '' }} id="status-toggle-{{ $product->id }}">
                        <label class="form-check-label text-{{ $product->is_active ? 'success' : 'danger' }}" for="status-toggle-{{ $product->id }}" id="status-label-{{ $product->id }}">
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </label>
                    </div>
                </td>
                <td>
                    <div class="form-check form-switch">
                        <input class="form-check-input featured-toggle" type="checkbox" data-product-id="{{ $product->id }}" {{ $product->is_featured ? 'checked' : '' }} id="featured-toggle-{{ $product->id }}">
                        <label class="form-check-label text-{{ $product->is_featured ? 'warning' : 'secondary' }}" for="featured-toggle-{{ $product->id }}" id="featured-label-{{ $product->id }}">
                            {{ $product->is_featured ? 'Featured' : 'Not Featured' }}
                        </label>
                    </div>
                </td>
                <td>
                    <div class="btn-group" role="group" aria-label="Product Actions">
                        <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-sm btn-info" title="View Product Details" data-bs-toggle="tooltip">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-primary" title="Edit Product" data-bs-toggle="tooltip">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" title="Delete Product" data-bs-toggle="tooltip" onclick="return confirm('Are you sure you want to delete this product? This action cannot be undone.')">
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
</div>

<div class="d-flex justify-content-between align-items-center mt-4">
    <div class="text-muted">
        Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} products
    </div>
    <nav>
        {{ $products->withQueryString()->links() }}
    </nav>
</div>
@else
<div class="card bg-dark text-white">
    <div class="card-body text-center py-5">
        <i class="fas fa-box-open fa-3x mb-3 text-muted"></i>
        <h4>No products found</h4>
        <p class="text-muted">
            @if(request('search') || request('category'))
                Try adjusting your search or filter criteria
            @else
                Get started by adding a new product
            @endif
        </p>
        @if(!request('search') && !request('category'))
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary mt-3">
                <i class="fas fa-plus me-2"></i> Add Your First Product
            </a>
        @endif
    </div>
</div>
@endif
