@extends('layouts.app-master')

@section('title', 'Akreditasi Dashboard')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Akreditasi</h1>
            </div>

            <!-- Dropdown Pemilihan Fakultas dan Prodi -->
            <div class="section-body">
                <form method="GET" action="{{ route('akreditasi.index') }}" id="filterForm">
                    <div class="form-group d-flex justify-content-between">
                        @if ($user->role !== 'Prodi')
                            <!-- Kolom Kiri: Fakultas -->
                            <div class="w-50 pr-2">
                                <label for="units">Fakultas</label>
                                <select name="unit_id" id="units" class="form-control" required>
                                    <option value="" disabled selected>Pilih Fakultas</option>
                                    @foreach ($unit as $f)
                                        <option value="{{ $f->id }}"
                                            {{ request('unit_id') == $f->id ? 'selected' : '' }}>
                                            {{ $f->nama_unit }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Kolom Kanan: Prodi -->
                            <div class="w-50 pl-2" id="sub_unitWrapper">
                                <label for="sub_units">Program Studi</label>
                                <select name="sub_unit_id" id="sub_units" class="form-control" disabled required>
                                    <option value="">Pilih Fakultas Terlebih Dahulu</option>
                                    @foreach ($sub_units as $p)
                                        <option value="{{ $p->id }}" data-units="{{ $p->unit_id }}"
                                            {{ request('sub_unit_id') == $p->id ? 'selected' : '' }}>
                                            {{ $p->nama_sub_unit }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <!-- Untuk role Prodi, tampilkan data dari session -->
                            <input type="hidden" name="unit_id" value="{{ $sub_unit->units->id }}">
                            <input type="hidden" name="sub_unit_id" value="{{ $sub_unit->id }}">
                            <div class="w-50 pr-2">
                                <label for="units">Fakultas</label>
                                <input type="text" class="form-control" value="{{ $sub_unit->units->nama_unit }}"
                                    readonly>
                            </div>
                            <div class="w-50 pl-2">
                                <label for="sub_units">Program Studi</label>
                                <input type="text" class="form-control" value="{{ $sub_unit->nama_sub_unit }}" readonly>
                            </div>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Tabel Akreditasi -->
            <div class="section-body mt-4">
                <!-- Tombol Tambah Data -->
                @if ($user->role === 'Prodi' || $user->sub_units->contains('id', request('sub_unit_id')))
                    <button class="btn btn-success mb-3" onclick="openModal('create')">Tambah Data</button>
                @endif

                <div class="card card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 5%">No</th>
                                    <th style="width: 60%">Nama Akreditasi</th>
                                    <th style="width: 15%">Status</th>
                                    <th style="width: 20%">Action</th>
                                </tr>
                            </thead>
                            <tbody id="akreditasiTableBody">
                                @if ($akreditasis->isEmpty())
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada data akreditasi yang ditemukan.
                                        </td>
                                    </tr>
                                @else
                                    @foreach ($akreditasis as $akreditasi)
                                        <tr data-id="{{ $akreditasi->id }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $akreditasi->nama_akreditasi }}</td>
                                            <td>
                                                <span
                                                    class="badge {{ $akreditasi->status == 'aktif' ? 'badge-success' : 'badge-secondary' }}">
                                                    {{ ucfirst($akreditasi->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <button class="btn btn-warning btn-sm"
                                                    onclick="openModal('edit', {{ $akreditasi->id }}, '{{ $akreditasi->nama_akreditasi }}')">Edit</button>
                                                <button class="btn btn-danger btn-sm"
                                                    onclick="confirmDelete({{ $akreditasi->id }})">Delete</button>
                                                @if ($akreditasi->status !== 'aktif')
                                                    <button class="btn btn-success btn-sm"
                                                        onclick="confirmActivate({{ $akreditasi->id }})">Aktifkan</button>
                                                @endif
                                                <form id="delete-form-{{ $akreditasi->id }}"
                                                    action="{{ route('akreditasi.destroy', $akreditasi->id) }}"
                                                    method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                                <form id="activate-form-{{ $akreditasi->id }}"
                                                    action="{{ route('akreditasi.activate', $akreditasi->id) }}"
                                                    method="POST" style="display: none;">
                                                    @csrf
                                                    @method('PUT')
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <x-sweet-alert-delete />
    <x-sweet-alert-akreditasi />

    <!-- Modal -->
    <div id="tambahDataModal" class="modal"
        style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0, 0, 0, 0.5);">
        <div class="modal-content"
            style="background-color: #fefefe; margin: 10% auto; padding: 20px; border: 1px solid #888; width: 50%; max-width: 500px; border-radius: 8px;">
            <span class="close" onclick="closeModal()"
                style="color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
            <h2 id="modalTitle" class="text-center mb-4">Tambah Akreditasi</h2>
            <form id="akreditasiForm" action="{{ route('akreditasi.store') }}" method="POST">
                @csrf
                @if (isset($sub_unit) && $sub_unit)
                    <input type="hidden" name="sub_unit_id" value="{{ $sub_unit->id }}">
                @else
                    <input type="hidden" name="sub_unit_id" value="">
                @endif

                <input type="hidden" id="methodField" name="_method" value="POST">

                <!-- Nama Akreditasi -->
                <div class="mb-4">
                    <label for="nama_akreditasi" class="block text-sm font-medium text-gray-700">Nama Akreditasi</label>
                    <input type="text" name="nama_akreditasi" id="nama_akreditasi" required class="form-control">
                </div>

                <div>
                    <button type="submit" id="submitBtn" class="btn btn-success">
                        Tambah Akreditasi
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Tambahkan kode lain terkait dropdown prodi dan fakultas
        document.addEventListener('DOMContentLoaded', function() {
            var fakultasDropdown = document.getElementById('units');
            var prodiDropdown = document.getElementById('sub_units');
            var allProdiOptions = Array.from(prodiDropdown.options); // Simpan semua opsi program studi

            function toggleProdiDropdown() {
                if (!fakultasDropdown.value) {
                    prodiDropdown.disabled = true;
                    prodiDropdown.innerHTML = '<option value="">Pilih Fakultas Terlebih Dahulu</option>';
                } else {
                    prodiDropdown.disabled = false;
                    var selectedFakultas = fakultasDropdown.value;
                    prodiDropdown.innerHTML = '<option value="" disabled selected>Pilih Program Studi</option>';

                    allProdiOptions.forEach(function(option) {
                        var fakultasId = option.getAttribute('data-units');
                        if (fakultasId == selectedFakultas) {
                            prodiDropdown.appendChild(option.cloneNode(true));
                        }
                    });
                }
            }

            toggleProdiDropdown();

            fakultasDropdown.addEventListener('change', function() {
                toggleProdiDropdown();
            });

            prodiDropdown.addEventListener('change', function() {
                prodiDropdown.disabled = false;
            });
        });

        document.getElementById('sub_units').addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });

        function openModal(mode = 'create', id = null, nama_akreditasi = '') {
            document.getElementById('tambahDataModal').style.display = 'block';
            if (mode === 'edit') {
                document.getElementById('modalTitle').innerText = 'Edit Akreditasi';
                document.getElementById('submitBtn').innerText = 'Update Akreditasi';
                document.getElementById('akreditasiForm').action = '/akreditasi/' + id;
                document.getElementById('methodField').value = 'PUT';
                document.getElementById('nama_akreditasi').value = nama_akreditasi;
            } else {
                document.getElementById('modalTitle').innerText = 'Tambah Akreditasi';
                document.getElementById('submitBtn').innerText = 'Tambah Akreditasi';
                document.getElementById('akreditasiForm').action = '{{ route('akreditasi.store') }}';
                document.getElementById('methodField').value = 'POST';
                document.getElementById('nama_akreditasi').value = '';
            }
        }

        function closeModal() {
            document.getElementById('tambahDataModal').style.display = 'none';
        }
    </script>
@endpush
