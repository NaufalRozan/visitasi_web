<?php

namespace App\Http\Controllers;

use App\Models\Substandar;
use App\Models\Standar;
use App\Models\Prodi;
use App\Models\Akreditasi;
use App\Models\Fakultas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubstandarController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'Prodi') {
            $prodi_id = session('prodi_id');

            if (!$prodi_id) {
                return redirect()->route('login')->withErrors('Prodi tidak ditemukan. Silakan login kembali.');
            }

            $prodi = Prodi::find($prodi_id);

            if (!$prodi) {
                return redirect()->route('login')->withErrors('Prodi tidak ditemukan.');
            }

            $fakultas = $prodi->fakultas;

            if (!$fakultas) {
                return redirect()->route('login')->withErrors('Fakultas tidak ditemukan untuk prodi ini.');
            }

            $akreditasi_aktif = $prodi->akreditasis()->where('status', 'aktif')->first();
            $selected_akreditasi_id = $akreditasi_aktif ? $akreditasi_aktif->id : null;

            $standars = Standar::where('akreditasi_id', $selected_akreditasi_id)->get();

            $substandars = collect();
            if ($request->has('standar_id')) {
                $selectedStandarId = $request->standar_id;
                $substandars = Substandar::where('standar_id', $selectedStandarId)
                    ->orderBy('no_urut')
                    ->get();
            }

            $lastNumber = Substandar::where('standar_id', $request->standar_id)->max('no_urut');
            $nextNumber = $lastNumber ? $lastNumber + 1 : 1;

            return view('pages.master.substandar', compact('fakultas', 'prodi', 'standars', 'substandars', 'nextNumber', 'selected_akreditasi_id', 'user'));
        } else {
            // Untuk role lainnya (Fakultas atau UNIV)
            $prodis = $user->prodis;
            $fakultas_ids = $prodis->pluck('fakultas_id')->unique();
            $fakultas = Fakultas::whereIn('id', $fakultas_ids)->get();

            $selected_fakultas_id = $request->input('fakultas_id');
            $selected_prodi_id = $request->input('prodi_id');

            $akreditasi_aktif = Akreditasi::where('prodi_id', $selected_prodi_id)
                ->where('status', 'aktif')
                ->first();
            $selected_akreditasi_id = $akreditasi_aktif ? $akreditasi_aktif->id : null;

            $standars = Standar::where('akreditasi_id', $selected_akreditasi_id)->get();

            $substandars = collect();
            if ($request->has('standar_id')) {
                $selectedStandarId = $request->standar_id;
                $substandars = Substandar::where('standar_id', $selectedStandarId)
                    ->orderBy('no_urut')
                    ->get();
            }

            $lastNumber = Substandar::where('standar_id', $request->standar_id)->max('no_urut');
            $nextNumber = $lastNumber ? $lastNumber + 1 : 1;

            return view('pages.master.substandar', compact('fakultas', 'prodis', 'standars', 'substandars', 'nextNumber', 'selected_akreditasi_id', 'user'));
        }
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

        return redirect()->route('substandar.index', [
            'akreditasi_id' => $request->akreditasi_id,
            'fakultas_id' => Standar::find($request->standar_id)->akreditasi->prodi->fakultas_id,
            'prodi_id' => Standar::find($request->standar_id)->akreditasi->prodi_id,
            'standar_id' => $request->standar_id,
        ])->with('success', 'Substandar berhasil ditambahkan!');
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

        return redirect()->route('substandar.index', [
            'akreditasi_id' => $substandar->standar->akreditasi_id,
            'fakultas_id' => $substandar->standar->akreditasi->prodi->fakultas_id,
            'prodi_id' => $substandar->standar->akreditasi->prodi_id,
            'standar_id' => $substandar->standar_id,
        ])->with('success', 'Substandar berhasil diperbarui!');
    }

    public function destroy(Substandar $substandar)
    {
        $akreditasi_id = $substandar->standar->akreditasi_id;
        $standar_id = $substandar->standar_id;
        $substandar->delete();

        return redirect()->route('substandar.index', [
            'akreditasi_id' => $akreditasi_id,
            'fakultas_id' => Standar::find($standar_id)->akreditasi->prodi->fakultas_id,
            'prodi_id' => Standar::find($standar_id)->akreditasi->prodi_id,
            'standar_id' => $standar_id,
        ])->with('success', 'Substandar berhasil dihapus!');
    }
}
