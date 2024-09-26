@extends('layouts.app-master')

@section('title', 'Detail Dashboard')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Detail</h1>
            </div>

            <!-- Dropdown Pemilihan Fakultas, Prodi, Akreditasi, Standar, dan Substandar -->
            <div class="section-body">
                <form method="GET" action="{{ route('detail.index') }}" id="filterForm">
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

                    <!-- Dropdown untuk Standar -->
                    <div class="form-group d-flex justify-content-between">
                        <div class="w-50 pr-2">
                            <label for="standar">Bagian</label>
                            <select name="standar_id" id="standar" class="form-control" onchange="this.form.submit()">
                                <option value="" disabled selected>Pilih Bagian</option>
                                @foreach ($standars as $standar)
                                    <option value="{{ $standar->id }}"
                                        {{ request('standar_id') == $standar->id ? 'selected' : '' }}>
                                        {{ $standar->nama_standar }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Kolom Kanan: Substandar -->
                        <div class="w-50 pl-2">
                            <label for="substandar">Sub-Bagian</label>
                            <select name="substandar_id" id="substandar" class="form-control" onchange="this.form.submit()">
                                <option value="" disabled selected>Pilih Sub-Bagian</option>
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
                @if (request('substandar_id') && ($user->role === 'Prodi' || $user->sub_units->contains('id', request('sub_unit_id'))))
                    <button class="btn btn-success mb-3" onclick="openModal()">Tambah Data</button>
                @endif

                <!-- Tabel Detail -->
                <div class="card card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 5%">Drag</th>
                                    <th style="width: 5%">No</th>
                                    <th style="width: 70%">Nama Detail</th>
                                    <th style="width: 20%">Action</th>
                                </tr>
                            </thead>
                            <tbody id="detailTableBody">
                                @if ($details->isEmpty())
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada data detail yang ditemukan.</td>
                                    </tr>
                                @else
                                    @foreach ($details as $detail)
                                        <tr data-id="{{ $detail->id }}">
                                            <td><i class="fas fa-bars handle sort-handler"></i></td>
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
            <h2 id="modalTitle" class="text-center mb-4">Tambah Detail</h2>
            <form id="detailForm" action="{{ route('detail.store') }}" method="POST">
                @csrf
                <input type="hidden" id="methodField" name="_method" value="POST">
                <input type="hidden" name="standar_id" value="{{ request('standar_id') }}">
                <input type="hidden" name="substandar_id" value="{{ request('substandar_id') }}">
                <input type="hidden" name="akreditasi_id" value="{{ request('akreditasi_id') }}">

                <!-- No Urut -->
                <div class="mb-4">
                    <label for="no_urut" class="block text-sm font-medium text-gray-700">No Urut</label>
                    <input type="text" name="no_urut" id="no_urut" value="{{ $nextNumber }}"
                        class="form-control" required>
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
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>

    <script>
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

        function openModal(mode = 'create', id = null, nama_detail = '', no_urut = '') {
            document.getElementById('tambahDataModal').style.display = 'block';
            if (mode === 'edit') {
                document.getElementById('modalTitle').innerText = 'Edit Detail';
                document.getElementById('submitBtn').innerText = 'Update Detail';
                document.getElementById('detailForm').action = '/detail/' + id;
                document.getElementById('methodField').value = 'PUT';
                document.getElementById('nama_detail').value = nama_detail;
                document.getElementById('no_urut').value = no_urut;
            } else {
                document.getElementById('modalTitle').innerText = 'Tambah Detail';
                document.getElementById('submitBtn').innerText = 'Tambah Detail';
                document.getElementById('detailForm').action = '{{ route('detail.store') }}';
                document.getElementById('methodField').value = 'POST';
                document.getElementById('nama_detail').value = '';
                document.getElementById('no_urut').value = '{{ $nextNumber }}';
            }
        }

        function closeModal() {
            document.getElementById('tambahDataModal').style.display = 'none';
        }
    </script>
@endpush
