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
            'sub_units' => 'array',
        ]);

        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->filled('password') ? bcrypt($request->input('password')) : $user->password,
            'role' => $request->input('role'),
        ]);

        if ($request->has('sub_units')) {
            $user->sub_units()->sync($request->input('sub_units'));
        }

        return redirect()->route('user.index')->with('success', 'User berhasil diperbarui!');
    }

    // Menghapus data user
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('user.index')->with('success', 'User berhasil dihapus!');
    }
}
