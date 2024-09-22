<?php

namespace App\Http\Controllers;

use App\Models\Akreditasi;
use App\Models\Fakultas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Prodi;

class AkreditasiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user(); // Ambil data user yang login

        if ($user->role === 'Prodi') {
            // Ambil prodi_id dari session jika user adalah Prodi
            $prodi_id = session('prodi_id');

            if (!$prodi_id) {
                return redirect()->route('login')->withErrors('Prodi tidak ditemukan. Silakan login kembali.');
            }

            $prodi = Prodi::find($prodi_id);

            if (!$prodi) {
                return redirect()->route('login')->withErrors('Prodi tidak ditemukan.');
            }

            $fakultas = $prodi->fakultas;

            $akreditasis = Akreditasi::where('prodi_id', $prodi->id)->get();

            return view('pages.master.akreditasi', compact('fakultas', 'prodi', 'akreditasis', 'user'));
        } else {
            // Jika user role lain selain Prodi (misalnya Universitas atau Fakultas)
            $prodis = $user->prodis;
            $fakultas_ids = $prodis->pluck('fakultas_id')->unique();
            $fakultas = Fakultas::whereIn('id', $fakultas_ids)->get();

            $selected_fakultas_id = $request->input('fakultas_id');
            $selected_prodi_id = $request->input('prodi_id');

            $prodi = null; // Default value null

            if ($selected_prodi_id) {
                $prodi = Prodi::find($selected_prodi_id); // Ambil prodi yang sesuai
                $akreditasis = Akreditasi::where('prodi_id', $selected_prodi_id)->get();
            } else {
                $akreditasis = collect(); // Kosongkan jika belum ada prodi yang dipilih
            }

            return view('pages.master.akreditasi', compact('fakultas', 'prodis', 'akreditasis', 'user', 'prodi'));
        }
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

        return redirect()->route('akreditasi.index', [
            'prodi_id' => $request->prodi_id,
            'fakultas_id' => Prodi::find($request->prodi_id)->fakultas_id,
        ])->with('success', 'Akreditasi berhasil ditambahkan!');
    }


    public function update(Request $request, Akreditasi $akreditasi)
    {
        $request->validate([
            'nama_akreditasi' => 'required|string|max:255',
        ]);

        $akreditasi->update([
            'nama_akreditasi' => $request->nama_akreditasi,
        ]);

        return redirect()->route(
            'akreditasi.index',
            [
                'prodi_id' => $akreditasi->prodi_id,
                'fakultas_id' => Prodi::find($akreditasi->prodi_id)->fakultas_id,
            ]
        )->with('success', 'Akreditasi berhasil diperbarui!');
    }

    public function activate(Akreditasi $akreditasi)
    {
        // Nonaktifkan semua akreditasi pada prodi yang sama
        Akreditasi::where('prodi_id', $akreditasi->prodi_id)
            ->update(['status' => 'tidak aktif']);

        // Aktifkan akreditasi yang dipilih
        $akreditasi->update(['status' => 'aktif']);

        return redirect()->route(
            'akreditasi.index',
            [
                'prodi_id' => $akreditasi->prodi_id,
                'fakultas_id' => Prodi::find($akreditasi->prodi_id)->fakultas_id,
            ]
        )->with('success', 'Akreditasi berhasil diaktifkan!');
    }


    public function destroy(Akreditasi $akreditasi)
    {
        $akreditasi->delete();

        return redirect()->route('akreditasi.index', [
            'prodi_id' => $akreditasi->prodi_id,
            'fakultas_id' => Prodi::find($akreditasi->prodi_id)->fakultas_id,
        ])->with('success', 'Akreditasi berhasil dihapus!');
    }
}
