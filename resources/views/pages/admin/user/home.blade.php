@extends('layouts.app-user')

@section('title', 'Daftar User')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Daftar User</h1>
            </div>

            <div class="section-body">
                <div class="card card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Prodi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr>
                                        <td class="align-top p-2 width" style="width: 25%;">{{ $user->name }}</td>
                                        <td class="align-top p-2" style="width: 20%;">{{ $user->email }}</td>
                                        <td class="align-top p-2" style="width: 10%;">{{ $user->role }}</td>
                                        <td class="align-top p-2" style="width: 30%;">
                                            @foreach ($user->sub_units as $sub_units)
                                                {{ $sub_units->nama_sub_unit }}<br>
                                            @endforeach
                                        </td>
                                        <td>
                                            <a href="{{ route('user.edit', $user) }}"
                                                class="btn btn-warning btn-sm">Edit</a>
                                            <form id="delete-form-{{ $user->id }}"
                                                action="{{ route('user.destroy', $user) }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <button type="button" class="btn btn-danger btn-sm"
                                                onclick="confirmDelete({{ $user->id }})">Delete</button>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Belum ada pengguna.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <x-sweet-alert-delete />
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/simpleweather/jquery.simpleWeather.min.js') }}"></script>
    <script src="{{ asset('library/chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('library/summernote/dist/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('library/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/index-0.js') }}"></script>
@endpush
