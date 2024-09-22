<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a>Arsip Akreditasi</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="home">AA</a>
        </div>
        <ul class="sidebar-menu">
            <!-- Menu lainnya -->
            @if(auth()->user()->role == 'Universitas')
            <li class="menu-header">User Management</li>
            <li>
                <a href="{{ url('/user') }}" class="nav-link">
                    <i class="fas fa-user-plus"></i> <span>List User</span>
                </a>
                <a href="{{ route('user.create') }}" class="nav-link">
                    <i class="fas fa-user-plus"></i> <span>Tambah User</span>
                </a>
            </li>
            @endif
        </ul>
    </aside>
</div>
