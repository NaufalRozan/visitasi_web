<?php

namespace App\Http\Controllers;

use App\Models\Akreditasi;
use App\Models\Standar;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StandarController extends Controller
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
        $akreditasis = $prodi->akreditasis()->get();

        // Ambil akreditasi aktif (status = 'aktif')
        $akreditasi_aktif = $prodi->akreditasis()->where('status', 'aktif')->first();

        // Jika ada request akreditasi_id gunakan, jika tidak pilih yang aktif
        $selected_akreditasi_id = $request->input('akreditasi_id', $akreditasi_aktif ? $akreditasi_aktif->id : null);

        // Ambil data standar hanya jika akreditasi_id dipilih
        $standars = collect(); // Inisialisasi sebagai collection kosong
        if ($selected_akreditasi_id) {
            $standars = Standar::where('akreditasi_id', $selected_akreditasi_id)
                ->orderBy('no_urut', 'asc')
                ->get();
        }

        // Tentukan nomor urut berikutnya
        $lastNumber = Standar::where('akreditasi_id', $selected_akreditasi_id)->max('no_urut');
        $nextNumber = $lastNumber ? $lastNumber + 1 : 1;

        return view('pages.master.standar', compact('fakultas', 'prodi', 'standars', 'nextNumber', 'akreditasis', 'selected_akreditasi_id'));
    }



    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'no_urut' => 'required|integer',
            'nama_standar' => 'required|string|max:255',
            'akreditasi_id' => 'required|exists:akreditasi,id', // Validasi akreditasi_id
        ]);

        // Simpan data standar baru dengan nomor urut dari input user
        Standar::create([
            'no_urut' => $request->no_urut,
            'nama_standar' => $request->nama_standar,
            'akreditasi_id' => $request->akreditasi_id,
        ]);

        return redirect()->route('standar.index', ['akreditasi_id' => $request->akreditasi_id])->with('success', 'Standar berhasil ditambahkan!');
    }

    public function updateOrder(Request $request)
    {
        $order = $request->order;

        foreach ($order as $item) {
            $standar = Standar::find($item['id']);
            $standar->no_urut = $item['no_urut'];
            $standar->save();
        }

        return response()->json(['success' => true]);
    }



    public function update(Request $request, Standar $standar)
    {
        $request->validate([
            'no_urut' => 'required|integer',
            'nama_standar' => 'required|string|max:255',
        ]);

        $standar->update([
            'no_urut' => $request->no_urut,
            'nama_standar' => $request->nama_standar,
        ]);

        return redirect()->route('standar.index', ['akreditasi_id' => $request->akreditasi_id])->with('success', 'Standar berhasil diperbarui!');
    }

    public function destroy(Standar $standar)
    {
        $akreditasi_id = $standar->akreditasi_id;
        $standar->delete();

        return redirect()->route('standar.index', ['akreditasi_id' => $akreditasi_id])->with('success', 'Standar berhasil dihapus!');
    }
}
