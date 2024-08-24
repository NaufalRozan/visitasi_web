<?php

namespace App\Http\Controllers;

use App\Models\Detail;
use App\Models\Substandar;
use App\Models\Standar;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DetailController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $prodi = $user->prodi;
        $fakultas = $prodi->fakultas;

        // Ambil semua standar terkait prodi
        $standars = Standar::whereHas('akreditasi', function ($query) use ($prodi) {
            $query->where('prodi_id', $prodi->id);
        })->get();

        // Ambil semua substandar berdasarkan standar yang dipilih
        $substandars = collect();
        if ($request->has('standar_id')) {
            $selectedStandarId = $request->standar_id;
            $substandars = Substandar::where('standar_id', $selectedStandarId)->get();
        }

        // Ambil semua detail berdasarkan substandar yang dipilih
        $details = collect();
        if ($request->has('substandar_id')) {
            $selectedSubstandarId = $request->substandar_id;
            $details = Detail::where('substandar_id', $selectedSubstandarId)->get();
        }

        // Tentukan nomor urut berikutnya
        $lastNumber = Detail::where('substandar_id', $request->substandar_id)->max('no_urut');
        $nextNumber = $lastNumber ? $lastNumber + 1 : 1;

        return view('pages.master.detail', compact('fakultas', 'prodi', 'standars', 'substandars', 'details', 'nextNumber'));
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

        return redirect()->route('detail.index', ['substandar_id' => $request->substandar_id])->with('success', 'Detail berhasil ditambahkan!');
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

        return redirect()->route('detail.index', ['substandar_id' => $detail->substandar_id])->with('success', 'Detail berhasil diperbarui!');
    }

    public function destroy(Detail $detail)
    {
        $substandar_id = $detail->substandar_id;
        $detail->delete();

        return redirect()->route('detail.index', ['substandar_id' => $substandar_id])->with('success', 'Detail berhasil dihapus!');
    }
}
