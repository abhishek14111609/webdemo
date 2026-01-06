@extends('admin.layout')

@section('title', 'Admin Dashboard')

@section('content')
<style>
    .admin-profile-card {
        background: #23284a;
        color: #ffe082;
        border-radius: 1.5em;
        box-shadow: 0 2px 12px rgba(0,0,0,0.12);
        padding: 2em 2em 1.5em 2em;
        margin-bottom: 2em;
        display: flex;
        align-items: center;
        gap: 2em;
    }
    .admin-profile-card img {
        width: 90px;
        height: 90px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #d4af37;
    }
    .admin-profile-info .name {
        font-size: 1.5em;
        font-weight: 700;
        color: #ffe082;
    }
    .admin-profile-info .email {
        color: #b0b3c7;
        font-size: 1.1em;
        margin-bottom: 0.5em;
    }
    .admin-profile-info .badge {
        font-size: 1em;
        padding: 0.5em 1.2em;
        border-radius: 2em;
        font-weight: 600;
        background: #d4af37;
        color: #23284a;
    }
    .stat-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
</style>

<div class="admin-profile-card">
    <img src="{{ Auth::user()->profile_pic ? asset('storage/' . Auth::user()->profile_pic) : 'https://randomuser.me/api/portraits/men/32.jpg' }}" alt="Admin Avatar">
    <div class="admin-profile-info">
        <div class="name">{{ Auth::user()->name }}</div>
        <div class="email"><i class="fas fa-envelope me-1"></i> {{ Auth::user()->email }}</div>
        <span class="badge"><i class="fas fa-user-shield me-1"></i> Admin</span>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-4">
    <div class="col-md-3">
        <div class="card text-center shadow-sm border-0 stat-card">
            <div class="card-body">
                <i class="fas fa-users fa-2x text-primary mb-2"></i>
                <h6 class="mb-0">Total Users</h6>
                <div class="fw-bold display-6">{{ $stats['totalUsers'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center shadow-sm border-0 stat-card">
            <div class="card-body">
                <i class="fas fa-box fa-2x text-success mb-2"></i>
                <h6 class="mb-0">Total Orders</h6>
                <div class="fw-bold display-6">{{ $stats['totalOrders'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center shadow-sm border-0 stat-card">
            <div class="card-body">
                <i class="fas fa-rupee-sign fa-2x text-info mb-2"></i>
                <h6 class="mb-0">Total Revenue</h6>
                <div class="fw-bold display-6">₹{{ number_format($stats['revenue'], 2) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center shadow-sm border-0 stat-card">
            <div class="card-body">
                <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                <h6 class="mb-0">Pending Orders</h6>
                <div class="fw-bold display-6">{{ $stats['pendingOrders'] ?? 0 }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Inquiries -->
<div class="card shadow-sm border-0 mt-4">
    <div class="card-body">
        <h5 class="card-title mb-3">Recent Inquiries</h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Message</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stats['recentInquiries'] as $inquiry)
                    <tr>
                        <td>{{ $inquiry->name }}</td>
                        <td>{{ $inquiry->email }}</td>
                        <td>{{ Str::limit($inquiry->message, 30) }}</td>
                        <td>{{ $inquiry->created_at->format('M d, Y') }}</td>
                        <td>
                            @if($inquiry->is_responded)
                                <span class="badge bg-success">Responded</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.inquiries.show', $inquiry->id) }}" class="btn btn-sm btn-primary">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection