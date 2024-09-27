@extends('layouts.app-resume')

@section('title', 'Dashboard')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Dashboard</h1>
            </div>

            <!-- Dropdown Pemilihan Fakultas, Prodi, dan Akreditasi -->
            <div class="section-body">
                <form method="GET" action="{{ route('resume.index') }}">
                    <div class="form-group d-flex justify-content-between">
                        <!-- Kolom Kiri: Fakultas -->
                        <div class="w-50 pr-2">
                            <label for="units">Fakultas</label>
                            <select name="unit_id" id="units" class="form-control" disabled>
                                @if ($sub_unit && $sub_unit->units)
                                    <option value="{{ $sub_unit->units->id }}">{{ $sub_unit->units->nama_unit }}</option>
                                @else
                                    <option value="">Fakultas tidak ditemukan</option>
                                @endif
                            </select>
                        </div>

                        <!-- Kolom Tengah: Prodi -->
                        <div class="w-50 pl-2">
                            <label for="sub_units">Program Studi</label>
                            <select name="sub_unit_id" id="sub_units" class="form-control" disabled>
                                @if ($sub_unit)
                                    <option value="{{ $sub_unit->id }}">{{ $sub_unit->nama_sub_unit }}</option>
                                @else
                                    <option value="">Prodi tidak ditemukan</option>
                                @endif
                            </select>
                        </div>
                    </div>

                    <!-- Dropdown Akreditasi -->
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

            <!-- Tabel Resume Dokumen -->
            @if ($standars->isNotEmpty())
                <div class="card card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 50%;">Standar</th>
                                    <th style="width: 10%;">Documents</th>
                                    <th style="width: 10%;">URLs</th>
                                    <th style="width: 10%;">Images</th>
                                    <th style="width: 10%;">Videos</th>
                                    <th style="width: 10%;">Total</th> <!-- Kolom Total Items -->
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalDocuments = 0;
                                    $totalURLs = 0;
                                    $totalImages = 0;
                                    $totalVideos = 0;
                                    $totalItems = 0; // Variabel untuk total semua item
                                @endphp

                                @foreach ($standars as $standar)
                                    @php
                                        $standarDocumentCount = $standar->details->sum('document_count');
                                        $standarUrlCount = $standar->details->sum('url_count');
                                        $standarImageCount = $standar->details->sum('image_count');
                                        $standarVideoCount = $standar->details->sum('video_count');
                                        $standarTotal =
                                            $standarDocumentCount +
                                            $standarUrlCount +
                                            $standarImageCount +
                                            $standarVideoCount;
                                    @endphp
                                    <tr>
                                        <td>{{ $standar->nama_standar }}</td>
                                        <td>{{ $standarDocumentCount }}</td>
                                        <td>{{ $standarUrlCount }}</td>
                                        <td>{{ $standarImageCount }}</td>
                                        <td>{{ $standarVideoCount }}</td>
                                        <td>{{ $standarTotal }}</td> <!-- Total per Standar -->
                                    </tr>

                                    @php
                                        // Hitung total dokumen, url, image, video, dan total item
                                        $totalDocuments += $standarDocumentCount;
                                        $totalURLs += $standarUrlCount;
                                        $totalImages += $standarImageCount;
                                        $totalVideos += $standarVideoCount;
                                        $totalItems += $standarTotal; // Total semua item dari setiap standar
                                    @endphp
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Total</th>
                                    <th>{{ $totalDocuments }}</th>
                                    <th>{{ $totalURLs }}</th>
                                    <th>{{ $totalImages }}</th>
                                    <th>{{ $totalVideos }}</th>
                                    <th>{{ $totalItems }}</th> <!-- Total seluruh item -->
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            @else
                <p class="text-center">Tidak ada data yang ditemukan untuk fakultas, prodi, dan akreditasi yang dipilih.</p>
            @endif
        </section>
    </div>
@endsection
