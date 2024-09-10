<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        @php
            $prodi_id = session('prodi_id');
            $prodi = \App\Models\Prodi::find($prodi_id);

            // Ambil akreditasi yang aktif
            $akreditasi_aktif = \App\Models\Akreditasi::where('prodi_id', $prodi_id)->where('status', 'aktif')->first();

            // Ambil standar berdasarkan akreditasi yang aktif
            $standars = $akreditasi_aktif
                ? \App\Models\Standar::where('akreditasi_id', $akreditasi_aktif->id)
                    ->orderBy('no_urut')
                    ->get()
                : collect(); // Jika tidak ada akreditasi aktif, kembalikan koleksi kosong
        @endphp

        <div class="sidebar-brand">
            <a>{{ $prodi ? $prodi->nama_prodi : 'Prodi Tidak Ditemukan' }}</a> <!-- Menampilkan nama prodi -->
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="home">{{ $prodi ? substr($prodi->nama_prodi, 0, 3) : 'Prodi' }}</a> <!-- Singkatan prodi -->
        </div>
        <ul class="sidebar-menu">
            <!-- Menu lainnya -->

            <!-- Loop untuk setiap Standar yang terkait dengan akreditasi aktif, urutkan berdasarkan no_urut -->
            @foreach ($standars as $standar)
                <li class="dropdown">
                    <a href="#" class="nav-link has-dropdown"><i class="fas fa-th"></i>
                        <span>{{ $standar->nama_standar }}</span></a>
                    <ul class="dropdown-menu">
                        @php
                            // Ambil substandar terkait standar yang dipilih dan urutkan berdasarkan no_urut
                            $substandars = $standar->substandars()->orderBy('no_urut')->get();
                        @endphp
                        @foreach ($substandars as $substandar)
                            <li><a class="nav-link"
                                    href="{{ route('substandar.index', ['standar_id' => $standar->id]) }}">{{ $substandar->nama_substandar }}</a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endforeach
        </ul>
    </aside>
</div>
