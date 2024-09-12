<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar">
    <form class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
        </ul>

        <!-- Tautan ke halaman lain -->
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a href="{{ url('/dashboard') }}" class="nav-link">Home</a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/berkas') }}" class="nav-link">Berkas</a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/master') }}" class="nav-link">Master</a>

            </li>
            <li class="nav-item">
                <a href="" class="nav-link">Resume</a>
            </li>
            <li class="nav-item">
                <a href="" class="nav-link">User</a>
            </li>
        </ul>
    </form>
    <ul class="navbar-nav navbar-right">
        <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <div class="d-sm-none d-lg-inline-block">Hi, {{ auth()->user()->name }}</div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <!-- Profil -->
                <a href="" class="dropdown-item has-icon">
                    <i class="fas fa-user"></i> Profil
                </a>

                <!-- Pengaturan -->
                <a href="" class="dropdown-item has-icon">
                    <i class="fas fa-cog"></i> Pengaturan
                </a>

                <!-- Divider -->
                <div class="dropdown-divider"></div>

                <!-- Logout -->
                <a href="#" class="dropdown-item has-icon text-danger"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit()">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </li>
    </ul>
</nav>
