<?php

namespace App\Http\Controllers;

use App\Models\Substandar;
use App\Models\Standar;
use App\Models\Prodi;
use App\Models\Akreditasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubstandarController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $prodi_id = session('prodi_id');

        // Pastikan prodi_id ada di session
        if (!$prodi_id) {
            return redirect()->route('login')->withErrors('Prodi tidak ditemukan. Silakan login kembali.');
        }

        // Ambil prodi berdasarkan prodi_id dari session
        $prodi = Prodi::find($prodi_id);

        // Pastikan prodi ditemukan
        if (!$prodi) {
            return redirect()->route('login')->withErrors('Prodi tidak ditemukan. Silakan login kembali.');
        }

        // Pastikan fakultas ditemukan
        $fakultas = $prodi->fakultas;
        if (!$fakultas) {
            return redirect()->route('login')->withErrors('Fakultas tidak ditemukan untuk prodi ini.');
        }

        // Ambil semua akreditasi terkait prodi
        $akreditasis = Akreditasi::where('prodi_id', $prodi->id)->get();

        // Ambil akreditasi aktif (status = 'aktif')
        $akreditasi_aktif = $prodi->akreditasis()->where('status', 'aktif')->first();

        // Jika ada request akreditasi_id gunakan, jika tidak pilih yang aktif
        $selectedAkreditasiId = $request->input('akreditasi_id', $akreditasi_aktif ? $akreditasi_aktif->id : null);

        // Filter standar berdasarkan akreditasi yang dipilih
        $standars = collect();
        if ($selectedAkreditasiId) {
            $standars = Standar::where('akreditasi_id', $selectedAkreditasiId)->get();
        }

        // Filter substandar berdasarkan standar yang dipilih
        $substandars = collect();
        if ($request->has('standar_id')) {
            $selectedStandarId = $request->standar_id;
            $substandars = Substandar::where('standar_id', $selectedStandarId)
                ->orderBy('no_urut')
                ->get();
        }

        // Tentukan nomor urut berikutnya
        $lastNumber = Substandar::where('standar_id', $request->standar_id)->max('no_urut');
        $nextNumber = $lastNumber ? $lastNumber + 1 : 1;

        return view('pages.master.substandar', compact('fakultas', 'prodi', 'akreditasis', 'standars', 'substandars', 'nextNumber', 'selectedAkreditasiId'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'no_urut' => 'required|integer',
            'nama_substandar' => 'required|string|max:255',
            'standar_id' => 'required|exists:standar,id',
        ]);

        Substandar::create([
            'no_urut' => $request->no_urut,
            'nama_substandar' => $request->nama_substandar,
            'standar_id' => $request->standar_id,
        ]);

        return redirect()->route('substandar.index', ['standar_id' => $request->standar_id, 'akreditasi_id' => $request->akreditasi_id])->with('success', 'Substandar berhasil ditambahkan!');
    }

    public function updateOrder(Request $request)
    {
        $order = $request->input('order');

        foreach ($order as $item) {
            $substandar = Substandar::find($item['id']);
            $substandar->no_urut = $item['no_urut'];
            $substandar->save();
        }

        return response()->json(['status' => 'success']);
    }

    public function update(Request $request, Substandar $substandar)
    {
        $request->validate([
            'no_urut' => 'required|integer',
            'nama_substandar' => 'required|string|max:255',
        ]);

        $substandar->update([
            'no_urut' => $request->no_urut,
            'nama_substandar' => $request->nama_substandar,
        ]);

        return redirect()->route('substandar.index', ['standar_id' => $substandar->standar_id, 'akreditasi_id' => $request->akreditasi_id])->with('success', 'Substandar berhasil diperbarui!');
    }

    public function destroy(Substandar $substandar)
    {
        $akreditasi_id = $substandar->standar->akreditasi_id;
        $standar_id = $substandar->standar_id;
        $substandar->delete();

        return redirect()->route('substandar.index', ['standar_id' => $standar_id, 'akreditasi_id' => $akreditasi_id])->with('success', 'Substandar berhasil dihapus!');
    }
}
