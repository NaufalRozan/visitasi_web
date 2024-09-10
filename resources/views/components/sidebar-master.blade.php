<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        @php
            $prodi_id = session('prodi_id');
            $prodi = \App\Models\Prodi::find($prodi_id);
        @endphp
        <div class="sidebar-brand">
            <a>{{ $prodi ? $prodi->nama_prodi : 'Prodi Tidak Ditemukan' }}</a> <!-- Menampilkan nama prodi -->
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="home">{{ $prodi ? substr($prodi->nama_prodi, 0, 3) : 'Prodi' }}</a> <!-- Singkatan prodi -->
        </div>
        <ul class="sidebar-menu">
            <li class="nav-item">
                <a href="akreditasi" class="nav-link"><i class="fas fa-book"></i>
                    <span>Akreditasi</span></a>
            </li>
            <li class="nav-item">
                <a href="standar" class="nav-link"><i class="fas fa-book"></i>
                    <span>Bagian</span></a>
            </li>
            <li class="nav-item">
                <a href="substandar" class="nav-link"><i class="fas fa-book"></i>
                    <span>Sub-Bagian</span></a>
            </li>
            <li class="nav-item">
                <a href="detail" class="nav-link"><i class="fas fa-book"></i>
                    <span>Detail</span></a>
            </li>
        </ul>
    </aside>
</div>
