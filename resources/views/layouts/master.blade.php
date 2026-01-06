<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Pardise Diamonds')</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')

    <style>
        body {
            font-family: 'Figtree', sans-serif;
            padding-top: 80px;
            margin: 0;
        }

        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: #fff;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 15px 5%;
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 10px 5%;
        }

        .logo {
            display: flex;
            align-items: center;
            font-weight: bold;
            font-size: 20px;
            color: #222;
            text-decoration: none;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            font-weight: bold;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 25px;
        }

        .nav-links a,
        .dropdown-toggle {
            text-transform: uppercase;
            font-weight: 500;
            color: #333;
            text-decoration: none;
            position: relative;
        }

        .nav-links a:hover,
        .nav-links a.active,
        .dropdown-toggle:hover {
            color: #d4af37;
        }

        .nav-icons {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .nav-icons a {
            color: #333;
            font-size: 18px;
            position: relative;
        }

        .nav-icons a:hover {
            color: #d4af37;
        }

        .cart-count {
            position: absolute;
            top: -8px;
            right: -10px;
            background: #d4af37;
            color: #fff;
            font-size: 10px;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .hamburger {
            display: none;
            font-size: 24px;
            cursor: pointer;
        }

        .profile-dropdown {
            min-width: 220px;
        }

        .profile-dropdown .dropdown-item i {
            width: 20px;
        }

        @media (max-width: 768px) {
            .nav-links {
                flex-direction: column;
                position: fixed;
                top: 70px;
                left: -100%;
                width: 80%;
                height: 100vh;
                background: #fff;
                padding: 30px 20px;
                box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
                transition: left 0.3s ease;
                z-index: 999;
                gap: 20px;
            }

            .nav-links.active {
                left: 0;
            }

            .nav-links a {
                width: 100%;
                text-align: center;
                padding: 12px 0;
                border-bottom: 1px solid #eee;
            }

            .nav-icons a:not(:last-child) {
                display: none;
            }

            .hamburger {
                display: block;
            }

            .dropdown-menu {
                position: static !important;
                width: 100%;
                border-radius: 0;
            }
        }

        footer {
            background-color: #111;
            color: #eee;
            padding: 60px 5% 30px;
        }

        footer h5 {
            color: #d4af37;
            margin-bottom: 20px;
        }

        footer ul {
            list-style: none;
            padding: 0;
        }

        footer ul li a {
            color: #aaa;
            text-decoration: none;
            display: block;
            padding: 6px 0;
        }

        footer ul li a:hover {
            color: #fff;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 30px;
            font-size: 14px;
            border-top: 1px solid #444;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar d-flex justify-content-between align-items-center">
        <div class="logo">
            <div class="logo-icon">PD</div>
            <u> <span>PARDISE DIAMONDS</span></u>
        </div>


        <div class="nav-links">
            <a href="/" class="{{ request()->is('/') ? 'active' : '' }}">Home</a>
            <a href="/shop" class="{{ request()->is('shop*') ? 'active' : '' }}">Shop</a>

            <!-- Dynamic Categories Dropdown -->
            <div class="dropdown">
                <a href="#" class="dropdown-toggle {{ request()->is('category*') ? 'active' : '' }}"
                    data-bs-toggle="dropdown">Categories</a>
                <ul class="dropdown-menu">
                    @forelse($navbarCategories as $category)
                    <li><a class="dropdown-item"
                            href="{{ route('category', $category->slug) }}">{{ $category->name }}</a></li>
                    @empty
                    <li><a class="dropdown-item" href="#">No categories available</a></li>
                    @endforelse
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="{{ route('categories') }}">View All Categories</a></li>
                </ul>
            </div>

            <!-- Dynamic Collections Dropdown -->
            <div class="dropdown">
                <a href="#" class="dropdown-toggle {{ request()->is('collections*') ? 'active' : '' }}"
                    data-bs-toggle="dropdown">Collections</a>
                <ul class="dropdown-menu">
                    @forelse($navbarCollections as $collection)
                    <li><a class="dropdown-item"
                            href="{{ route('collection.show', $collection->slug) }}">{{ $collection->title }}</a></li>
                    @empty
                    <li><a class="dropdown-item" href="#">No collections available</a></li>
                    @endforelse
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="{{ route('collections') }}">View All Collections</a></li>
                </ul>
            </div>

            <a href="/about" class="{{ request()->is('about') ? 'active' : '' }}">About Us</a>
            <a href="/contact" class="{{ request()->is('contact') ? 'active' : '' }}">Contact</a>

        </div>

        <div class="nav-icons">
           

            @auth
            @if(Auth::user()->is_admin)
            <a href="{{ route('admin.dashboard') }}">Admin Panel</a>
            @endif
            <div class="dropdown">
                <a href="#" class="dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                    <img src="{{ Auth::user()->profile_pic ? asset('storage/' . Auth::user()->profile_pic) : 'https://randomuser.me/api/portraits/men/32.jpg' }}"
                        class="rounded-circle" style="width:32px;height:32px;object-fit:cover;margin-right:8px;"
                        alt="{{ Auth::user()->name }}">
                    <span class="d-none d-sm-inline fw-bold text-warning">{{ Auth::user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end profile-dropdown">
                    <li class="px-3 py-2 border-bottom d-flex align-items-center">
                        <img src="{{ Auth::user()->profile_pic ? asset('storage/' . Auth::user()->profile_pic) : 'https://randomuser.me/api/portraits/men/32.jpg' }}"
                            class="rounded-circle me-2" style="width:36px;height:36px;object-fit:cover;"
                            alt="{{ Auth::user()->name }}">
                        <div>
                            <div class="fw-bold">{{ Auth::user()->name }}</div>
                            <div class="text-muted small">{{ Auth::user()->email }}</div>
                        </div>
                    </li>
                    <li><a class="dropdown-item" href="/profile"><i class="fas fa-user text-muted me-2"></i> Profile</a>
                    </li>
                    <li><a class="dropdown-item" href="/orders"><i class="fas fa-box text-muted me-2"></i> Orders</a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item text-danger" type="submit"><i
                                    class="fas fa-sign-out-alt me-2"></i> Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
            <a href="{{ route('wishlist') }}"><i class="far fa-heart"></i></a>
            <a href="{{ route('cart.index') }}"><i class="fas fa-shopping-bag"></i>
                @php
                $cartCount = 0;
                if (auth()->check() && auth()->user()->cart && auth()->user()->cart->cartItems) {
                $cartCount = auth()->user()->cart->cartItems->sum('quantity');
                }
                @endphp
                <span class="cart-count">{{ $cartCount }}</span>
            </a>
            @else
            <a href="{{ route('login') }}">Login</a>
            <a href="{{ route('register') }}">Sign Up</a>
            @endauth

            <div class="hamburger"><i class="fas fa-bars"></i></div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>


    <footer class="mt-5">
        <div class="row text-center text-md-start">
            <div class="col-md-3 mb-4">
                <h5>About Eternal</h5>
                <p>Luxury redefined. Explore the finest handcrafted diamond jewelry, made with love and precision.</p>
            </div>
            <div class="col-md-2 mb-4">
                <h5>Quick Links</h5>
                <ul>
                    <li><a href="/">Home</a></li>
                    <li><a href="/shop">Shop</a></li>
                    <li><a href="/about">About Us</a></li>
                    <li><a href="/contact">Contact</a></li>
                </ul>
            </div>
            <div class="col-md-2 mb-4">
                <h5>Collections</h5>
                <ul>
                    @forelse($navbarCollections->take(3) as $collection)
                    <li><a href="{{ route('collection.show', $collection->slug) }}">{{ $collection->title }}</a></li>
                    @empty
                    <li><a href="#">No collections available</a></li>
                    @endforelse
                    <li><a href="{{ route('collections') }}">All Collections</a></li>
                </ul>
            </div>
            <div class="col-md-2 mb-4">
                <h5>Customer Care</h5>
                <ul>
                    <li><a href="/profile">My Account</a></li>
                    <li><a href="/orders">My Orders</a></li>
                    <li><a href="/faq">FAQs</a></li>
                    <li><a href="/support">Support</a></li>
                </ul>
            </div>
            <div class="col-md-3 mb-4">
                <h5>Follow Us</h5>
                <div class="d-flex gap-3">
                    <a href="#" class="text-light"><i class="fab fa-facebook fa-lg"></i></a>
                    <a href="#" class="text-light"><i class="fab fa-instagram fa-lg"></i></a>
                    <a href="#" class="text-light"><i class="fab fa-twitter fa-lg"></i></a>
                    <a href="#" class="text-light"><i class="fab fa-youtube fa-lg"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom mt-4">
            &copy; {{ date('Y') }} Eternal Diamonds. All rights reserved.
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hamburger = document.querySelector('.hamburger');
            const navLinks = document.querySelector('.nav-links');
            const navbar = document.querySelector('.navbar');

            hamburger?.addEventListener('click', function(e) {
                e.stopPropagation();
                navLinks?.classList.toggle('active');
                this.classList.toggle('active');
            });

            document.addEventListener('click', function(e) {
                if (!e.target.closest('.navbar')) {
                    navLinks?.classList.remove('active');
                    hamburger?.classList.remove('active');
                }
            });

            window.addEventListener('scroll', function() {
                if (window.scrollY > 50) {
                    navbar?.classList.add('scrolled');
                } else {
                    navbar?.classList.remove('scrolled');
                }
            });
        });
    </script>
    @stack('scripts')
</body>

</html>
