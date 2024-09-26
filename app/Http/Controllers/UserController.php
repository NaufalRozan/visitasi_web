<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Fakultas;
use App\Models\Prodi;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderByRaw("FIELD(role, 'Universitas', 'Fakultas', 'Prodi')")
            ->get();

        return view('pages.admin.user.home', compact('users'));
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
}
