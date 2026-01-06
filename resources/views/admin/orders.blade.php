@extends('admin.layout')

@section('title', 'Manage Orders')

@section('content')
<style>
    .orders-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5em;
    }
    .orders-table th {
        background: #554c2a;
        color: #ffe082;
        font-weight: 600;
        border: none;
    }
    .orders-table td {
        background: #23284a;
        color: #fff;
        border: none;
        vertical-align: middle;
    }
    .orders-table .action-btn {
        border-radius: 50%;
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1em;
        margin-right: 0.2em;
    }
    .orders-table .action-delete { background: #b71c1c; color: #fff; }
    .orders-table .action-delete:hover { background: #d32f2f; color: #fff; }
    .orders-table .btn-details {
        background: #388e3c;
        color: #fff;
        border-radius: 2em;
        font-weight: 600;
        font-size: 0.98em;
        padding: 0.3em 1.2em;
        margin-right: 0.2em;
        transition: background 0.2s;
    }
    .orders-table .btn-details:hover {
        background: #256029;
        color: #ffe082;
    }
    .orders-table .status-select {
        background: #23284a;
        color: #ffe082;
        border: 1.5px solid #554c2a;
        border-radius: 2em;
        font-size: 0.98em;
        padding: 0.2em 1em;
        min-width: 120px;
    }
</style>
<div class="orders-header">
    <h4 class="mb-0">Orders</h4>
</div>

@if(session('success'))
<div class="alert alert-success mb-4">
    {{ session('success') }}
</div>
@endif

@if($orders->isEmpty())
<div class="alert alert-info">
    No orders found in the system.
</div>
@else
<div class="table-responsive">
    <table class="table orders-table align-middle">
        <thead>
            <tr>
                <th>#</th>
                <th>Order Number</th>
                <th>Customer</th>
                <th>Items</th>
                <th>Total</th>
                <th>Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $index => $order)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $order->order_number }}</td>
                <td>{{ $order->user->name ?? $order->name }}</td>
                <td>{{ $order->orderItems->count() }}</td>
                <td>₹{{ number_format($order->total, 2) }}</td>
                <td>{{ $order->created_at->format('Y-m-d') }}</td>
                <td>
                    <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="status-select" onchange="this.form.submit()">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </form>
                </td>
                <td>
                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-details" title="Details"><i class="fas fa-eye"></i> Details</a>
                    <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-btn action-delete" title="Delete" onclick="return confirm('Are you sure you want to delete this order?')"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
@endsection