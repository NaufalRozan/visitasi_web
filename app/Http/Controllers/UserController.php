<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Fakultas;
use App\Models\Prodi;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Menampilkan daftar user
    public function index()
    {
        $users = User::with('sub_units') // Ambil data user beserta relasi sub_units
            ->orderByRaw("FIELD(role, 'Universitas', 'Fakultas', 'Prodi')")
            ->get();

        $unit = Fakultas::with('sub_units')->get(); // Ambil data fakultas beserta relasi sub_units (prodi)

        return view('pages.admin.user.home', compact('users', 'unit'));
    }

    public function getUser(User $user)
    {
        $user->load('sub_units'); // Memuat relasi sub_units
        return response()->json($user);
    }

    public function create()
    {
        // Ambil data fakultas dan relasi prodi untuk setiap fakultas
        $unit = Fakultas::with('sub_units')->get();
        return view('pages.admin.user.create', compact('unit'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string',
            'sub_units' => 'array', // Validasi agar input prodi adalah array
        ]);

        // Buat user baru
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'role' => $request->input('role'),
        ]);

        // Simpan relasi user dengan prodi
        if ($request->has('sub_unit')) {
            $user->sub_units()->sync($request->input('sub_unit'));
        }

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function edit(User $user)
    {
        $unit = Fakultas::with('sub_units')->get(); // Data Fakultas beserta relasi Prodi
        return view('pages.admin.user.edit', compact('user', 'unit'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|string',
            'sub_units' => 'nullable|array',
        ]);

        // Update data user
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->filled('password') ? bcrypt($request->input('password')) : $user->password,
            'role' => $request->input('role'),
        ]);

        // Atur sub_units berdasarkan role
        $role = $request->input('role');
        $subUnits = [];

        if ($role === 'Universitas') {
            // Jika role Universitas, ambil semua Prodi
            $subUnits = Prodi::pluck('id')->toArray();
        } elseif ($role === 'Fakultas') {
            // Jika role Fakultas, ambil semua Prodi yang terkait dengan Fakultas yang dipilih
            if ($request->has('unit')) {
                $subUnits = Prodi::whereIn('unit_id', $request->input('unit'))->pluck('id')->toArray();
            }
        } elseif ($role === 'Prodi') {
            // Jika role Prodi, gunakan Prodi yang dipilih langsung
            $subUnits = $request->input('sub_units', []);
        }

        // Sinkronisasi relasi sub_units
        $user->sub_units()->sync($subUnits);

        return redirect()->route('user.index')->with('success', 'User berhasil diperbarui!');
    }


    // Menghapus data user
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('user.index')->with('success', 'User berhasil dihapus!');
    }
}
