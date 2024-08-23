@extends('layouts.app-master')

@section('title', 'Substandar Dashboard')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Substandar</h1>
            </div>

            <!-- Dropdown Pemilihan Fakultas, Prodi, dan Standar -->
            <div class="section-body">
                <form method="GET" action="{{ route('substandar.index') }}">
                    <div class="form-group">
                        <label for="fakultas">Fakultas</label>
                        <select name="fakultas_id" id="fakultas" class="form-control" disabled>
                            <option value="{{ $fakultas->id }}">{{ $fakultas->nama_fakultas }}</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="prodi">Program Studi</label>
                        <select name="prodi_id" id="prodi" class="form-control" disabled>
                            <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }}</option>
                        </select>
                    </div>

                    <div class="form-group">
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
                </form>
            </div>

            <!-- Tombol Tambah Data -->
            <div class="section-body mt-4">
                @if (request('standar_id'))
                    <button class="btn btn-success mb-3" onclick="openModal()">Tambah Data</button>
                @endif

                <!-- Tabel Substandar -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Substandar</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="substandarTableBody">
                        @if ($substandars->isEmpty())
                            <tr>
                                <td colspan="3" class="text-center">Tidak ada data substandar yang ditemukan.</td>
                            </tr>
                        @else
                            @foreach ($substandars as $substandar)
                                <tr>
                                    <td>{{ $substandar->no_urut }}</td>
                                    <td>{{ $substandar->nama_substandar }}</td>
                                    <td>
                                        <button class="btn btn-warning btn-sm"
                                            onclick="openModal('edit', {{ $substandar->id }}, '{{ $substandar->nama_substandar }}', {{ $substandar->no_urut }})">Edit</button>
                                        <button class="btn btn-danger btn-sm"
                                            onclick="confirmDelete({{ $substandar->id }})">Delete</button>
                                        <form id="delete-form-{{ $substandar->id }}"
                                            action="{{ route('substandar.destroy', $substandar->id) }}" method="POST"
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
            <h2 id="modalTitle" class="text-center mb-4">Tambah Substandar</h2>
            <form id="substandarForm" action="{{ route('substandar.store') }}" method="POST">
                @csrf
                <input type="hidden" id="methodField" name="_method" value="POST">
                <input type="hidden" name="standar_id" value="{{ request('standar_id') }}">
                <!-- Pastikan standar_id tersimpan -->

                <!-- No Urut (Editable) -->
                <div class="mb-4">
                    <label for="no_urut" class="block text-sm font-medium text-gray-700">No Urut</label>
                    <input type="text" name="no_urut" id="no_urut" value="{{ $nextNumber }}" class="form-control"
                        required>
                </div>

                <!-- Nama Substandar -->
                <div class="mb-4">
                    <label for="nama_substandar" class="block text-sm font-medium text-gray-700">Nama Substandar</label>
                    <input type="text" name="nama_substandar" id="nama_substandar" required class="form-control">
                </div>

                <div>
                    <button type="submit" id="submitBtn" class="btn btn-success">
                        Tambah Substandar
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Script Modal -->
    <script>
        function openModal(mode = 'create', id = null, nama_substandar = '', no_urut = '') {
            document.getElementById('tambahDataModal').style.display = 'block';

            if (mode === 'edit') {
                document.getElementById('modalTitle').innerText = 'Edit Substandar';
                document.getElementById('submitBtn').innerText = 'Update Substandar';

                // Update form action and method for editing
                document.getElementById('substandarForm').action = '/substandar/' + id;
                document.getElementById('methodField').value = 'PUT'; // Ubah method menjadi PUT untuk update

                // Set the current data in the form fields
                document.getElementById('nama_substandar').value = nama_substandar;
                document.getElementById('no_urut').value = no_urut; // Set the correct no_urut value
            } else {
                document.getElementById('modalTitle').innerText = 'Tambah Substandar';
                document.getElementById('submitBtn').innerText = 'Tambah Substandar';

                // Reset form action and method for creating
                document.getElementById('substandarForm').action = '{{ route('substandar.store') }}';
                document.getElementById('methodField').value = 'POST'; // Set method to POST

                // Clear the form fields
                document.getElementById('nama_substandar').value = '';
                document.getElementById('no_urut').value = '{{ $nextNumber }}'; // Set the next number for new entry
            }
        }

        function closeModal() {
            document.getElementById('tambahDataModal').style.display = 'none';
        }
    </script>
@endpush
