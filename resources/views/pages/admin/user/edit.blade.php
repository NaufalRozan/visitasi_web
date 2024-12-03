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

                    <!-- Input Nama -->
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}"
                            required>
                    </div>

                    <!-- Input Email -->
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}"
                            required>
                    </div>

                    <!-- Input Password -->
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control">
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                    </div>

                    <!-- Role Dropdown -->
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select name="role" id="role" class="form-control" required onchange="handleRoleChange()">
                            <option value="Universitas" {{ $user->role === 'Universitas' ? 'selected' : '' }}>Universitas
                            </option>
                            <option value="Fakultas" {{ $user->role === 'Fakultas' ? 'selected' : '' }}>Fakultas</option>
                            <option value="Prodi" {{ $user->role === 'Prodi' ? 'selected' : '' }}>Prodi</option>
                        </select>
                    </div>

                    <!-- Ceklis Fakultas -->
                    <div class="form-group" id="units-checkboxes"
                        style="display: {{ $user->role === 'Fakultas' ? 'block' : 'none' }}">
                        <label for="units">Pilih Fakultas</label>
                        @foreach ($unit as $fakultas)
                            <div>
                                <input type="checkbox" name="unit[]" value="{{ $fakultas->id }}" class="unit-checkbox"
                                    {{ $user->sub_units->pluck('unit_id')->contains($fakultas->id) ? 'checked' : '' }}
                                    onclick="selectUnit('{{ $fakultas->id }}')">
                                {{ $fakultas->nama_unit }}
                            </div>
                        @endforeach
                    </div>

                    <!-- Ceklis Prodi -->
                    <div class="form-group" id="sub_unit-checkboxes"
                        style="display: {{ $user->role === 'Prodi' ? 'block' : 'none' }}">
                        <label for="sub_unit">Pilih Prodi</label>
                        @foreach ($unit as $fakultas)
                            <strong>{{ $fakultas->nama_unit }}</strong>
                            @foreach ($fakultas->sub_units as $prodi)
                                <div>
                                    <input type="checkbox" name="sub_units[]" value="{{ $prodi->id }}"
                                        class="sub_unit-checkbox unit-{{ $fakultas->id }}"
                                        {{ $user->sub_units->pluck('id')->contains($prodi->id) ? 'checked' : '' }}>
                                    {{ $prodi->nama_sub_unit }}
                                </div>
                            @endforeach
                            <hr>
                        @endforeach
                    </div>

                    <!-- Submit -->
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

            if (role === 'Universitas') {
                selectAllFakultasAndProdi();
            } else if (role === 'Fakultas') {
                deselectAllProdi();
            } else {
                deselectAllFakultasAndProdi();
            }
        }

        function selectAllFakultasAndProdi() {
            document.querySelectorAll('.unit-checkbox, .sub_unit-checkbox').forEach(checkbox => checkbox.checked = true);
        }

        function deselectAllFakultasAndProdi() {
            document.querySelectorAll('.unit-checkbox, .sub_unit-checkbox').forEach(checkbox => checkbox.checked = false);
        }

        function deselectAllProdi() {
            document.querySelectorAll('.sub_unit-checkbox').forEach(checkbox => checkbox.checked = false);
        }
    </script>
@endpush
