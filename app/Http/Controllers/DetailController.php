<?php

namespace App\Http\Controllers;

use App\Models\Detail;
use App\Models\Substandar;
use App\Models\Standar;
use App\Models\Prodi;
use App\Models\Akreditasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DetailController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $prodi_id = session('prodi_id');

        if (!$prodi_id) {
            return redirect()->route('login')->withErrors('Prodi tidak ditemukan. Silakan login kembali.');
        }

        $prodi = Prodi::find($prodi_id);

        if (!$prodi) {
            return redirect()->route('login')->withErrors('Prodi tidak ditemukan. Silakan login kembali.');
        }

        $fakultas = $prodi->fakultas;
        if (!$fakultas) {
            return redirect()->route('login')->withErrors('Fakultas tidak ditemukan untuk prodi ini.');
        }

        // Ambil akreditasi aktif
        $akreditasis = Akreditasi::where('prodi_id', $prodi->id)->get();
        $akreditasi_aktif = $prodi->akreditasis()->where('status', 'aktif')->first();

        // Pilih akreditasi dari request atau aktif
        $selectedAkreditasiId = $request->input('akreditasi_id', $akreditasi_aktif ? $akreditasi_aktif->id : null);

        $standars = collect();
        if ($selectedAkreditasiId) {
            $standars = Standar::where('akreditasi_id', $selectedAkreditasiId)->get();
        }

        $substandars = collect();
        if ($request->has('standar_id')) {
            $selectedStandarId = $request->standar_id;
            $substandars = Substandar::where('standar_id', $selectedStandarId)->get();
        }

        $details = collect();
        if ($request->has('substandar_id')) {
            $selectedSubstandarId = $request->substandar_id;
            $details = Detail::where('substandar_id', $selectedSubstandarId)
                ->orderBy('no_urut')
                ->get();
        }

        $lastNumber = Detail::where('substandar_id', $request->substandar_id)->max('no_urut');
        $nextNumber = $lastNumber ? $lastNumber + 1 : 1;

        return view('pages.master.detail', compact('fakultas', 'prodi', 'akreditasis', 'standars', 'substandars', 'details', 'nextNumber', 'selectedAkreditasiId'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'no_urut' => 'required|integer',
            'nama_detail' => 'required|string|max:255',
            'substandar_id' => 'required|exists:substandar,id',
        ]);

        Detail::create([
            'no_urut' => $request->no_urut,
            'nama_detail' => $request->nama_detail,
            'substandar_id' => $request->substandar_id,
        ]);

        return redirect()->route('detail.index', [
            'akreditasi_id' => $request->akreditasi_id,
            'standar_id' => $request->standar_id,
            'substandar_id' => $request->substandar_id
        ])->with('success', 'Detail berhasil ditambahkan!');
    }

    public function updateOrder(Request $request)
    {
        $order = $request->input('order');

        foreach ($order as $item) {
            $detail = Detail::find($item['id']);
            $detail->no_urut = $item['no_urut'];
            $detail->save();
        }

        return response()->json(['status' => 'success']);
    }

    public function update(Request $request, Detail $detail)
    {
        $request->validate([
            'no_urut' => 'required|integer',
            'nama_detail' => 'required|string|max:255',
        ]);

        $detail->update([
            'no_urut' => $request->no_urut,
            'nama_detail' => $request->nama_detail,
        ]);

        return redirect()->route('detail.index', [
            'akreditasi_id' => $request->akreditasi_id,
            'standar_id' => $request->standar_id,
            'substandar_id' => $request->substandar_id
        ])->with('success', 'Detail berhasil diperbarui!');
    }

    public function destroy(Detail $detail)
    {
        $akrediatas_id = $detail->substandar->standar->akreditasi_id;
        $standar_id = $detail->substandar->standar_id;
        $substandar_id = $detail->substandar_id;
        $detail->delete();

        return redirect()->route('detail.index', [
            'substandar_id' => $substandar_id,
            'standar_id' => $standar_id,
            'akreditasi_id' => $akrediatas_id
        ])->with('success', 'Detail berhasil dihapus!');
    }
}
