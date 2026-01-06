@extends('admin.layout')

@section('title', 'Orders Management')

@section('content')
    <style>
        .orders-table th,
        .orders-table td {
            vertical-align: middle;
        }

        .orders-table th {
            background: #554c2a;
            color: #ffe082;
            font-weight: 600;
            border: none;
        }

        .orders-table td {
            background: #222;
            color: #fff;
            border: none;
        }

        .search-bar {
            background: #222;
            color: #ffe082;
            border: 2px solid #554c2a;
            border-radius: 2em;
            padding: 0.75em 1.5em;
            width: 100%;
            font-size: 1.1em;
            margin-bottom: 1.5em;
        }

        .badge-status {
            font-size: 0.9em;
            padding: 0.4em 1em;
            border-radius: 2em;
            font-weight: 600;
        }

        .badge-pending {
            background: #ffc107;
            color: #000;
        }

        .badge-processing {
            background: #17a2b8;
            color: #fff;
        }

        .badge-shipped {
            background: #0d6efd;
            color: #fff;
        }

        .badge-delivered {
            background: #198754;
            color: #fff;
        }

        .badge-cancelled {
            background: #dc3545;
            color: #fff;
        }

        .badge-refunded {
            background: #6c757d;
            color: #fff;
        }

        .action-btn {
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1em;
            margin-right: 0.3em;
            border: none;
            transition: all 0.2s;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .action-view {
            background: #1976d2;
            color: #fff;
        }

        .action-print {
            background: #0dcaf0;
            color: #000;
        }

        .action-delete {
            background: #b71c1c;
            color: #fff;
        }

        .card {
            background: transparent;
            border: none;
            box-shadow: none;
        }
    </style>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3" style="color: #ffe082;">Manage Orders</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table orders-table mb-0" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>#{{ $order->order_number }}</td>
                                <td>
                                    <div style="font-weight: 600; color: #ffe082;">{{ $order->user->name ?? $order->name }}
                                    </div>
                                    <div style="font-size: 0.85em; color: #aaa;">{{ $order->email }}</div>
                                </td>
                                <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                                <td style="font-weight: bold; color: #ffe082;">₹{{ number_format($order->total, 2) }}</td>
                                <td>
                                    <span class="badge badge-status badge-{{ $order->status }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="action-btn action-view"
                                        title="View Order">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="#" class="action-btn action-print" title="Print Invoice"
                                        onclick="window.open('{{ route('admin.orders.invoice', $order->id) }}', '_blank'); return false;">
                                        <i class="fas fa-print"></i>
                                    </a>
                                    <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn action-delete"
                                            onclick="return confirm('Are you sure you want to delete this order?')"
                                            title="Delete Order">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">No orders found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection