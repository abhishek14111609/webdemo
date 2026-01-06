@extends('layouts.master')

@section('title', 'My Account - Eternal Diamonds')

@push('styles')
<style>
    .account-hero {
        background: linear-gradient(120deg, #fffbe6 60%, #f9f9f9 100%);
        padding: 4rem 0 2rem 0;
        text-align: center;
        position: relative;
    }
    .account-hero h1 {
        font-size: 2.5rem;
        font-weight: 800;
        color: #1a1a1a;
        margin-bottom: 1.2rem;
        letter-spacing: 1px;
    }
    .profile-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 24px rgba(212,175,55,0.07);
        padding: 2.5rem 2rem 2rem 2rem;
        max-width: 420px;
        margin: -80px auto 2rem auto;
        position: relative;
        z-index: 2;
        border: 1.5px solid #f3e6b3;
        text-align: center;
    }
    .profile-img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid #d4af37;
        margin-bottom: 1rem;
        box-shadow: 0 2px 12px rgba(212,175,55,0.13);
    }
    .profile-card h3 {
        font-size: 1.3rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 0.3rem;
    }
    .profile-card .email {
        color: #bfa13a;
        font-size: 1.01rem;
        margin-bottom: 1.2rem;
    }
    .dropdown-menu {
        min-width: 180px;
        border-radius: 10px;
        border: 1.5px solid #f3e6b3;
        box-shadow: 0 4px 24px rgba(212,175,55,0.07);
    }
    .dropdown-item.active, .dropdown-item:active {
        background: linear-gradient(90deg, #d4af37 0%, #fffbe6 100%);
        color: #1a1a1a;
    }
    .btn-gold {
        background: linear-gradient(90deg, #d4af37 0%, #fffbe6 100%);
        color: #1a1a1a;
        border: none;
        font-weight: 700;
        padding: 0.6rem 2rem;
        border-radius: 8px;
        transition: background 0.3s, color 0.3s;
        box-shadow: 0 2px 8px rgba(212,175,55,0.09);
        margin-top: 1.2rem;
    }
    .btn-gold:hover {
        background: #d4af37;
        color: #fff;
    }
    .account-section {
        padding: 2rem 0 3rem 0;
    }
    .order-table th, .order-table td {
        vertical-align: middle;
        text-align: center;
    }
    .order-table th {
        background: #fffbe6;
        color: #bfa13a;
        font-weight: 700;
        border-top: none;
    }
    .order-table td {
        background: #fff;
        border-top: none;
    }
    .logout-btn {
        width: 100%;
        text-align: left;
        background: none;
        border: none;
        color: #d9534f;
        font-weight: 600;
        padding: 0.5rem 1.5rem;
        border-radius: 8px;
        transition: background 0.2s, color 0.2s;
    }
    .logout-btn:hover {
        background: #fffbe6;
        color: #a94442;
    }
    @media (max-width: 768px) {
        .account-hero h1 { font-size: 1.5rem; }
        .account-section { padding: 1rem 0 2rem 0; }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabLinks = document.querySelectorAll('.account-tab-link');
        const tabContents = document.querySelectorAll('.account-tab-content');
        tabLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                tabLinks.forEach(l => l.classList.remove('active'));
                tabContents.forEach(c => c.style.display = 'none');
                this.classList.add('active');
                document.getElementById(this.dataset.tab).style.display = 'block';
            });
        });
        // Show default tab
        document.querySelector('.account-tab-link.active').click();
    });
</script>
@endpush

@section('content')
<div class="account-hero">
    <h1>My Account</h1>
</div>
<div class="profile-card">
    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Profile" class="profile-img">
    <h3>Alexander Stone</h3>
    <div class="email">alexander@example.com</div>
    <div class="dropdown mt-3">
        <button class="btn btn-gold dropdown-toggle w-100" type="button" id="accountDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            Account Menu
        </button>
        <ul class="dropdown-menu w-100" aria-labelledby="accountDropdown">
            <li><a class="dropdown-item account-tab-link active" href="#" data-tab="profile-tab">Profile</a></li>
            <li><a class="dropdown-item account-tab-link" href="#" data-tab="orders-tab">Order History</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </li>
        </ul>
    </div>
</div>
<section class="account-section container">
    <div id="profile-tab" class="account-tab-content" style="display: none;">
        <h4 class="mb-4">Profile Information</h4>
        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Name:</strong> Alexander Stone
            </div>
            <div class="col-md-6">
                <strong>Email:</strong> alexander@example.com
            </div>
        </div>
        <a href="#" class="btn btn-gold">Edit Profile</a>
    </div>
    <div id="orders-tab" class="account-tab-content" style="display: none;">
        <h4 class="mb-4">Order History</h4>
        <div class="table-responsive">
            <table class="table order-table align-middle">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1001</td>
                        <td>2024-05-01</td>
                        <td><span class="badge bg-success">Completed</span></td>
                        <td>$2,500</td>
                    </tr>
                    <tr>
                        <td>1002</td>
                        <td>2024-05-10</td>
                        <td><span class="badge bg-warning text-dark">Pending</span></td>
                        <td>$1,800</td>
                    </tr>
                    {{-- More orders here --}}
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection 