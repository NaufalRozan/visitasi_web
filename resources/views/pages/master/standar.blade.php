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
                <form method="GET" action="{{ route('standar.index') }}" id="filterForm">
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
                                    disabled>
                            </div>
                            <div class="w-50 pl-2">
                                <label for="sub_units">Program Studi</label>
                                <input type="text" class="form-control" value="{{ $sub_unit->nama_sub_unit }}" disabled>
                            </div>
                        @endif
                    </div>

                    <!-- Tambahkan Dropdown untuk jumlah row per halaman -->
                    <div class="form-group row">
                        <div class="col-md-2">
                            <label for="perPage">Row Page:</label>
                            <select name="perPage" id="perPage" class="form-control"
                                onchange="document.getElementById('filterForm').submit();">
                                <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="search">Cari Nama Bagian</label>
                            <input type="text" id="search" name="search" class="form-control"
                                placeholder="Cari Nama Bagian">
                        </div>
                    </div>
                </form>
            </div>


            <!-- Tabel Standar -->
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
                                            <td><i class="fas fa-bars handle sort-handler"></i></td>
                                            <td>{{ $standar->no_urut }}</td>
                                            <td>{{ $standar->nama_standar }}</td>
                                            <td>
                                                <button class="btn btn-warning btn-sm"
                                                    onclick="openModal('edit', {{ $standar->id }}, '{{ $standar->nama_standar }}', {{ $standar->no_urut }})">Edit</button>
                                                <button class="btn btn-danger btn-sm"
                                                    onclick="confirmDelete({{ $standar->id }})">Delete</button>
                                                <form id="delete-form-{{ $standar->id }}"
                                                    action="{{ route('standar.destroy', ['standar' => $standar->id, 'perPage' => request('perPage', 5)]) }}"
                                                    method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        <!-- Tambahkan Pagination -->
                        <div class="mt-3">
                            {{ $standars instanceof \Illuminate\Pagination\LengthAwarePaginator ? $standars->appends(['unit_id' => request('unit_id'), 'sub_unit_id' => request('sub_unit_id'), 'perPage' => $perPage])->links() : '' }}
                        </div>
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

                <!-- Hidden Input untuk menyimpan akreditasi dari sub_unit yang dipilih -->
                <input type="hidden" name="akreditasi_id" value="{{ $selected_akreditasi_id }}">

                <!-- Hidden Input untuk perPage -->
                <input type="hidden" name="perPage" value="{{ $perPage }}">

                <!-- No Urut (Editable) -->
                <div class="mb-4">
                    <label for="no_urut" class="block text-sm font-medium text-gray-700">No Urut</label>
                    <input type="text" name="no_urut" id="no_urut" value="{{ $nextNumber }}"
                        class="form-control" required>
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

    <!-- Script Search -->
    <script>
        // Script untuk melakukan pencarian data standar
        $(document).ready(function() {
            $('#search').on('input', function() {
                var searchQuery = $(this).val();

                $.ajax({
                    url: '{{ route('standar.index') }}',
                    type: 'GET',
                    data: {
                        search: searchQuery,
                        unit_id: $('#units').val(),
                        sub_unit_id: $('#sub_units').val(),
                        perPage: $('#perPage').val(),
                    },
                    success: function(response) {
                        var newStandarTable = $(response).find('#standarTableBody').html();
                        $('#standarTableBody').html(newStandarTable);
                    },
                    error: function(error) {
                        console.log('Error:', error);
                    }
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
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

                    // Kirim urutan baru ke server menggunakan AJAX
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
        });

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

        function openModal(mode = 'create', id = null, nama_standar = '', no_urut = '') {
            document.getElementById('tambahDataModal').style.display = 'block';
            if (mode === 'edit') {
                document.getElementById('modalTitle').innerText = 'Edit Standar';
                document.getElementById('submitBtn').innerText = 'Update Standar';
                document.getElementById('standarForm').action = '/standar/' + id;
                document.getElementById('methodField').value = 'PUT';
                document.getElementById('nama_standar').value = nama_standar;
                document.getElementById('no_urut').value = no_urut;
            } else {
                document.getElementById('modalTitle').innerText = 'Tambah Standar';
                document.getElementById('submitBtn').innerText = 'Tambah Standar';
                document.getElementById('standarForm').action = '{{ route('standar.store') }}';
                document.getElementById('methodField').value = 'POST';
                document.getElementById('nama_standar').value = '';
                document.getElementById('no_urut').value = '{{ $nextNumber }}';
            }
        }

        function closeModal() {
            document.getElementById('tambahDataModal').style.display = 'none';
        }
    </script>
@endpush
