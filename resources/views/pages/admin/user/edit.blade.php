@extends('layouts.app-user')

@section('title', 'Edit User')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit User</h1>
            </div>
            <div class="section-body">
                <form action="{{ route('user.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Nama -->
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}"
                            required>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}"
                            required>
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control">
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                    </div>

                    <!-- Role -->
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select name="role" id="role" class="form-control" required onchange="handleRoleChange()">
                            <option value="Universitas" {{ $user->role == 'Universitas' ? 'selected' : '' }}>Universitas
                            </option>
                            <option value="Fakultas" {{ $user->role == 'Fakultas' ? 'selected' : '' }}>Fakultas</option>
                            <option value="Prodi" {{ $user->role == 'Prodi' ? 'selected' : '' }}>Prodi</option>
                        </select>
                    </div>

                    <!-- Fakultas -->
                    <div id="units-checkboxes" class="form-group"
                        style="{{ $user->role == 'Fakultas' ? '' : 'display: none;' }}">
                        <label for="units">Pilih Fakultas</label>
                        @foreach ($unit as $fakultas)
                            <div>
                                <input type="checkbox" class="unit-checkbox" name="sub_units[]" value="{{ $fakultas->id }}"
                                    onclick="selectUnit('{{ $fakultas->id }}')"
                                    {{ $user->sub_units->pluck('id')->contains($fakultas->id) ? 'checked' : '' }}>
                                {{ $fakultas->nama_unit }}
                            </div>
                        @endforeach
                    </div>

                    <!-- Prodi -->
                    <div id="sub_unit-checkboxes" class="form-group"
                        style="{{ $user->role == 'Prodi' ? '' : 'display: none;' }}">
                        <label for="sub_units">Pilih Prodi</label>
                        @foreach ($unit as $fakultas)
                            <strong>{{ $fakultas->nama_unit }}</strong>
                            @foreach ($fakultas->sub_units as $prodi)
                                <div>
                                    <input type="checkbox" class="sub_unit-checkbox unit-{{ $fakultas->id }}"
                                        name="sub_units[]" value="{{ $prodi->id }}"
                                        {{ $user->sub_units->pluck('id')->contains($prodi->id) ? 'checked' : '' }}>
                                    {{ $prodi->nama_sub_unit }}
                                </div>
                            @endforeach
                            <hr>
                        @endforeach
                    </div>

                    <div>
                        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                        <a href="{{ route('user.index') }}" class="btn btn-primary">Kembali</a>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        function handleRoleChange() {
            const role = document.getElementById('role').value;
            document.getElementById('units-checkboxes').style.display = (role === 'Fakultas') ? 'block' : 'none';
            document.getElementById('sub_unit-checkboxes').style.display = (role === 'Prodi') ? 'block' : 'none';
        }
    </script>
@endpush
