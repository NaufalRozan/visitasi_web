<?php

namespace App\Http\Controllers;

use App\Models\Standar;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StandarController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $prodi = $user->prodi;
        $fakultas = $prodi->fakultas;

        // Ambil data standar berdasarkan prodi_id dari user yang login
        $standars = Standar::whereHas('akreditasi', function ($query) use ($prodi) {
            $query->where('prodi_id', $prodi->id);
        })->get();

        // Ambil no_urut terakhir dan tentukan no_urut berikutnya
        $lastNumber = Standar::whereHas('akreditasi', function ($query) use ($prodi) {
            $query->where('prodi_id', $prodi->id);
        })->max('no_urut');

        $nextNumber = $lastNumber ? $lastNumber + 1 : 1;

        // Ambil semua Prodi untuk dropdown
        $prodis = Prodi::where('fakultas_id', $fakultas->id)->get();

        return view('pages.master.standar', compact('fakultas', 'prodi', 'standars', 'nextNumber', 'prodis'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_standar' => 'required|string|max:255',
            'prodi_id' => 'required|exists:prodi,id', // Validasi prodi_id
        ]);

        // Ambil akreditasi_id berdasarkan prodi_id
        $prodi = Prodi::find($request->prodi_id);
        $akreditasi = $prodi->akreditasi;

        if (!$akreditasi) {
            return redirect()->back()->withErrors('Akreditasi untuk prodi yang dipilih tidak ditemukan.');
        }

        // Hitung jumlah data standar yang sudah ada untuk menentukan no_urut
        $lastNumber = Standar::whereHas('akreditasi', function ($query) use ($prodi) {
            $query->where('prodi_id', $prodi->id);
        })->max('no_urut');

        $newNumber = $lastNumber ? $lastNumber + 1 : 1;

        // Simpan data standar baru
        Standar::create([
            'no_urut' => $newNumber,
            'nama_standar' => $request->nama_standar,
            'akreditasi_id' => $akreditasi->id,
        ]);

        return redirect()->route('standar.index')->with('success', 'Standar berhasil ditambahkan!');
    }
}
