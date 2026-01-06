@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">{{ $user->name }}</h1>
        <p class="text-muted mb-0">Member since {{ $user->created_at->format('F j, Y') }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.users') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Users
        </a>
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
            <i class="fas fa-edit me-1"></i> Edit User
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold">User Information</h6>
            </div>
            <div class="card-body text-center">
                <div class="avatar-circle bg-primary text-white mb-3">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h5>{{ $user->name }}</h5>
                <p class="text-muted">
                    <i class="fas fa-user-shield me-1"></i>
                    {{ $user->is_admin ? 'Administrator' : 'Customer' }}
                </p>
                <hr>
                <ul class="list-unstyled text-start">
                    <li class="mb-2">
                        <i class="fas fa-envelope me-2 text-muted"></i>
                        {{ $user->email }}
                    </li>
                    @if($user->phone)
                        <li class="mb-2">
                            <i class="fas fa-phone me-2 text-muted"></i>
                            {{ $user->phone }}
                        </li>
                    @endif
                    <li class="mb-0">
                        <i class="fas fa-calendar-alt me-2 text-muted"></i>
                        Joined {{ $user->created_at->diffForHumans() }}
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold">Recent Orders</h6>
            </div>
            <div class="card-body p-0">
                @if($orders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Order #</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td>#{{ $order->order_number }}</td>
                                        <td>{{ $order->created_at->format('M j, Y') }}</td>
                                        <td>
                                            <span class="badge {{ ['completed' => 'bg-success', 'processing' => 'bg-info', 'cancelled' => 'bg-danger'][$order->status] ?? 'bg-warning' }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="text-end">₹{{ number_format($order->total, 2) }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3">
                        {{ $orders->links() }}
                    </div>
                @else
                    <div class="text-center p-5">
                        <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                        <p class="mb-0">No orders found for this user.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .avatar-circle {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-weight: 600;
        margin: 0 auto 1rem;
    }
</style>
@endpush
@endsection