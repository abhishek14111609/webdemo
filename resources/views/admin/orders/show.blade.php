@extends('admin.layout')

@section('title', 'Order Details')

@section('content')
    <style>
        .order-detail-card {
            background: #23284a;
            color: #ffe082;
            border-radius: 1.2em;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.13);
            padding: 2em 2.5em;
            margin: 2em auto 2em auto;
            max-width: 1000px;
            border-left: 7px solid #d4af37;
        }

        .order-detail-card h3,
        .order-detail-card h4 {
            color: #ffe082;
            font-weight: 700;
            margin-bottom: 1.2em;
        }

        .order-detail-table th,
        .order-detail-table td {
            color: #ffe082;
            border: none;
            background: transparent;
            padding: 0.6em 1em 0.6em 0.5em;
            font-size: 1.08em;
        }

        .order-detail-table th {
            text-align: left;
            font-weight: 600;
            width: 30%;
            vertical-align: middle;
        }

        .order-detail-table td {
            text-align: left;
            width: 70%;
            vertical-align: middle;
        }

        .order-detail-table i {
            color: #d4af37;
            margin-right: 0.5em;
            min-width: 22px;
            text-align: center;
        }

        .back-btn {
            background: #d4af37;
            color: #23284a;
            font-weight: 700;
            border-radius: 2em;
            padding: 0.45em 1.7em;
            font-size: 1.08em;
            transition: background 0.2s;
        }

        .back-btn:hover {
            background: #c19b26;
            color: #23284a;
        }

        .btn-danger {
            background: #b71c1c;
            border-radius: 2em;
            padding: 0.45em 1.7em;
            font-weight: 700;
        }

        .table-bordered {
            border-color: #554c2a;
        }

        .table-bordered th {
            background: #554c2a;
            color: #ffe082;
            font-weight: 600;
            text-align: center;
            border: 1px solid #665c33;
        }

        .table-bordered td {
            background: #1a1e36;
            color: #fff;
            text-align: center;
            border: 1px solid #444;
            vertical-align: middle;
        }

        .form-select,
        .form-control {
            background: #1a1e36;
            color: #ffe082;
            border: 1px solid #d4af37;
        }

        .form-select:focus,
        .form-control:focus {
            background: #23284a;
            color: #fff;
            border-color: #ffe082;
            box-shadow: 0 0 0 0.25rem rgba(212, 175, 55, 0.25);
        }

        .badge {
            font-size: 0.9em;
        }
    </style>

    <div class="order-detail-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Order #{{ $order->order_number }}</h3>
            <span class="badge bg-secondary">{{ $order->created_at->format('F d, Y h:i A') }}</span>
        </div>

        <div class="row">
            <div class="col-md-6">
                <table class="table order-detail-table">
                    <tr>
                        <th><i class="fas fa-user"></i> Customer</th>
                        <td>{{ $order->user->name ?? $order->name }}</td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-envelope"></i> Email</th>
                        <td>{{ $order->email }}</td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-phone"></i> Phone</th>
                        <td>{{ $order->phone }}</td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-map-marker-alt"></i> Address</th>
                        <td>{{ $order->address }}<br>{{ $order->city }}, {{ $order->state }} -
                            {{ $order->zip }}<br>{{ $order->country }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table order-detail-table">
                    <tr>
                        <th><i class="fas fa-credit-card"></i> Payment</th>
                        <td>{{ ucfirst($order->payment_method) }}</td>
                    </tr>
                    @if($order->payment_transaction)
                        <tr>
                            <th><i class="fas fa-receipt"></i> Transaction ID</th>
                            <td>{{ $order->payment_transaction->transaction_id }}</td>
                        </tr>
                    @endif
                    <tr>
                        <th><i class="fas fa-info-circle"></i> Status</th>
                        <td>
                            <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                                @csrf
                                <div class="input-group">
                                    <select name="status" class="form-select form-select-sm" id="statusSelect">
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending
                                        </option>
                                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>
                                            Processing</option>
                                        <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped
                                        </option>
                                        <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>
                                            Delivered</option>
                                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>
                                            Cancelled</option>
                                        <option value="refunded" {{ $order->status == 'refunded' ? 'selected' : '' }}>Refunded
                                        </option>
                                    </select>
                                    <input type="text" name="tracking_number" class="form-control form-control-sm"
                                        placeholder="Tracking #" value="{{ $order->tracking_number }}"
                                        style="display: {{ $order->status == 'shipped' ? 'block' : 'none' }}; max-width: 150px;"
                                        id="trackingInput">
                                    <button type="submit" class="btn btn-sm btn-success">Update</button>
                                </div>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-truck"></i> Tracking</th>
                        <td>{{ $order->tracking_number ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <h4 class="mt-4 border-bottom border-secondary pb-2">Order Items</h4>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="text-start ps-4">
                                <div class="d-flex align-items-center">
                                    @if($item->product && $item->product->image)
                                        <img src="{{ asset($item->product->image) }}"
                                            style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px; margin-right: 10px;">
                                    @endif
                                    {{ $item->product_name }}
                                </div>
                            </td>
                            <td>₹{{ number_format($item->price, 2) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>₹{{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="4" class="text-end pe-4 fw-bold">Subtotal</td>
                        <td class="fw-bold">₹{{ number_format($order->subtotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-end pe-4 fw-bold">Tax</td>
                        <td class="fw-bold">₹{{ number_format($order->tax, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-end pe-4 fw-bold fs-5" style="color: #ffe082;">TOTAL</td>
                        <td class="fw-bold fs-5" style="color: #ffe082;">₹{{ number_format($order->total, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h4 class="mt-4 border-bottom border-secondary pb-2">Status History</h4>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Updated By</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($order->statusHistory as $history)
                        <tr>
                            <td>{{ $history->created_at->format('Y-m-d H:i') }}</td>
                            <td><span class="badge bg-info text-dark">{{ ucfirst($history->status) }}</span></td>
                            <td>{{ $history->creator->name ?? 'System' }}</td>
                            <td>{{ $history->notes }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No history recorded</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between mt-5">
            <a href="{{ route('admin.orders.index') }}" class="btn back-btn"><i class="fas fa-arrow-left me-1"></i> Back to
                Orders</a>

            <div>
                <a href="#" class="btn btn-info me-2 fw-bold"
                    onclick="window.open('{{ route('admin.orders.invoice', $order->id) }}', '_blank'); return false;">
                    <i class="fas fa-print me-1"></i> Print Invoice
                </a>
                <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"
                        onclick="return confirm('Are you sure you want to delete this order?')"><i
                            class="fas fa-trash me-1"></i> Delete Order</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('statusSelect').addEventListener('change', function () {
            var trackingInput = document.getElementById('trackingInput');
            trackingInput.style.display = this.value === 'shipped' ? 'block' : 'none';

            if (this.value === 'shipped') {
                trackingInput.focus();
            }
        });
    </script>
@endsection