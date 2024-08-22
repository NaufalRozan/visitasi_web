@extends('layouts.app-master')

@section('title', 'General Dashboard')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Standar</h1>
            </div>

            <!-- Dropdown Pemilihan Fakultas dan Prodi -->
            <div class="section-body">
                <form>
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
                </form>
            </div>

            <!-- Tombol Tambah Data -->
            <div class="section-body mt-4">
                <button class="btn btn-success mb-3" onclick="openModal()">Tambah Data</button>

                <!-- Tabel Standar -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Standar</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="standarTableBody">
                        @foreach ($standars as $standar)
                            <tr>
                                <td>{{ $standar->no_urut }}</td>
                                <td>{{ $standar->nama_standar }}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm"
                                        onclick="openModal('edit', {{ $standar->id }}, '{{ $standar->nama_standar }}', {{ $standar->no_urut }})">Edit</button>
                                    <button class="btn btn-danger btn-sm"
                                        onclick="confirmDelete({{ $standar->id }})">Delete</button>
                                    <form id="delete-form-{{ $standar->id }}"
                                        action="{{ route('standar.destroy', $standar->id) }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <!-- Modal -->
    <div id="tambahDataModal" class="modal"
        style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0, 0, 0, 0.5);">
        <div class="modal-content"
            style="background-color: #fefefe; margin: 10% auto; padding: 20px; border: 1px solid #888; width: 50%; max-width: 500px; border-radius: 8px;">
            <span class="close" onclick="closeModal()"
                style="color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
            <h2 id="modalTitle" class="text-center mb-4">Tambah Standar</h2>
            <form id="standarForm" action="{{ route('standar.store') }}" method="POST">
                @csrf
                <input type="hidden" id="methodField" name="_method" value="POST">

                <!-- No Urut (Editable) -->
                <div class="mb-4">
                    <label for="no_urut" class="block text-sm font-medium text-gray-700">No Urut</label>
                    <input type="text" name="no_urut" id="no_urut" value="{{ $nextNumber }}" class="form-control"
                        required>
                </div>

                <!-- Hidden Prodi ID -->
                <input type="hidden" name="prodi_id" value="{{ $prodi->id }}">

                <!-- Hidden Akreditasi ID -->
                <input type="hidden" name="akreditasi_id" id="akreditasi_id" value="{{ $prodi->akreditasi->id }}">

                <!-- Nama Standar -->
                <div class="mb-4">
                    <label for="nama_standar" class="block text-sm font-medium text-gray-700">Nama Standar</label>
                    <input type="text" name="nama_standar" id="nama_standar" required class="form-control">
                </div>

                <div>
                    <button type="submit" id="submitBtn" class="btn btn-success">
                        Tambah Standar
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/simpleweather/jquery.simpleWeather.min.js') }}"></script>
    <script src="{{ asset('library/chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('library/summernote/dist/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('library/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/index-0.js') }}"></script>

    <!-- Script Modal -->
    <script>
        function openModal(mode = 'create', id = null, nama_standar = '', no_urut = '') {
            document.getElementById('tambahDataModal').style.display = 'block';

            if (mode === 'edit') {
                document.getElementById('modalTitle').innerText = 'Edit Standar';
                document.getElementById('submitBtn').innerText = 'Update Standar';

                // Update form action and method for editing
                document.getElementById('standarForm').action = '/standar/' + id;
                document.getElementById('methodField').value = 'PUT'; // Ubah method menjadi PUT untuk update

                // Set the current data in the form fields
                document.getElementById('nama_standar').value = nama_standar;
                document.getElementById('no_urut').value = no_urut; // Set the correct no_urut value
            } else {
                document.getElementById('modalTitle').innerText = 'Tambah Standar';
                document.getElementById('submitBtn').innerText = 'Tambah Standar';

                // Reset form action and method for creating
                document.getElementById('standarForm').action = '{{ route('standar.store') }}';
                document.getElementById('methodField').value = 'POST'; // Set method to POST

                // Clear the form fields
                document.getElementById('nama_standar').value = '';
                document.getElementById('no_urut').value = '{{ $nextNumber }}'; // Set the next number for new entry
            }
        }

        function closeModal() {
            document.getElementById('tambahDataModal').style.display = 'none';


        }

        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda tidak dapat mengembalikan data yang telah dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
@endpush
