<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Console - ModestMirror')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    
    <!-- FontAwesome 6 Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet" crossorigin="anonymous">
    
    <!-- Custom Style -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    
    <style>
        .admin-sidebar {
            min-height: 100vh;
            background-color: var(--primary-coffee);
            color: var(--background-beige);
            border-right: 2px solid var(--gold-accent);
        }
        .admin-sidebar .nav-link {
            color: rgba(245, 240, 230, 0.7);
            font-weight: 500;
            padding: 12px 20px;
            transition: var(--transition-smooth);
            border-left: 3px solid transparent;
        }
        .admin-sidebar .nav-link:hover,
        .admin-sidebar .nav-link.active {
            color: var(--white);
            background-color: rgba(200, 169, 106, 0.1);
            border-left-color: var(--gold-accent);
        }
        .admin-main {
            background-color: var(--background-beige);
            min-height: 100vh;
        }
    </style>
    @yield('styles')
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 px-0 admin-sidebar d-flex flex-column">
            <div class="p-4 text-center">
                <a class="brand-font fs-3 text-white text-decoration-none" href="{{ route('home') }}">
                    Modest<span style="color: var(--gold-accent);">Mirror</span>
                </a>
                <div class="small text-gold text-uppercase font-monospace mt-1" style="font-size: 0.65rem; letter-spacing: 2px;">Admin Console</div>
            </div>
            
            <hr class="mx-3" style="border-color: rgba(245, 240, 230, 0.15);">
            
            <ul class="nav flex-column mb-auto">
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteName() == 'admin.dashboard' ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="fa-solid fa-gauge-high me-2 text-warning"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteName() == 'admin.categories' ? 'active' : '' }}" href="{{ route('admin.categories') }}">
                        <i class="fa-solid fa-folder me-2"></i>Categories
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteName() == 'admin.products' ? 'active' : '' }}" href="{{ route('admin.products') }}">
                        <i class="fa-solid fa-shirt me-2"></i>Products
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteName() == 'admin.orders' ? 'active' : '' }}" href="{{ route('admin.orders') }}">
                        <i class="fa-solid fa-receipt me-2"></i>Orders
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteName() == 'admin.users' ? 'active' : '' }}" href="{{ route('admin.users') }}">
                        <i class="fa-solid fa-users me-2"></i>Users
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteName() == 'admin.ar-assets' ? 'active' : '' }}" href="{{ route('admin.ar-assets') }}">
                        <i class="fa-solid fa-camera me-2"></i>AR Assets
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteName() == 'admin.reviews' ? 'active' : '' }}" href="{{ route('admin.reviews') }}">
                        <i class="fa-solid fa-star me-2 text-warning"></i>Reviews Moderation
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteName() == 'admin.subscribers' ? 'active' : '' }}" href="{{ route('admin.subscribers') }}">
                        <i class="fa-solid fa-envelope me-2"></i>Subscribers
                    </a>
                </li>
            </ul>

            <hr class="mx-3" style="border-color: rgba(245, 240, 230, 0.15);">

            <div class="p-3">
                <a href="{{ route('home') }}" class="btn btn-luxury-outline w-100 border-white text-white btn-sm py-2 mb-2 text-center text-decoration-none">
                    <i class="fa-solid fa-shop me-2"></i>Storefront
                </a>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100 btn-sm py-2"><i class="fa-solid fa-sign-out me-2"></i>Logout</button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 px-4 py-4 admin-main">
            <!-- Header bar -->
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom" style="border-color: var(--light-sand) !important;">
                <h2 class="brand-font mb-0">@yield('page_title', 'Dashboard')</h2>
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted small">Logged in as:</span>
                    <strong class="text-dark">{{ auth()->user()->name }}</strong>
                </div>
            </div>
            
            @yield('content')
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>

@yield('scripts')
</body>
</html>
