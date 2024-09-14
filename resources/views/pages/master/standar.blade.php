@extends('layouts.app-master')

@section('title', 'Bagian Dashboard')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Bagian</h1>
            </div>

            <!-- Dropdown Pemilihan Fakultas, Prodi, dan Akreditasi -->
            <div class="section-body">
                <form method="GET" action="{{ route('standar.index') }}">
                    <div class="form-group d-flex justify-content-between">
                        <!-- Kolom Kiri -->
                        <div class="w-50 pr-2">
                            <label for="fakultas">Fakultas</label>
                            <select name="fakultas_id" id="fakultas" class="form-control" disabled>
                                @if ($fakultas)
                                    <option value="{{ $fakultas->id }}">{{ $fakultas->nama_fakultas }}</option>
                                @else
                                    <option value="">Fakultas tidak ditemukan</option>
                                @endif
                            </select>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="w-50 pl-2">
                            <label for="prodi">Program Studi</label>
                            <select name="prodi_id" id="prodi" class="form-control" disabled>
                                @if ($prodi)
                                    <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }}</option>
                                @else
                                    <option value="">Prodi tidak ditemukan</option>
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="akreditasi">Akreditasi</label>
                        <select name="akreditasi_id" id="akreditasi" class="form-control" onchange="this.form.submit()">
                            <option value="">Pilih Akreditasi</option>
                            @foreach ($akreditasis as $akreditasi)
                                <option value="{{ $akreditasi->id }}"
                                    {{ $selected_akreditasi_id == $akreditasi->id ? 'selected' : '' }}>
                                    {{ $akreditasi->nama_akreditasi }}
                                </option>
                            @endforeach
                        </select>

                    </div>
                </form>
            </div>

            <!-- Tombol Tambah Data -->
            <div class="section-body mt-4">
                @if (request('akreditasi_id') || $selected_akreditasi_id)
                    <button class="btn btn-success mb-3" onclick="openModal()">Tambah Data</button>
                @endif


                <!-- Tabel Standar -->
                <div class="card card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 5%">Drag</th>
                                    <th style="width: 5%">No</th>
                                    <th style="width: 70%">Nama Bagian</th>
                                    <th style="width: 20%">Action</th>
                                </tr>
                            </thead>
                            <tbody id="standarTableBody">
                                @if ($standars->isEmpty())
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada data standar yang ditemukan.</td>
                                    </tr>
                                @else
                                    @foreach ($standars as $standar)
                                        <tr data-id="{{ $standar->id }}">
                                            <td><i class="fas fa-bars handle"></i></td>
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
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
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
            <h2 id="modalTitle" class="text-center mb-4">Tambah Standar</h2>
            <form id="standarForm" action="{{ route('standar.store') }}" method="POST">
                @csrf
                <input type="hidden" id="methodField" name="_method" value="POST">

                <!-- Hidden Input untuk menyimpan akreditasi aktif -->
                <input type="hidden" name="akreditasi_id" value="{{ $selected_akreditasi_id }}">

                <!-- No Urut (Editable) -->
                <div class="mb-4">
                    <label for="no_urut" class="block text-sm font-medium text-gray-700">No Urut</label>
                    <input type="text" name="no_urut" id="no_urut" value="{{ $nextNumber }}" class="form-control"
                        required>
                </div>

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
    <!-- Tambahkan SortableJS -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>

    <script>
        // Inisialisasi SortableJS pada tabel
        var el = document.getElementById('standarTableBody');
        var sortable = Sortable.create(el, {
            handle: '.handle',
            animation: 150,
            onEnd: function(evt) {
                var order = [];
                $('#standarTableBody tr').each(function(index, element) {
                    order.push({
                        id: $(element).data('id'),
                        no_urut: index + 1
                    });

                    // Update no_urut langsung pada tabel setelah drag
                    $(element).find('td:eq(1)').text(index + 1);
                });

                // Kirim urutan baru ke server
                $.ajax({
                    url: "{{ route('standar.updateOrder') }}",
                    method: 'POST',
                    data: {
                        order: order,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log('Order updated successfully');
                    },
                    error: function(response) {
                        console.error('Error updating order');
                    }
                });
            }
        });

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
    </script>
@endpush
