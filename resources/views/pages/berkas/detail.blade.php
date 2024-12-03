@extends('layouts.app-berkas')

@section('title', 'Arsip')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between">
                <h1>Arsip: {{ $substandar->nama_substandar }}</h1>
            </div>

            <div class="section-body">

                <!-- Dropdown Filter Tipe -->
                <div class="mb-4">
                    <label for="filterTipe">Filter Tipe:</label>
                    <select name="filterTipe" id="filterTipe" class="form-control" onchange="filterByType()">
                        <option value="All">All</option>
                        <option value="Document">Document</option>
                        <option value="URL">URL</option>
                        <option value="Image">Image</option>
                        <option value="Video">Video</option>
                    </select>
                </div>

                <!-- Tabel Detail dan Dokumen -->
                @foreach ($details as $detail)
                    <div class="card mt-4">
                        <div class="card-header d-flex justify-content-between">
                            <h4>{{ $detail->nama_detail }}</h4>
                            <button class="btn btn-success btn-sm"
                                onclick="openModal('{{ $detail->id }}', '{{ $detail->nextNoUrut }}')">Tambah
                                Dokumen</button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%;">Drag</th>
                                            <th style="width: 5%;">No</th>
                                            <th style="width: 40%;">Deskripsi</th>
                                            <th style="width: 20%;">Lokasi</th>
                                            <th style="width: 10%;">Tipe</th>
                                            <th style="width: 20%;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="detailItemTableBody">
                                        @foreach ($detail->items as $item)
                                            <tr data-id="{{ $item->id }}" data-type="{{ $item->tipe }}">
                                                <td><i class="fas fa-bars handle"></i></td>
                                                <td>{{ $item->no_urut }}</td>
                                                <td>{{ $item->deskripsi }}</td>
                                                <td>
                                                    @if ($item->tipe === 'URL')
                                                        <a href="{{ $item->lokasi }}" target="_blank">Buka URL</a>
                                                    @else
                                                        <a href="{{ route('detail_item.download', $item->id) }}"
                                                            download>Unduh {{ $item->tipe }}</a>
                                                    @endif
                                                </td>
                                                <td>{{ $item->tipe }}</td>
                                                <td>
                                                    <a href="{{ route('detail_item.view', $item->id) }}"
                                                        class="btn btn-success btn-sm" target="_blank">View</a>
                                                    <button class="btn btn-warning btn-sm"
                                                        onclick="openModalEdit('{{ $item->id }}', '{{ $item->no_urut }}', '{{ $item->deskripsi }}', '{{ $item->lokasi }}', '{{ $item->tipe }}')">Edit</button>
                                                    <button class="btn btn-danger btn-sm"
                                                        onclick="confirmDelete('{{ $item->id }}')">Delete</button>
                                                    <form id="delete-form-{{ $item->id }}"
                                                        action="{{ route('detail_item.destroy', $item->id) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>

    <x-sweet-alert-delete />

    <!-- Modal Tambah/Edit Dokumen -->
    <div id="tambahDokumenModal" class="modal"
        style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0, 0, 0, 0.5);">
        <div class="modal-content"
            style="background-color: #fefefe; margin: 10% auto; padding: 20px; border: 1px solid #888; width: 50%; max-width: 500px; border-radius: 8px;">
            <span class="close" onclick="closeModal()"
                style="color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
            <h2 class="text-center mb-4" id="modalTitle">Tambah Dokumen</h2>
            <form id="dokumenForm" action="{{ route('detail_item.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="detail_id" id="detail_id">
                <input type="hidden" id="methodField" name="_method" value="POST">

                <!-- No Urut -->
                <div class="form-group">
                    <label for="no_urut">Nomor Urut</label>
                    <input type="number" name="no_urut" id="no_urut" class="form-control" required>
                </div>

                <!-- Deskripsi -->
                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <input type="text" name="deskripsi" id="deskripsi" class="form-control" required>
                </div>

                <!-- Tipe -->
                <div class="form-group">
                    <label for="tipe">Tipe</label>
                    <select name="tipe" id="tipe" class="form-control" required>
                        <option value="" disabled selected>Pilih Tipe</option> <!-- Opsi default -->
                        <option value="Document">Document</option>
                        <option value="URL">URL</option>
                        <option value="Image">Image</option>
                        <option value="Video">Video</option>
                    </select>
                </div>

                <!-- Lokasi (jika ada URL atau lokasi manual) -->
                <div class="form-group" id="lokasiField" style="display: none;">
                    <label for="lokasi">Lokasi</label>
                    <input type="text" name="lokasi" id="lokasi" class="form-control">
                </div>

                <!-- Upload File -->
                <div class="form-group" id="fileUploadField" style="display: none;">
                    <label for="file_upload">Unggah File</label>
                    <input type="file" name="file_upload" id="file_upload" class="form-control">
                </div>

                <div>
                    <button type="submit" class="btn btn-success" id="submitBtn">Tambah Dokumen</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>

    <script>
        // Inisialisasi SortableJS pada tabel DetailItem
        var el = document.getElementById('detailItemTableBody');
        var sortable = Sortable.create(el, {
            handle: '.handle',
            animation: 150,
            onEnd: function(evt) {
                var order = [];
                $('#detailItemTableBody tr').each(function(index, element) {
                    order.push({
                        id: $(element).data('id'),
                        no_urut: index + 1
                    });

                    // Update no_urut langsung pada tabel setelah drag
                    $(element).find('td:eq(1)').text(index + 1);
                });

                // Kirim urutan baru ke server
                $.ajax({
                    url: "{{ route('detail_item.updateOrder') }}",
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

        // Fungsi untuk membuka modal tambah dokumen
        function openModal(detailId, nextNoUrut) {
            document.getElementById('modalTitle').innerText = 'Tambah Dokumen';
            document.getElementById('submitBtn').innerText = 'Tambah Dokumen';
            document.getElementById('methodField').value = 'POST';
            document.getElementById('dokumenForm').action = "{{ route('detail_item.store') }}";

            document.getElementById('detail_id').value = detailId;
            document.getElementById('no_urut').value = nextNoUrut;
            document.getElementById('deskripsi').value = '';
            document.getElementById('lokasi').value = '';
            document.getElementById('tipe').value = ''; // Reset ke default "Pilih Tipe"
            document.getElementById('file_upload').value = '';

            document.getElementById('fileUploadField').style.display = 'none';
            document.getElementById('lokasiField').style.display = 'none';

            document.getElementById('tambahDokumenModal').style.display = 'block';
        }

        // Buka modal edit dokumen
        function openModalEdit(id, no_urut, deskripsi, lokasi, tipe) {
            document.getElementById('modalTitle').innerText = 'Edit Dokumen';
            document.getElementById('submitBtn').innerText = 'Update Dokumen';
            document.getElementById('methodField').value = 'PUT';
            document.getElementById('dokumenForm').action = '/detail_item/' + id;

            document.getElementById('no_urut').value = no_urut;
            document.getElementById('deskripsi').value = deskripsi;
            document.getElementById('lokasi').value = lokasi;
            document.getElementById('tipe').value = tipe;
            document.getElementById('file_upload').value = '';

            if (tipe === 'Document' || tipe === 'Image' || tipe === 'Video') {
                document.getElementById('fileUploadField').style.display = 'block';
                document.getElementById('lokasiField').style.display = 'none';
            } else if (tipe === 'URL') {
                document.getElementById('lokasiField').style.display = 'block';
                document.getElementById('fileUploadField').style.display = 'none';
            }

            document.getElementById('tambahDokumenModal').style.display = 'block';
        }

        // Tutup modal
        function closeModal() {
            document.getElementById('tambahDokumenModal').style.display = 'none';
        }

        // Menampilkan field berdasarkan tipe yang dipilih
        document.getElementById('tipe').addEventListener('change', function() {
            const tipe = this.value;
            document.getElementById('fileUploadField').style.display = 'none';
            document.getElementById('lokasiField').style.display = 'none';

            if (tipe === 'Document' || tipe === 'Image' || tipe === 'Video') {
                document.getElementById('fileUploadField').style.display = 'block';
            } else if (tipe === 'URL') {
                document.getElementById('lokasiField').style.display = 'block';
            }
        });

        // Filter dokumen berdasarkan tipe yang dipilih
        function filterByType() {
            const selectedType = document.getElementById('filterTipe').value;

            const rows = document.querySelectorAll('#detailItemTableBody tr');

            rows.forEach(row => {
                const itemType = row.getAttribute('data-type');
                if (selectedType === 'All' || itemType === selectedType) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
@endpush
