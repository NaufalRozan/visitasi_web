<?php

namespace App\Http\Controllers;

use App\Models\Akreditasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Prodi;

class AkreditasiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Ambil prodi_id dari session
        $prodi_id = session('prodi_id');

        // Pastikan prodi_id ada di session
        if (!$prodi_id) {
            return redirect()->route('login')->withErrors('Prodi tidak ditemukan. Silakan login kembali.');
        }

        // Ambil prodi yang sesuai dengan prodi_id dari session
        $prodi = Prodi::find($prodi_id);

        // Pastikan prodi ditemukan
        if (!$prodi) {
            return redirect()->route('login')->withErrors('Prodi tidak ditemukan. Silakan login kembali.');
        }

        // Ambil fakultas dari prodi
        $fakultas = $prodi->fakultas;

        // Ambil semua akreditasi terkait prodi
        $akreditasis = Akreditasi::where('prodi_id', $prodi->id)->get();

        // Ambil no_urut terakhir dan tentukan no_urut berikutnya (jika diperlukan)
        $lastNumber = Akreditasi::where('prodi_id', $prodi->id)->max('id'); // Contoh: gunakan id untuk urutan

        $nextNumber = $lastNumber ? $lastNumber + 1 : 1;

        // Ambil semua Prodi untuk dropdown
        $prodis = Prodi::where('fakultas_id', $fakultas->id)->get();

        return view('pages.master.akreditasi', compact('fakultas', 'prodi', 'akreditasis', 'nextNumber', 'prodis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_akreditasi' => 'required|string|max:255',
            'prodi_id' => 'required|exists:prodi,id',
        ]);

        Akreditasi::create([
            'nama_akreditasi' => $request->nama_akreditasi,
            'prodi_id' => $request->prodi_id,
            'status' => 'tidak aktif', // Atur status default
        ]);

        return redirect()->route('akreditasi.index')->with('success', 'Akreditasi berhasil ditambahkan!');
    }


    public function update(Request $request, Akreditasi $akreditasi)
    {
        $request->validate([
            'nama_akreditasi' => 'required|string|max:255',
        ]);

        $akreditasi->update([
            'nama_akreditasi' => $request->nama_akreditasi,
        ]);

        return redirect()->route('akreditasi.index')->with('success', 'Akreditasi berhasil diperbarui!');
    }

    public function activate(Akreditasi $akreditasi)
    {
        // Nonaktifkan semua akreditasi pada prodi yang sama
        Akreditasi::where('prodi_id', $akreditasi->prodi_id)
            ->update(['status' => 'tidak aktif']);

        // Aktifkan akreditasi yang dipilih
        $akreditasi->update(['status' => 'aktif']);

        return redirect()->route('akreditasi.index')->with('success', 'Akreditasi berhasil diaktifkan!');
    }


    public function destroy(Akreditasi $akreditasi)
    {
        $akreditasi->delete();

        return redirect()->route('akreditasi.index')->with('success', 'Akreditasi berhasil dihapus!');
    }
}
