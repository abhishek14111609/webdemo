@extends('admin.layout')

@section('title', 'Add Product')

@section('content')
<style>
.admin-form-card {
    background: #23284a;
    color: #ffe082;
    border-radius: 1.5em;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.12);
    padding: 2em 2em 1.5em 2em;
    max-width: 600px;
    margin: 0 auto;
}

.admin-form-card h3 {
    color: #ffe082;
    font-size: 1.6em;
    font-weight: 700;
    margin-bottom: 0.5em;
    display: flex;
    align-items: center;
    gap: 0.5em;
}

.admin-form-card h3 i {
    color: #d4af37;
    font-size: 1.2em;
}

.form-label {
    color: #ffe082;
    font-weight: 600;
}

.form-control,
.form-select,
.form-textarea {
    background: #181c2f;
    color: #ffe082;
    border: 2px solid #554c2a;
    border-radius: 0.7em;
}

.form-control:focus,
.form-select:focus,
.form-textarea:focus {
    border-color: #d4af37;
    box-shadow: none;
}

.form-textarea {
    min-height: 100px;
    resize: vertical;
}

.btn-save {
    background: #388e3c;
    color: #fff;
    font-weight: 600;
    border-radius: 2em;
    padding: 0.5em 2em;
    border: none;
}

.btn-cancel {
    background: #b71c1c;
    color: #fff;
    font-weight: 600;
    border-radius: 2em;
    padding: 0.5em 2em;
    border: none;
}

.admin-form-group {
    margin-bottom: 1.2em;
}

.admin-form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 0.7em;
}

.form-check-input {
    background-color: #181c2f;
    border: 2px solid #554c2a;
}

.form-check-input:checked {
    background-color: #d4af37;
    border-color: #d4af37;
}

.form-check-label {
    color: #ffe082;
    font-weight: 500;
}
</style>
<div class="admin-form-card">
    <h3><i class="fas fa-gem"></i> Add Product</h3>
    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="admin-form-group">
            <label class="form-label">Product Name *</label>
            <input type="text" name="name" class="form-control" placeholder="Enter product name"
                value="{{ old('name') }}" required>
            @error('name')
            <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="admin-form-group">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control form-textarea"
                placeholder="Enter product description">{{ old('description') }}</textarea>
            @error('description')
            <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="admin-form-group">
            <label class="form-label">Category *</label>
            <select name="category_id" class="form-select" required>
                <option value="">Select Category</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
                @endforeach
            </select>
            @error('category_id')
            <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="row admin-form-group">
            <div class="col-md-6 mb-3 mb-md-0">
                <label class="form-label">Price *</label>
                <input type="number" name="price" class="form-control" placeholder="0.00" step="0.01"
                    value="{{ old('price') }}" required>
                @error('price')
                <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Original Price</label>
                <input type="number" name="original_price" class="form-control" placeholder="0.00" step="0.01"
                    value="{{ old('original_price') }}">
                @error('original_price')
                <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row admin-form-group">
            <div class="col-md-6 mb-3 mb-md-0">
                <label class="form-label">Stock *</label>
                <input type="number" name="stock" class="form-control" placeholder="0" value="{{ old('stock') }}"
                    required>
                @error('stock')
                <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Badge</label>
                <select name="badge" class="form-select">
                    <option value="">No Badge</option>
                    <option value="New" {{ old('badge') == 'New' ? 'selected' : '' }}>New</option>
                    <option value="Sale" {{ old('badge') == 'Sale' ? 'selected' : '' }}>Sale</option>
                    <option value="Popular" {{ old('badge') == 'Popular' ? 'selected' : '' }}>Popular</option>
                    <option value="Featured" {{ old('badge') == 'Featured' ? 'selected' : '' }}>Featured</option>
                </select>
                @error('badge')
                <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="admin-form-group">
            <label class="form-label">Product Image</label>
            <input type="file" name="image" class="form-control" accept="image/*">
            @error('image')
            <div class="text-danger small">{{ $message }}</div>
            @enderror
            <div class="small text-light mt-1">Recommended size: 800x800px. Max size: 2MB.</div>
        </div>

        <div class="admin-form-group">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured"
                    {{ old('is_featured') ? 'checked' : '' }}>
                <label class="form-check-label" for="is_featured">
                    Featured Product
                </label>
            </div>
        </div>

        <div class="admin-form-group">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                    {{ old('is_active', true) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">
                    Active Product
                </label>
            </div>
        </div>

        <div class="admin-form-actions">
            <a href="{{ route('admin.products.index') }}" class="btn btn-cancel">Cancel</a>
            <button type="submit" class="btn btn-save">Save Product</button>
        </div>
    </form>
</div>
@endsection
