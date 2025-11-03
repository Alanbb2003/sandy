<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts and Styles -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <script src="https://kit.fontawesome.com/4e280bc07f.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.7/css/dataTables.dataTables.css" />
    <link href="https://cdn.jsdelivr.net/npm/lightbox2@2.11.3/dist/css/lightbox.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/lightbox2@2.11.3/dist/js/lightbox.min.js"></script>
    <style>
        body {
            background-color: #f5f5f5;
        }
        .whiteTxt {
            color: white;
        }
    </style>
</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-light bg-info shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a href="{{ url('/membership') }}" class="nav-link">
                            <i class="fa-regular fa-address-card"></i> Membership
                        </a>
                    </li>
                </ul>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Right Side of Navbar -->
                <ul class="navbar-nav ms-auto d-flex align-items-center">
                    @guest
                        <li class="nav-item me-3">
                            <a href="{{ url('/cart') }}" class="btn position-relative mx-1">
                            <i class="fa-solid fa-cart-shopping fa-lg"></i>
                            @if(session('cart'))
                                @php
                                    $cartItems = session()->get('cart', []);
                                    $items_in_cart = array_sum(array_column($cartItems, 'quantity'));
                                @endphp
                                <span class="position-absolute top-0 start-100 translate-middle badge bg-danger">
                                    {{ $items_in_cart }}
                                    <span class="visually-hidden">items in cart</span>
                                </span>
                            @endif
                        </a>
                        </li>
                        <li class="nav-item me-3">
                            <a class="nav-link" href="{{ url('/wishlist') }}">
                                <i class="fa-solid fa-heart fa-lg"></i>
                            </a>
                        </li>
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link border rounded px-3 py-1" href="{{ route('login') }}">
                                    <i class="fa-solid fa-user"></i> {{ __('Login') }}
                                </a>
                            </li>
                        @endif
                    @else
                        <!-- Icons Section -->
                        <li class="nav-item me-3">
                            <a href="{{ url('/cart') }}" class="btn position-relative mx-1">
                            <i class="fa-solid fa-cart-shopping fa-lg"></i>
                            @if(session('cart'))
                                @php
                                    $cartItems = session()->get('cart', []);
                                    $items_in_cart = array_sum(array_column($cartItems, 'quantity'));
                                @endphp
                                <span class="position-absolute top-0 start-100 translate-middle badge bg-danger">
                                    {{ $items_in_cart }}
                                    <span class="visually-hidden">items in cart</span>
                                </span>
                            @endif
                        </a>
                        </li>
                        <li class="nav-item me-3">
                            <a class="nav-link" href="{{ url('/wishlist') }}">
                                <i class="fa-solid fa-heart fa-lg"></i>
                            </a>
                        </li>
                        <li class="nav-item me-3">
                            <a class="nav-link" href="{{ url('/transaction') }}">
                                <i class="fa-solid fa-clock-rotate-left fa-lg"></i>
                            </a>
                        </li>

                        <!-- User Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                @if(Auth::user()->picture)
                                    <img src="{{ asset('images/photos/' . Auth::user()->picture) }}" alt="Profile Picture" class="rounded-circle me-2" width="30" height="30">
                                @else
                                    <i class="fa-solid fa-user fa-lg me-2"></i>
                                @endif
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="{{ url('/profile') }}">Profile</a></li>
                                <li><a class="dropdown-item" href="{{ url('/wishlist') }}">Wishlist</a></li>
                                <li><a class="dropdown-item" href="{{ url('/transaction') }}">Riwayat Transaksi</a></li>
                                <li><a class="dropdown-item" href="{{ url('/retur') }}">Retur</a></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    <main class="py-4">
        @include('sweetalert::alert')
        @yield('content')
    </main>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.1.7/js/dataTables.js"></script>
@yield('script')
</body>
</html>