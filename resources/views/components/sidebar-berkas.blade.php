<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        @php
            $sub_unit_id = session('sub_unit_id');
            $sub_units = \App\Models\Prodi::find($sub_unit_id);

            // Ambil akreditasi yang aktif
            $akreditasi_aktif = \App\Models\Akreditasi::where('sub_unit_id', $sub_unit_id)->where('status', 'aktif')->first();

            // Ambil standar berdasarkan akreditasi yang aktif
            $standars = $akreditasi_aktif
                ? \App\Models\Standar::where('akreditasi_id', $akreditasi_aktif->id)
                    ->orderBy('no_urut')
                    ->get()
                : collect(); // Jika tidak ada akreditasi aktif, kembalikan koleksi kosong
        @endphp

        <div class="sidebar-brand">
            <a>Arsip Akreditasi</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="home">AA</a> <!-- Singkatan sub_unit -->
        </div>
        <ul class="sidebar-menu">
            <!-- Loop untuk setiap Standar dan Substandar -->
            @foreach ($standars as $standar)
                <li class="dropdown">
                    <a href="#" class="nav-link has-dropdown"><i class="fas fa-th"></i>
                        <span>{{ $standar->nama_standar }}</span></a>
                    <ul class="dropdown-menu">
                        @php
                            // Mengambil substandar yang terkait dengan standar
                            $substandars = $standar->substandars()->orderBy('no_urut')->get();
                        @endphp
                        @foreach ($substandars as $substandar)
                            <li><a class="nav-link"
                                    href="{{ route('detail.show', ['substandar_id' => $substandar->id]) }}">{{ $substandar->nama_substandar }}</a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endforeach
        </ul>

    </aside>
</div>
