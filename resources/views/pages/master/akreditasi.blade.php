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
                <form method="GET" action="{{ route('akreditasi.index') }}">
                    <div class="form-group d-flex justify-content-between">
                        <div class="w-50 pr-2">
                            <label for="fakultas">Fakultas</label>
                            <select name="fakultas_id" id="fakultas" class="form-control" disabled>
                                <option value="{{ $fakultas->id }}">{{ $fakultas->nama_fakultas }}</option>
                            </select>
                        </div>

                        <div class="w-50 pl-2">
                            <label for="prodi">Program Studi</label>
                            <select name="prodi_id" id="prodi" class="form-control" disabled>
                                <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }}</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tombol Tambah Data -->
            <div class="section-body mt-4">
                <button class="btn btn-success mb-3" onclick="openModal()">Tambah Akreditasi</button>

                <!-- Tabel Akreditasi -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Akreditasi</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="akreditasiTableBody">
                        @if ($akreditasis->isEmpty())
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada data akreditasi yang ditemukan.</td>
                            </tr>
                        @else
                            @foreach ($akreditasis as $akreditasi)
                                <tr>
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
                                            <button class="btn btn-primary btn-sm"
                                                onclick="confirmActivate({{ $akreditasi->id }})">Aktifkan</button>
                                        @endif
                                        <form id="delete-form-{{ $akreditasi->id }}"
                                            action="{{ route('akreditasi.destroy', $akreditasi->id) }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <form id="activate-form-{{ $akreditasi->id }}"
                                            action="{{ route('akreditasi.activate', $akreditasi->id) }}" method="POST"
                                            style="display: none;">
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
                <input type="hidden" name="prodi_id" value="{{ $prodi->id }}">
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
    <!-- Script Modal -->
    <script>
        function openModal(mode = 'create', id = null, nama_akreditasi = '') {
            document.getElementById('tambahDataModal').style.display = 'block';

            if (mode === 'edit') {
                document.getElementById('modalTitle').innerText = 'Edit Akreditasi';
                document.getElementById('submitBtn').innerText = 'Update Akreditasi';

                // Update form action and method for editing
                document.getElementById('akreditasiForm').action = '/akreditasi/' + id;
                document.getElementById('methodField').value = 'PUT'; // Ubah method menjadi PUT untuk update

                // Set the current data in the form fields
                document.getElementById('nama_akreditasi').value = nama_akreditasi;
            } else {
                document.getElementById('modalTitle').innerText = 'Tambah Akreditasi';
                document.getElementById('submitBtn').innerText = 'Tambah Akreditasi';

                // Reset form action and method for creating
                document.getElementById('akreditasiForm').action = '{{ route('akreditasi.store') }}';
                document.getElementById('methodField').value = 'POST'; // Set method to POST

                // Clear the form fields
                document.getElementById('nama_akreditasi').value = '';
            }
        }

        function closeModal() {
            document.getElementById('tambahDataModal').style.display = 'none';
        }
    </script>
@endpush
