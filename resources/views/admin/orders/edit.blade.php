@extends('admin.layout')

@section('title', 'Edit Order')

@section('content')
<style>
    .form-label { color: #ffe082; font-weight: 600; }
    .form-control, .form-select { background: #222; color: #ffe082; border: 2px solid #554c2a; }
    .form-control:focus, .form-select:focus { border-color: #d4af37; box-shadow: none; }
    .btn-save { background: #388e3c; color: #fff; font-weight: 600; border-radius: 2em; padding: 0.5em 2em; }
    .btn-cancel { background: #b71c1c; color: #fff; font-weight: 600; border-radius: 2em; padding: 0.5em 2em; }
</style>
<div class="card shadow-sm border-0 mx-auto" style="max-width: 500px;">
    <div class="card-body">
        <h3 class="mb-4 text-light">Edit Order</h3>
        <form>
            <div class="mb-3">
                <label class="form-label">Order ID</label>
                <input type="text" class="form-control" value="#ORD001" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Customer Name</label>
                <input type="text" class="form-control" value="John Doe">
            </div>
            <div class="mb-3">
                <label class="form-label">Product Name</label>
                <input type="text" class="form-control" value="Modern Grey Sofa">
            </div>
            <div class="mb-3">
                <label class="form-label">Amount</label>
                <input type="number" class="form-control" value="549.99">
            </div>
            <div class="mb-4">
                <label class="form-label">Status</label>
                <select class="form-select">
                    <option selected>Pending</option>
                    <option>Completed</option>
                    <option>Cancelled</option>
                </select>
            </div>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.orders') }}" class="btn btn-cancel">Cancel</a>
                <button type="submit" class="btn btn-save">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection 