<!-- Top Bar -->
<div class="top-bar">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="social-icons">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <div class="col-md-6 text-end">
                <span class="top-contact">
                    <i class="fas fa-phone-alt"></i> +62 123 456 789
                    <span class="ms-3"><i class="fas fa-envelope"></i> admin@portal.ac.id</span>
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Main Navbar -->
<nav class="navbar navbar-expand-lg navbar-modern">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-fire brand-icon"></i>
            <span class="brand-text">Admin Portal</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                       href="{{ route('admin.dashboard') }}">
                        DASHBOARD
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.verifikasi.list') || request()->routeIs('admin.detail') ? 'active' : '' }}" 
                       href="{{ route('admin.verifikasi.list') }}">
                        VERIFIKASI DOKUMEN
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}" 
                       href="{{ route('admin.users') }}">
                        USER MANAGEMENT
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-shield"></i> {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user"></i> Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
    .top-bar {
        background: #0a1128;
        color: #fff;
        padding: 8px 0;
        font-size: 13px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    .social-icons a {
        color: #fff;
        margin-right: 15px;
        font-size: 14px;
        transition: color 0.3s;
    }
    .social-icons a:hover {
        color: #ff6b35;
    }
    .top-contact {
        color: #fff;
    }
    .top-contact i {
        color: #ff6b35;
        margin-right: 5px;
    }
    .navbar-modern {
        background: #1a1d3f;
        padding: 15px 0;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .navbar-brand {
        display: flex;
        align-items: center;
        color: #fff !important;
        font-weight: bold;
        font-size: 24px;
    }
    .brand-icon {
        color: #ff6b35;
        font-size: 28px;
        margin-right: 10px;
    }
    .brand-text {
        color: #fff;
    }
    .navbar-modern .navbar-nav .nav-link {
        color: #fff !important;
        font-weight: 500;
        padding: 8px 20px !important;
        margin: 0 5px;
        font-size: 13px;
        letter-spacing: 0.5px;
        transition: all 0.3s;
        text-transform: uppercase;
    }
    .navbar-modern .navbar-nav .nav-link:hover {
        color: #ff6b35 !important;
    }
    .navbar-modern .navbar-nav .nav-link.active {
        color: #ff6b35 !important;
        position: relative;
    }
    .navbar-modern .navbar-nav .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 20px;
        right: 20px;
        height: 2px;
        background: #ff6b35;
    }
    .navbar-modern .dropdown-menu {
        background: #1a1d3f;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .navbar-modern .dropdown-item {
        color: #fff;
        transition: all 0.3s;
    }
    .navbar-modern .dropdown-item:hover {
        background: rgba(255, 107, 53, 0.1);
        color: #ff6b35;
    }
    .navbar-modern .dropdown-divider {
        border-color: rgba(255, 255, 255, 0.1);
    }
    .navbar-toggler {
        border-color: rgba(255, 255, 255, 0.3);
    }
    .navbar-toggler-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.8%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }
    .navbar-nav .nav-link.active {
    .navbar-nav .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.05);
        border-radius: 5px;
    }
</style>
