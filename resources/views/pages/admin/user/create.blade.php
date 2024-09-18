@extends('layouts.app-user')

@section('title', 'Tambah User')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Tambah User</h1>
            </div>
            <div class="section-body">
                <form action="{{ route('user.store') }}" method="POST">
                    @csrf
                    <!-- Input Nama -->
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>

                    <!-- Input Email -->
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>

                    <!-- Input Password -->
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>

                    <!-- Role Dropdown -->
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select name="role" id="role" class="form-control" required onchange="handleRoleChange()">
                            <option value="">Pilih Role</option>
                            <option value="Universitas">Universitas</option>
                            <option value="Fakultas">Fakultas</option>
                            <option value="Prodi">Prodi</option>
                        </select>
                    </div>

                    <!-- Ceklis Fakultas -->
                    <div class="form-group" id="fakultas-checkboxes" style="display: none;">
                        <label for="fakultas">Pilih Fakultas</label>
                        <input type="text" id="searchFakultas" class="form-control mb-2" placeholder="Cari Fakultas...">
                        <div id="fakultasList">
                            @foreach ($fakultas as $fk)
                                <div>
                                    <input type="checkbox" name="fakultas[]" value="{{ $fk->id }}"
                                        class="fakultas-checkbox" onclick="selectFakultas('{{ $fk->id }}')">
                                    {{ $fk->nama_fakultas }}
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Ceklis Prodi Dikelompokkan berdasarkan Fakultas -->
                    <div class="form-group" id="prodi-checkboxes" style="display: none;">
                        <label for="prodi">Pilih Prodi</label>
                        <input type="text" id="searchProdi" class="form-control mb-2" placeholder="Cari Prodi...">
                        <div id="prodiList">
                            @foreach ($fakultas as $fk)
                                <div class="fakultas-group" id="fakultas-group-{{ $fk->id }}">
                                    <strong>{{ $fk->nama_fakultas }}</strong>
                                    @foreach ($fk->prodis as $prodi)
                                        <div>
                                            <input type="checkbox" name="prodi[]" value="{{ $prodi->id }}"
                                                class="prodi-checkbox fakultas-{{ $fk->id }}">
                                            {{ $prodi->nama_prodi }}
                                        </div>
                                    @endforeach
                                </div>
                                <hr> <!-- Separator antara fakultas -->
                            @endforeach
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary">Tambah User</button>
                </form>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        // Handle role selection change
        function handleRoleChange() {
            var role = document.getElementById('role').value;
            var fakultasCheckboxes = document.getElementById('fakultas-checkboxes');
            var prodiCheckboxes = document.getElementById('prodi-checkboxes');

            // Reset display for all checkbox groups
            fakultasCheckboxes.style.display = 'none';
            prodiCheckboxes.style.display = 'none';

            if (role === 'Fakultas') {
                fakultasCheckboxes.style.display = 'block'; // Show Fakultas checkboxes
                deselectAllProdi();
            } else if (role === 'Prodi') {
                prodiCheckboxes.style.display = 'block'; // Show Prodi checkboxes
                deselectAllFakultas();
            } else if (role === 'Universitas') {
                selectAllFakultasAndProdi(); // Select all Fakultas and Prodi
            } else {
                deselectAllFakultasAndProdi(); // Deselect everything
            }
        }

        // Select all Fakultas and Prodi
        function selectAllFakultasAndProdi() {
            var fakultasCheckboxes = document.querySelectorAll('.fakultas-checkbox');
            var prodiCheckboxes = document.querySelectorAll('.prodi-checkbox');

            fakultasCheckboxes.forEach(function(checkbox) {
                checkbox.checked = true;
            });

            prodiCheckboxes.forEach(function(checkbox) {
                checkbox.checked = true;
            });
        }

        // Deselect all Fakultas and Prodi
        function deselectAllFakultasAndProdi() {
            var fakultasCheckboxes = document.querySelectorAll('.fakultas-checkbox');
            var prodiCheckboxes = document.querySelectorAll('.prodi-checkbox');

            fakultasCheckboxes.forEach(function(checkbox) {
                checkbox.checked = false;
            });

            prodiCheckboxes.forEach(function(checkbox) {
                checkbox.checked = false;
            });
        }

        // Deselect all Fakultas
        function deselectAllFakultas() {
            var fakultasCheckboxes = document.querySelectorAll('.fakultas-checkbox');

            fakultasCheckboxes.forEach(function(checkbox) {
                checkbox.checked = false;
            });
        }

        // Deselect all Prodi
        function deselectAllProdi() {
            var prodiCheckboxes = document.querySelectorAll('.prodi-checkbox');

            prodiCheckboxes.forEach(function(checkbox) {
                checkbox.checked = false;
            });
        }

        // When Fakultas checkbox is selected or deselected
        function selectFakultas(fakultasId) {
            var fakultasCheckbox = document.querySelector('.fakultas-checkbox[value="' + fakultasId + '"]');
            var prodiCheckboxes = document.querySelectorAll('.fakultas-' + fakultasId);

            if (fakultasCheckbox.checked) {
                // Select all Prodi under the selected Fakultas
                prodiCheckboxes.forEach(function(checkbox) {
                    checkbox.checked = true;
                });
            } else {
                // Deselect all Prodi under the deselected Fakultas
                prodiCheckboxes.forEach(function(checkbox) {
                    checkbox.checked = false;
                });
            }
        }

        // Filter Fakultas search
        document.getElementById('searchFakultas').addEventListener('input', function() {
            var searchValue = this.value.toLowerCase();
            var fakultasItems = document.querySelectorAll('#fakultasList div');

            fakultasItems.forEach(function(item) {
                if (item.innerText.toLowerCase().includes(searchValue)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        // Filter Prodi search
        document.getElementById('searchProdi').addEventListener('input', function() {
            var searchValue = this.value.toLowerCase();
            var prodiItems = document.querySelectorAll('#prodiList .fakultas-group');

            prodiItems.forEach(function(group) {
                var prodiMatches = false;

                group.querySelectorAll('div').forEach(function(prodiItem) {
                    if (prodiItem.innerText.toLowerCase().includes(searchValue)) {
                        prodiItem.style.display = 'block';
                        prodiMatches = true;
                    } else {
                        prodiItem.style.display = 'none';
                    }
                });

                // Show or hide the entire fakultas group based on prodi matches
                if (prodiMatches) {
                    group.style.display = 'block';
                } else {
                    group.style.display = 'none';
                }
            });
        });
    </script>
@endpush
