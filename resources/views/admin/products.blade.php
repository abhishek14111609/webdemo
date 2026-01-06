@extends('admin.layout')

@section('title', 'Manage Products')

@section('content')
<style>
    .products-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5em;
    }
    .btn-add-product {
        background: #388e3c;
        color: #fff;
        border-radius: 2em;
        font-weight: 600;
        font-size: 1.1em;
        padding: 0.5em 1.5em;
        box-shadow: 0 2px 8px rgba(56,142,60,0.08);
        transition: background 0.2s;
    }
    .btn-add-product:hover {
        background: #256029;
        color: #ffe082;
    }
    .products-table th {
        background: #554c2a;
        color: #ffe082;
        font-weight: 600;
        border: none;
    }
    .products-table td {
        background: #23284a;
        color: #fff;
        border: none;
        vertical-align: middle;
    }
    .products-table .action-btn {
        border-radius: 50%;
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1em;
        margin-right: 0.2em;
    }
    .products-table .action-edit { background: #fbc02d; color: #23284a; }
    .products-table .action-edit:hover { background: #ffe082; color: #23284a; }
    .products-table .action-delete { background: #b71c1c; color: #fff; }
    .products-table .action-delete:hover { background: #d32f2f; color: #fff; }
</style>
<div class="products-header">
    <h4 class="mb-0">Products</h4>
    <a href="{{ route('admin.products.create') }}" class="btn btn-add-product"><i class="fas fa-plus me-2"></i> Add Product</a>
</div>
<div class="table-responsive">
    <table class="table products-table align-middle">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Diamond Ring</td>
                <td>Rings</td>
                <td>$1200</td>
                <td>10</td>
                <td>
                    <a href="{{ route('admin.products.edit', 1) }}" class="action-btn action-edit" title="Edit"><i class="fas fa-pen"></i></a>
                    <button class="action-btn action-delete" title="Delete"><i class="fas fa-trash"></i></button>
                </td>
            </tr>
            <tr>
                <td>2</td>
                <td>Gold Necklace</td>
                <td>Necklaces</td>
                <td>$850</td>
                <td>5</td>
                <td>
                    <a href="{{ route('admin.products.edit', 2) }}" class="action-btn action-edit" title="Edit"><i class="fas fa-pen"></i></a>
                    <button class="action-btn action-delete" title="Delete"><i class="fas fa-trash"></i></button>
                </td>
            </tr>
            <tr>
                <td>3</td>
                <td>Silver Bracelet</td>
                <td>Bracelets</td>
                <td>$300</td>
                <td>20</td>
                <td>
                    <a href="{{ route('admin.products.edit', 3) }}" class="action-btn action-edit" title="Edit"><i class="fas fa-pen"></i></a>
                    <button class="action-btn action-delete" title="Delete"><i class="fas fa-trash"></i></button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
