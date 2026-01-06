<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f4f6f9; }
        .admin-sidebar {
            min-height: 100vh;
            background: #181c2f;
            color: #fff;
            padding: 0;
            position: sticky;
            top: 0;
            left: 0;
            z-index: 1045;
            width: 250px;
            transition: none;
        }
        @media (min-width: 992px) {
            .admin-sidebar {
                transform: none !important;
                position: sticky;
                left: 0;
                box-shadow: none;
                display: block !important;
            }
            #adminMain {
                margin-left: 250px;
                width: calc(100% - 250px);
            }
            .sidebar-backdrop {
                display: none !important;
            }
        }
        .admin-sidebar .profile {
            padding: 32px 0 24px 0;
            text-align: center;
            border-bottom: 1px solid #23284a;
        }
        .admin-sidebar .profile img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #d4af37;
        }
        .admin-sidebar .profile .name {
            margin-top: 12px;
            font-weight: 600;
            font-size: 18px;
            color: #fff;
        }
        .admin-sidebar .profile .email {
            font-size: 13px;
            color: #b0b3c7;
        }
        .admin-sidebar .nav-link {
            color: #b0b3c7;
            font-weight: 500;
            padding: 16px 30px;
            display: flex;
            align-items: center;
            gap: 14px;
            font-size: 16px;
            border-left: 4px solid transparent;
            transition: background 0.2s, color 0.2s, border-color 0.2s;
        }
        .admin-sidebar .nav-link.active, .admin-sidebar .nav-link:hover {
            background: #23284a;
            color: #d4af37;
            border-left: 4px solid #d4af37;
        }
        .admin-header {
            background: #fff;
            padding: 24px 32px 16px 32px;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .admin-header h2 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            color: #181c2f;
        }
        /* Responsive Sidebar */
        @media (max-width: 991.98px) {
            .admin-sidebar {
                min-height: 100vh;
                position: fixed;
                top: 0;
                left: 0;
                z-index: 1045;
                width: 250px;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                box-shadow: 2px 0 12px rgba(0,0,0,0.08);
            }
            .admin-sidebar.show {
                transform: translateX(0);
            }
            .admin-sidebar .profile {
                padding: 24px 0 16px 0;
            }
            .admin-header {
                padding: 18px 16px 12px 16px;
            }
            .sidebar-backdrop {
                display: none;
                position: fixed;
                top: 0; left: 0; right: 0; bottom: 0;
                background: rgba(0,0,0,0.3);
                z-index: 1040;
            }
            .sidebar-backdrop.show {
                display: block;
            }
            #adminMain {
                margin-left: 0;
                width: 100%;
            }
        }
        @media (max-width: 575.98px) {
            .admin-header h2 {
                font-size: 1.2rem;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
<div class="container-fluid p-0">
    <div class="row g-0">
        <!-- Sidebar for desktop, offcanvas for mobile -->
        <nav id="adminSidebar" class="col-md-2 admin-sidebar d-flex flex-column p-0">
            <div class="profile">
                <img src="{{ Auth::user()->profile_pic ? asset('storage/' . Auth::user()->profile_pic) : 'https://randomuser.me/api/portraits/men/32.jpg' }}" alt="Admin Avatar">
                <div class="name">{{ Auth::user()->name }}</div>
                <div class="email">{{ Auth::user()->email }}</div>
            </div>
            <ul class="nav flex-column mt-4">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}"><i class="fas fa-users"></i> Users</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.orders') }}" class="nav-link {{ request()->routeIs('admin.orders') ? 'active' : '' }}"><i class="fas fa-box"></i> Orders</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}"><i class="fas fa-gem"></i> Products</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.sliders.index') }}" class="nav-link {{ request()->routeIs('admin.sliders.*') ? 'active' : '' }}"><i class="fas fa-images"></i> Sliders</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.inquiries.index') }}" class="nav-link {{ request()->routeIs('admin.inquiries.*') ? 'active' : '' }}"><i class="fas fa-envelope"></i> Inquiries</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.about.index') }}" class="nav-link {{ request()->routeIs('admin.about.index') ? 'active' : '' }}"><i class="fas fa-info-circle"></i> About</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}"><i class="fas fa-cog"></i> Categories</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.collections.index') }}" class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}"><i class="fas fa-cog"></i> Collection</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.settings') }}" class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}"><i class="fas fa-cog"></i> Settings</a>
                </li>
                <li class="nav-item mt-4">
                    <a href="/" class="nav-link"><i class="fas fa-home"></i> Back to Site</a>
                </li>
            </ul>
        </nav>
        <div id="sidebarBackdrop" class="sidebar-backdrop"></div>
        <main class="col-md-10 ms-sm-auto px-0" id="adminMain">
            <div class="admin-header">
                <div class="d-flex align-items-center gap-2">
                    <!-- Hamburger for mobile -->
                    <button class="btn btn-outline-dark d-md-none me-2" id="sidebarToggle" type="button" aria-label="Toggle sidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h2 class="mb-0">@yield('title', 'Admin Panel')</h2>
                </div>
                <div>
                    <a href="{{ route('logout') }}" class="btn btn-outline-danger btn-sm"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
            <div class="p-4">
                @yield('content')
            </div>
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Sidebar toggle for mobile
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('adminSidebar');
        const toggleBtn = document.getElementById('sidebarToggle');
        const backdrop = document.getElementById('sidebarBackdrop');
        function showSidebar() {
            sidebar.classList.add('show');
            backdrop.classList.add('show');
        }
        function hideSidebar() {
            sidebar.classList.remove('show');
            backdrop.classList.remove('show');
        }
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                if (sidebar.classList.contains('show')) {
                    hideSidebar();
                } else {
                    showSidebar();
                }
            });
        }
        if (backdrop) {
            backdrop.addEventListener('click', hideSidebar);
        }
        // Hide sidebar and backdrop on resize to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 992) {
                sidebar.classList.remove('show');
                backdrop.classList.remove('show');
            }
        });
    });
</script>
@stack('scripts')
</body>
</html>