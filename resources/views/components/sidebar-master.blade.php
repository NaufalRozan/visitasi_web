<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a>{{ Auth::user()->prodi->nama_prodi }}</a> <!-- Menampilkan nama prodi -->
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="home">{{ substr(Auth::user()->prodi->nama_prodi, 0, 3) }}</a> <!-- Singkatan prodi -->
        </div>
        <ul class="sidebar-menu">
            <li class="nav-item">
                <a href="standar" class="nav-link"><i class="fas fa-book"></i>
                    <span>Standar</span></a>
            </li>
            <li class="nav-item">
                <a href="substandar" class="nav-link"><i class="fas fa-book"></i>
                    <span>Substandar</span></a>
            </li>
            <li class="nav-item">
                <a href="" class="nav-link"><i class="fas fa-book"></i>
                    <span>Detail</span></a>
            </li>
        </ul>
    </aside>
</div>
