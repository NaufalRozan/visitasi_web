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
            <!-- Menu lainnya -->
        </ul>
    </aside>
</div>
