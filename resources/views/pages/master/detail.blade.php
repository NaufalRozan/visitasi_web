@extends('layouts.app-master')

@section('title', 'Detail Dashboard')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Detail</h1>
            </div>

            <!-- Dropdown Pemilihan Fakultas, Prodi, dan Standar -->
            <div class="section-body">
                <form method="GET" action="{{ route('detail.index') }}">
                    <div class="form-group d-flex justify-content-between">
                        <!-- Kolom Kiri -->
                        <div class="w-50 pr-2">
                            <label for="fakultas">Fakultas</label>
                            <select name="fakultas_id" id="fakultas" class="form-control" disabled>
                                <option value="{{ $fakultas->id }}">{{ $fakultas->nama_fakultas }}</option>
                            </select>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="w-50 pl-2">
                            <label for="prodi">Program Studi</label>
                            <select name="prodi_id" id="prodi" class="form-control" disabled>
                                <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group d-flex justify-content-between">
                        <!-- Kolom Kiri -->
                        <div class="w-50 pr-2">
                            <label for="standar">Standar</label>
                            <select name="standar_id" id="standar" class="form-control" onchange="this.form.submit()">
                                <option value="">Pilih Standar</option>
                                @foreach ($standars as $standar)
                                    <option value="{{ $standar->id }}"
                                        {{ request('standar_id') == $standar->id ? 'selected' : '' }}>
                                        {{ $standar->nama_standar }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="w-50 pr-2">
                            <label for="substandar">SubStandar</label>
                            <select name="substandar_id" id="substandar" class="form-control" onchange="this.form.submit()">
                                <option value="">Pilih SubStandar</option>
                                @foreach ($substandars as $substandar)
                                    <option value="{{ $substandar->id }}"
                                        {{ request('substandar_id') == $substandar->id ? 'selected' : '' }}>
                                        {{ $substandar->nama_substandar }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tombol Tambah Data -->
            <div class="section-body mt-4">
                @if (request('substandar_id'))
                    <button class="btn btn-success mb-3" onclick="openModal()">Tambah Data</button>
                @endif

                <!-- Tabel Substandar -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Detail</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="detailTableBody">
                        @if ($details->isEmpty())
                            <tr>
                                <td colspan="3" class="text-center">Tidak ada data detail yang ditemukan.</td>
                            </tr>
                        @else
                            @foreach ($details as $detail)
                                <tr>
                                    <td>{{ $detail->no_urut }}</td>
                                    <td>{{ $detail->nama_detail }}</td>
                                    <td>
                                        <button class="btn btn-warning btn-sm"
                                            onclick="openModal('edit', {{ $detail->id }}, '{{ $detail->nama_detail }}', {{ $detail->no_urut }})">Edit</button>
                                        <button class="btn btn-danger btn-sm"
                                            onclick="confirmDelete({{ $detail->id }})">Delete</button>
                                        <form id="delete-form-{{ $detail->id }}"
                                            action="{{ route('detail.destroy', $detail->id) }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                            @method('DELETE')
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

    <!-- Modal -->
    <div id="tambahDataModal" class="modal"
        style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0, 0, 0, 0.5);">
        <div class="modal-content"
            style="background-color: #fefefe; margin: 10% auto; padding: 20px; border: 1px solid #888; width: 50%; max-width: 500px; border-radius: 8px;">
            <span class="close" onclick="closeModal()"
                style="color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
            <h2 id="modalTitle" class="text-center mb-4">Tambah Detail</h2>
            <form id="detailForm" action="{{ route('detail.store') }}" method="POST">
                @csrf
                <input type="hidden" id="methodField" name="_method" value="POST">
                <input type="hidden" name="substandar_id" value="{{ request('substandar_id') }}">
                <!-- Pastikan substandar_id tersimpan -->

                <!-- No Urut (Editable) -->
                <div class="mb-4">
                    <label for="no_urut" class="block text-sm font-medium text-gray-700">No Urut</label>
                    <input type="text" name="no_urut" id="no_urut" value="{{ $nextNumber }}" class="form-control"
                        required>
                </div>

                <!-- Nama Detail -->
                <div class="mb-4">
                    <label for="nama_detail" class="block text-sm font-medium text-gray-700">Nama Detail</label>
                    <input type="text" name="nama_detail" id="nama_detail" required class="form-control">
                </div>

                <div>
                    <button type="submit" id="submitBtn" class="btn btn-success">
                        Tambah Detail
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Pastikan substandar_id diset ulang saat standar_id berubah
        document.getElementById('standar').addEventListener('change', function() {
            document.getElementById('substandar').value = ''; // Reset dropdown substandar
            document.getElementById('substandar').dispatchEvent(new Event('change')); // Submit form
        });

        function openModal(mode = 'create', id = null, nama_detail = '', no_urut = '') {
            document.getElementById('tambahDataModal').style.display = 'block';

            if (mode === 'edit') {
                document.getElementById('modalTitle').innerText = 'Edit Detail';
                document.getElementById('submitBtn').innerText = 'Update Detail';

                // Update form action and method for editing
                document.getElementById('detailForm').action = '/detail/' + id;
                document.getElementById('methodField').value = 'PUT'; // Ubah method menjadi PUT untuk update

                // Set the current data in the form fields
                document.getElementById('nama_detail').value = nama_detail;
                document.getElementById('no_urut').value = no_urut; // Set the correct no_urut value
            } else {
                document.getElementById('modalTitle').innerText = 'Tambah Detail';
                document.getElementById('submitBtn').innerText = 'Tambah Detail';

                // Reset form action and method for creating
                document.getElementById('detailForm').action = '{{ route('detail.store') }}';
                document.getElementById('methodField').value = 'POST'; // Set method to POST

                // Clear the form fields
                document.getElementById('nama_detail').value = '';
                document.getElementById('no_urut').value = '{{ $nextNumber }}'; // Set the next number for new entry
            }
        }

        function closeModal() {
            document.getElementById('tambahDataModal').style.display = 'none';
        }
    </script>
@endpush
