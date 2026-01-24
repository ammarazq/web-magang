<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-shield-alt"></i> Admin Portal
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                       href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.verifikasi.list') || request()->routeIs('admin.detail') ? 'active' : '' }}" 
                       href="{{ route('admin.verifikasi.list') }}">
                        <i class="fas fa-clipboard-check"></i> Verifikasi Dokumen
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}" 
                       href="{{ route('admin.users') }}">
                        <i class="fas fa-users-cog"></i> User Management
                    </a>
                </li>
            </ul>
            <div class="navbar-nav">
                <span class="navbar-text text-white me-3">
                    <i class="fas fa-user-shield"></i> {{ Auth::user()->name }} 
                    <small class="text-muted">(ID: {{ Auth::user()->id }})</small>
                </span>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<style>
    .navbar-nav .nav-link.active {
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 5px;
    }
    .navbar-nav .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.05);
        border-radius: 5px;
    }
</style>
