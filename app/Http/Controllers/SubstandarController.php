<?php

namespace App\Http\Controllers;

use App\Models\Substandar;
use App\Models\Standar;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubstandarController extends Controller
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

        // Filter substandar berdasarkan standar yang dipilih
        $substandars = collect();
        if ($request->has('standar_id')) {
            $selectedStandarId = $request->standar_id;
            $substandars = Substandar::where('standar_id', $selectedStandarId)->get();
        }

        // Tentukan nomor urut berikutnya
        $lastNumber = Substandar::where('standar_id', $request->standar_id)->max('no_urut');
        $nextNumber = $lastNumber ? $lastNumber + 1 : 1;

        return view('pages.master.substandar', compact('fakultas', 'prodi', 'standars', 'substandars', 'nextNumber'));
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

        return redirect()->route('substandar.index', ['standar_id' => $request->standar_id])->with('success', 'Substandar berhasil ditambahkan!');
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

        return redirect()->route('substandar.index', ['standar_id' => $substandar->standar_id])->with('success', 'Substandar berhasil diperbarui!');
    }

    public function destroy(Substandar $substandar)
    {
        $standar_id = $substandar->standar_id;
        $substandar->delete();

        return redirect()->route('substandar.index', ['standar_id' => $standar_id])->with('success', 'Substandar berhasil dihapus!');
    }
}
